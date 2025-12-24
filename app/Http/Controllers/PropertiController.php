<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
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
        $projects = Project::where('client_id', Auth::id())->latest()->take(10)->get();
        return view('modul.properti.client.index', compact('projects'));
    }

    public function dokumen()
    {
        return auth()->user()->role === 'karyawan'
            ? view('modul.properti.karyawan.dokumen')
            : view('modul.properti.client.dokumen');
    }

    public function fisik()
    {
        return auth()->user()->role === 'karyawan'
            ? view('modul.properti.karyawan.fisik')
            : view('modul.properti.client.fisik');
    }

    public function penilaian()
    {
        return auth()->user()->role === 'karyawan'
            ? view('modul.properti.karyawan.penilaian')
            : view('modul.properti.client.penilaian');
    }

    // ===== LAPORAN =====

    // Menampilkan Daftar Project
    public function laporanProject()
    {
        $projects = Project::with('client')->latest()->get();
        return view('modul.properti.laporan.project', compact('projects'));
    }

    // Mengambil Data JSON untuk Modal Edit
    public function getProject($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }

    // Proses Unggah/Update Laporan
    public function uploadLaporan(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'asal_instansi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'file' => 'nullable|mimes:pdf|max:2048',
        ]);

        $project = Project::findOrFail($request->project_id);

        $project->asal_instansi = $request->asal_instansi;
        $project->tanggal_mulai = $request->tanggal_mulai;

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($project->dokumen) {
                Storage::disk('public')->delete($project->dokumen);
            }
            $path = $request->file('file')->store('laporan_project', 'public');
            $project->dokumen = $path;
        }

        $project->status = 'Selesai';
        $project->save();

        return back()->with('success', 'Laporan project berhasil diperbarui!');
    }

    // Menampilkan Halaman Laporan Tahunan
    public function laporanTahunan()
    {
        $years = Project::whereNotNull('tanggal_mulai')
            ->selectRaw('YEAR(tanggal_mulai) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get();

        return view('modul.properti.laporan.tahunan', compact('years'));
    }

    // Mengambil Data JSON Berdasarkan Tahun
    public function getTahunanByYear($year)
    {
        $projects = Project::whereYear('tanggal_mulai', $year)
            ->whereNotNull('dokumen')
            ->get();

        return response()->json([
            'tahun' => $year,
            'files' => $projects
        ]);
    }

    // Menghapus Project
    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);
        if ($project->dokumen) {
            Storage::disk('public')->delete($project->dokumen);
        }
        $project->delete();

        return back()->with('success', 'Project dan laporan berhasil dihapus');
    }
}
