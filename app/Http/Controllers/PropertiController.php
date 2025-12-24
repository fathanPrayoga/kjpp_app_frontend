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

    // public function fisik()
    // {
    //     return view(
    //         auth()->user()->role === 'karyawan'
    //             ? 'modul.properti.karyawan.fisik'
    //             : 'modul.properti.client.fisik'
    //     );
    // }

    // public function penilaian()
    // {
    //     return view(
    //         auth()->user()->role === 'karyawan'
    //     // Ambil semua project dari client untuk karyawan lihat
    //     $projects = Project::latest()->take(10)->get();
    //             ? 'modul.properti.karyawan.penilaian', compact('projects')
    //             // Ambil project milik client yang login
    //     $projects = Project::where('client_id', Auth::id())->latest()->take(10)->get();
    //     : 'modul.properti.client.penilaian'
    //     , compact('projects'));
    // }

    // 1. MODULE FISIK
    public function fisik()
    {
        $role = auth()->user()->role;

        if ($role === 'karyawan') {
            // Karyawan sees ALL projects
            // We use 'with' to display the client name in the admin table
            $projects = Project::with('client')->latest()->get();

            return view('modul.properti.karyawan.fisik', compact('projects'));

        } else {
            // Client sees ONLY their own projects
            $projects = Project::where('client_id', auth()->id())
                ->latest()
                ->get();

            // Note: Make sure this view file exists!
            return view('modul.properti.client.fisik', compact('projects'));
        }
    }

    // 2. MODULE PENILAIAN
    public function penilaian()
    {
        $role = auth()->user()->role;

        if ($role === 'karyawan') {
            // Karyawan sees ALL projects for valuation
            $projects = Project::with('client')->latest()->get();

            return view('modul.properti.karyawan.penilaian', compact('projects'));

        } else {
            // Client sees ONLY their own valuation status
            $projects = Project::where('client_id', auth()->id())
                ->latest()
                ->get();

            // Note: Make sure this view file exists!
            return view('modul.properti.client.penilaian', compact('projects'));
        }
    }

    // Get nilai data for a specific project
    public function getNilai($projectId)
    {
        $nilai = \App\Models\Nilai::where('project_id', $projectId)->first();

        if ($nilai) {
            return response()->json([
                'exists' => true,
                'id' => $nilai->id,
                'status_penilaian' => $nilai->status_penilaian?->value,
                'nilai_pasar_final' => $nilai->nilai_pasar_final,
                'nilai_tanah' => $nilai->nilai_tanah,
                'nilai_indikasi_dari_pasar' => $nilai->nilai_indikasi_dari_pasar,
                'nilai_indikasi_dari_biaya' => $nilai->nilai_indikasi_dari_biaya,
                'nilai_likuidasi' => $nilai->nilai_likuidasi,
                'nilai_bangunan' => $nilai->nilai_bangunan,
                'nilai_per_m2_tanah' => $nilai->nilai_per_m2_tanah,
                'nilai_per_m2_bangunan' => $nilai->nilai_per_m2_bangunan,
            ]);
        }

        return response()->json(['exists' => false]);
    }

    // Save atau update nilai data
    public function saveNilai(\Illuminate\Http\Request $request, $projectId)
    {
        $nilai = \App\Models\Nilai::where('project_id', $projectId)->first();
        $selectedStatus = $request->input('status_penilaian');

        // Check if trying to edit finalized nilai (sudah dinilai)
        if ($nilai && $nilai->status_penilaian?->value === 'sudah dinilai') {
            return response()->json([
                'success' => false,
                'error' => 'Nilai tidak dapat diubah karena status sudah "Sudah Dinilai"'
            ], 403);
        }

        // Get all nilai fields
        $nilaiFields = [
            'nilai_pasar_final',
            'nilai_tanah',
            'nilai_indikasi_dari_pasar',
            'nilai_indikasi_dari_biaya',
            'nilai_likuidasi',
            'nilai_bangunan',
            'nilai_per_m2_tanah',
            'nilai_per_m2_bangunan'
        ];

        // Check if ANY nilai field has a value
        $hasAnyValue = false;
        foreach ($nilaiFields as $field) {
            if ($request->input($field)) {
                $hasAnyValue = true;
                break;
            }
        }

        // Determine final status based on values and selection
        if (!$hasAnyValue) {
            // No values filled - stay belum dinilai
            $finalStatus = 'belum dinilai';
        } else if ($selectedStatus === 'sudah dinilai') {
            // Any value filled AND user selected sudah dinilai - lock it
            $finalStatus = 'sudah dinilai';
        } else {
            // Any value filled AND user selected sedang dinilai - save as sedang dinilai
            $finalStatus = 'sedang dinilai';
        }

        if (!$nilai) {
            $nilai = new \App\Models\Nilai();
            $nilai->project_id = $projectId;
        }

        $nilai->status_penilaian = $finalStatus;
        foreach ($nilaiFields as $field) {
            $nilai->$field = $request->input($field);
        }

        $nilai->save();

        return response()->json(['success' => true]);
    }
}
