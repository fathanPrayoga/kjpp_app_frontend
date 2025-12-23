<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class PropertiController extends Controller
{
    public function karyawan()
    {
        // Ambil semua project dari client untuk karyawan lihat
        $projects = Project::latest()->take(10)->get();

        return view('modul.properti.karyawan.index', compact('projects'));
    }

    public function client()
    {
        // Ambil project milik client yang login
        $projects = Project::where('client_id', Auth::id())->latest()->take(10)->get();

        return view('modul.properti.client.index', compact('projects'));
    }

    public function dokumen()
{
    $role = auth()->user()->role; // Ambil role user yang login
    
    // Jika role-nya karyawan, arahkan ke folder karyawan. Jika bukan, ke folder client.
    if ($role === 'karyawan') {
        return view('modul.properti.karyawan.dokumen');
    } else {
        return view('modul.properti.client.dokumen');
    }
}

public function fisik()
{
    $role = auth()->user()->role;
    if ($role === 'karyawan') {
        $projects = Project::latest()->take(10)->get();
        return view('modul.properti.karyawan.fisik', compact('projects'));
    } else {
        $projects = Project::where('client_id', Auth::id())->latest()->take(10)->get();
        return view('modul.properti.client.fisik', compact('projects'));
    }
}

public function penilaian()
{
    $role = auth()->user()->role;
    if ($role === 'karyawan') {
        return view('modul.properti.karyawan.penilaian');
    } else {
        return view('modul.properti.client.penilaian');
    }
}
}