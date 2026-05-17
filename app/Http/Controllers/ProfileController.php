<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form via Inertia.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            // Jika data user sudah dishare secara global di HandleInertiaRequests Middleware,
            // prop 'user' ini opsional, tapi aman untuk tetap dikirimkan.
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     * (Catatan: Di Vue saat ini, field dibuat readonly, tapi method ini tetap diamankan untuk Inertia)
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('message', 'Profil berhasil diperbarui.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}