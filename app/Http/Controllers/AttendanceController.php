<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\CourseClass;
use App\Models\Subject;
use App\Models\User;
use App\Imports\StudentImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = CourseClass::with(['subject', 'teacher', 'teacher2', 'assistant', 'assistant2'])
            ->withCount('students');

        /**
         * SINKRONISASI DENGAN FRONTEND:
         * User dianggap "Management" jika is_admin = 1 ATAU punya permission 'view-all-attendance'
         */
        $isManagement = $user->is_admin == 1 || $user->hasPermissionTo('view-all-attendance');

        if (!$isManagement) {
            $query->where(function ($q) use ($user) {
                $q->where('teacher_id', $user->id)
                  ->orWhere('teacher2_id', $user->id)
                  ->orWhere('assistant_id', $user->id)
                  ->orWhere('assistant2_id', $user->id);
            });
        }

        // Filter Pencarian (Search)
        if ($request->filled('search')) {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            })->orWhere('name', 'like', '%' . $request->search . '%');
        }

        // Filter Tahun Akademik
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        return Inertia::render('Admin/Attendance/Index', [
            'classes' => $query->latest()->get(),
            'academicYears' => CourseClass::distinct()->orderBy('academic_year', 'desc')->pluck('academic_year'),
            'filters' => $request->only(['academic_year', 'search']),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => User::role('dosen')->orderBy('name')->get(),
            'assistants' => User::role('asisten_praktikum')->orderBy('name')->get(),
            // Kirim status management ke frontend untuk kepastian
            'userStatus' => [
                'isManagement' => $isManagement
            ]
        ]);
    }

    /**
     * Simpan Kelas Baru
     */
    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string',
            'teacher_id' => 'required|exists:users,id',
            'teacher2_id' => 'nullable|exists:users,id',
            'assistant_id' => 'nullable|exists:users,id',
            'assistant2_id' => 'nullable|exists:users,id',
        ]);

        CourseClass::create($validated);
        return back()->with('success', 'Kelas praktikum berhasil dibuat.');
    }

    /**
     * Update Kelas
     */
    public function updateClass(Request $request, CourseClass $courseClass)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string',
            'teacher_id' => 'required|exists:users,id',
            'teacher2_id' => 'nullable|exists:users,id',
            'assistant_id' => 'nullable|exists:users,id',
            'assistant2_id' => 'nullable|exists:users,id',
        ]);

        $courseClass->update($validated);
        return back()->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Hapus Kelas
     */
    public function destroyClass(CourseClass $courseClass)
    {
        // Pastikan tidak ada data presensi yang bergantung jika ingin menghapus aman, 
        // atau gunakan cascade delete di migration.
        $courseClass->delete();
        return back()->with('success', 'Kelas berhasil dihapus dari sistem.');
    }

    /**
     * Tampilkan detail sesi presensi (halaman proyektor/QR)
     */
    public function showSession(CourseClass $courseClass)
    {
        $session = AttendanceSession::where('course_class_id', $courseClass->id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        $attendances = [];
        $qrCodeSvg = null;

        if ($session) {
            $sessionNumber = AttendanceSession::where('course_class_id', $courseClass->id)
                ->where('created_at', '<=', $session->created_at)
                ->count();

            $attendances = Attendance::with('student')
                ->where('attendance_session_id', $session->id)
                ->latest()
                ->get();

            if ($session->is_active) {
                $qrCodeSvg = QrCode::size(300)
                    ->color(59, 130, 246)
                    ->backgroundColor(255, 255, 255)
                    ->margin(2)
                    ->generate($session->qr_token);
            }
        } else {
            $sessionNumber = AttendanceSession::where('course_class_id', $courseClass->id)->count() + 1;
        }

        return Inertia::render('Admin/Attendance/Session', [
            'courseClass' => $courseClass->load('subject'),
            'session' => $session,
            'attendances' => $attendances,
            'qrCode' => $qrCodeSvg ? (string) $qrCodeSvg : null,
            'sessionNumber' => $sessionNumber,
            'today' => Carbon::now()->translatedFormat('d F Y'),
        ]);
    }

    public function startSession(CourseClass $courseClass)
    {
        $nextNumber = AttendanceSession::where('course_class_id', $courseClass->id)->count() + 1;

        AttendanceSession::create([
            'course_class_id' => $courseClass->id,
            'creator_id' => auth()->id(),
            'title' => 'Pertemuan ke-' . $nextNumber . ' • ' . Carbon::now()->translatedFormat('d F Y'),
            'qr_token' => Str::random(40),
            'expires_at' => Carbon::now()->addHours(3),
            'is_active' => true,
        ]);

        return back()->with('success', 'Sesi praktikum hari ini telah dibuka!');
    }

    public function toggleSession(AttendanceSession $session)
    {
        $session->update(['is_active' => !$session->is_active]);
        $status = $session->is_active ? 'dimunculkan' : 'disembunyikan';
        return back()->with('success', "QR Code berhasil $status.");
    }

    public function showScanner()
    {
        return Inertia::render('Attendance/Scan');
    }

    public function scan(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $session = AttendanceSession::where('qr_token', $request->token)
            ->where('is_active', true)
            ->first();

        if (!$session || Carbon::now()->greaterThan($session->expires_at)) {
            return back()->with('error', 'QR Code tidak berlaku atau presensi sudah ditutup.');
        }

        $isEnrolled = $session->courseClass->students()
            ->where('user_id', auth()->id())
            ->exists();

        if (!$isEnrolled) {
            return back()->with('error', 'Anda tidak terdaftar di kelas praktikum ini.');
        }

        Attendance::updateOrCreate(
            ['attendance_session_id' => $session->id, 'user_id' => auth()->id()],
            ['status' => 'hadir', 'device_info' => $request->userAgent(), 'logged_at' => now()]
        );

        return redirect()->route('dashboard')->with('success', 'Presensi berhasil! Selamat praktikum.');
    }

    public function report(CourseClass $courseClass)
    {
        $sessions = AttendanceSession::where('course_class_id', $courseClass->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $students = $courseClass->students()->with(['attendances' => function($query) use ($sessions) {
            $query->whereIn('attendance_session_id', $sessions->pluck('id'));
        }])->get();

        return Inertia::render('Admin/Attendance/Report', [
            'courseClass' => $courseClass->load(['subject', 'teacher']),
            'sessions' => $sessions,
            'reportData' => $students->map(function($student) use ($sessions) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'nim' => $student->nim_nip,
                    'presence' => $sessions->map(function($session) use ($student) {
                        return $student->attendances->where('attendance_session_id', $session->id)->first();
                    })
                ];
            })
        ]);
    }

    public function exportTemplate()
    {
        $headers = ['nim_nip', 'name', 'email'];
        $fileName = 'template_import_mahasiswa.csv';
        
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }

    public function import(Request $request, $classId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new StudentImport($classId), $request->file('file'));
            return back()->with('success', 'Data mahasiswa berhasil diimport ke kelas ini.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}