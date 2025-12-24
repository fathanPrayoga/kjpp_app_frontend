<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class PropertiController extends Controller
{
    public function karyawan()
    {
        $projects = Project::latest()->take(10)->get();
        return view('modul.properti.karyawan.index', compact('projects'));
    }

    public function client()
    {
        $projects = Project::with('documents')
            ->where('client_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        return view('modul.properti.client.index', compact('projects'));
    }

    public function dokumen()
    {
        $role = auth()->user()->role;

        if ($role === 'karyawan') {

            $projects = Project::latest()->get();
            return view('modul.properti.karyawan.dokumen', compact('projects'));

        } else {

            $projects = Project::with('documents')
                ->where('client_id', auth()->id())
                ->latest()
                ->get();

            return view('modul.properti.client.dokumen', compact('projects'));
        }
    }

    public function fisik()
    {
        $role = auth()->user()->role;

        if ($role === 'karyawan') {
            $projects = Project::latest()->take(10)->get();
            return view('modul.properti.karyawan.fisik', compact('projects'));
        }

        $projects = Project::with('documents')
            ->where('client_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('modul.properti.client.fisik', compact('projects'));
    }

    public function penilaian()
    {
        return view(
            auth()->user()->role === 'karyawan'
                ? 'modul.properti.karyawan.penilaian'
                : 'modul.properti.client.penilaian'
        );
    }
}
