<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    if($user->role === 'karyawan') {
       
        $recentProjects = \App\Models\Project::with('client')->latest()->take(5)->get();

        // Data statis sementara
        $stats = [
            'properti_count' => 4,
            'laporan_count' => 4,
            'pesan_count' => 1
        ];

        
        return view('dashboards.karyawan.index', compact('recentProjects', 'stats'));
    }

    if ($user->role === 'client') {
        return view('dashboards.client.index');
    }

    if ($user->role === 'pekerjaTambahan') {
        return view('dashboards.pekerjaTambahan.index');
    }

    abort(403, 'Anda tidak memiliki akses ke halaman ini.');
}
}
