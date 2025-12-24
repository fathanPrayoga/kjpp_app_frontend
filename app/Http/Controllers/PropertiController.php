<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Nilai;
use Illuminate\Support\Facades\Auth;

class PropertiController extends Controller
{
    // ===== FUNGSI INDEX BERDASARKAN ROLE =====

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

    // ===== MODULE DOKUMEN =====

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

    // ===== MODULE FISIK =====

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

    // ===== MODULE PENILAIAN =====

    public function penilaian()
    {
        $role = auth()->user()->role;

        if ($role === 'karyawan') {
            $projects = Project::with('client')->latest()->get();
            return view('modul.properti.karyawan.penilaian', compact('projects'));
        } else {
            $projects = Project::where('client_id', auth()->id())->latest()->get();
            return view('modul.properti.client.penilaian', compact('projects'));
        }
    }

    // ===== LOGIKA PENILAIAN (JSON) =====

    public function getNilai($projectId)
    {
        $nilai = Nilai::where('project_id', $projectId)->first();

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

    public function saveNilai(Request $request, $projectId)
    {
        $nilai = Nilai::where('project_id', $projectId)->first();
        $selectedStatus = $request->input('status_penilaian');

        if ($nilai && $nilai->status_penilaian?->value === 'sudah dinilai') {
            return response()->json([
                'success' => false,
                'error' => 'Nilai tidak dapat diubah karena status sudah "Sudah Dinilai"'
            ], 403);
        }

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

        $hasAnyValue = false;
        foreach ($nilaiFields as $field) {
            if ($request->input($field)) {
                $hasAnyValue = true;
                break;
            }
        }

        if (!$hasAnyValue) {
            $finalStatus = 'belum dinilai';
        } else if ($selectedStatus === 'sudah dinilai') {
            $finalStatus = 'sudah dinilai';
        } else {
            $finalStatus = 'sedang dinilai';
        }

        if (!$nilai) {
            $nilai = new Nilai();
            $nilai->project_id = $projectId;
        }

        $nilai->status_penilaian = $finalStatus;
        foreach ($nilaiFields as $field) {
            $nilai->$field = $request->input($field);
        }

        $nilai->save();
        return response()->json(['success' => true]);
    }

    // ===== MODULE LAPORAN =====
    public function laporanProject()
    {
        $projects = Project::with('client')->latest()->get();
        return view('modul.properti.laporan.project', compact('projects'));
    }

    public function getProject($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }

    public function uploadLaporan(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'asal_instansi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'file' => 'nullable|mimes:pdf|max:2048',
        ]);

        $project = Project::findOrFail($request->project_id);

        // SIMPAN TEXT
        $project->asal_instansi = $request->asal_instansi;
        $project->tanggal_mulai = $request->tanggal_mulai;

        // FILE
        if ($request->hasFile('file')) {
            if ($project->dokumen) {
                Storage::disk('public')->delete($project->dokumen);
            }

            $project->dokumen = $request->file('file')
                ->store('laporan_project', 'public');
        }

        $project->status = 'Selesai';
        $project->save();

        return back()->with('success', 'Laporan berhasil diperbarui');
    }

    public function laporanTahunan()
    {
        $years = Project::whereNotNull('tanggal_mulai')
            ->selectRaw('YEAR(tanggal_mulai) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get();

        return view('modul.properti.laporan.tahunan', compact('years'));
    }

    public function getTahunanByYear($year)
    {
        $projects = Project::whereYear('tanggal_mulai', $year)
            ->whereNotNull('dokumen')
            ->get();

        return response()->json(['tahun' => $year, 'files' => $projects]);
    }

    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);
        if ($project->dokumen) {
            Storage::disk('public')->delete($project->dokumen);
        }
        $project->delete();

        return back()->with('success', 'Project dan laporan berhasil dihapus');
    }
    public function downloadZipTahunan($year)
{
    $projects = Project::whereYear('tanggal_mulai', $year)
        ->whereNotNull('dokumen')
        ->get();

    if ($projects->isEmpty()) {
        return back()->with('error', 'Tidak ada dokumen untuk diunduh pada tahun ini.');
    }

    $zipFileName = 'Laporan_Tahunan_' . $year . '.zip';
    $zipFilePath = public_path($zipFileName); // Gunakan public_path
    $zip = new \ZipArchive;

    if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
        foreach ($projects as $project) {
            $filePath = storage_path('app/public/' . $project->dokumen);

            if (file_exists($filePath)) {
                // Gunakan nama project sebagai nama file di dalam ZIP
                $namaFileDalamZip = str_replace(' ', '_', $project->nama_project) . '.pdf';
                $zip->addFile($filePath, $namaFileDalamZip);
            }
        }
        $zip->close();
    }

    return response()->download($zipFilePath)->deleteFileAfterSend(true);
}
}