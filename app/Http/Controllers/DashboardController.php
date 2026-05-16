<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Utama
     * Path: resources/js/Pages/Admin/Dashboard/Index.vue
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard/Index');
    }
}