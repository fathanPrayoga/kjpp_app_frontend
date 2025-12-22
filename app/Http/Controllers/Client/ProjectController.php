<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        // SIMPAN PROJECT DULU (TANPA VALIDASI)
        $project = Project::create([
            'client_id'      => auth()->id(),
            'nama_project'   => $request->nama_project,
            'contract_date'  => $request->contract_date,
            'contact_person' => $request->contact_person,
            'deskripsi'      => $request->deskripsi,
            'status'         => 'pending',
        ]);

        // KALAU ADA FILE, SIMPAN
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {

                $path = Storage::disk('public')->putFile('documents', $file);

                $project->documents()->create([
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => 'storage/' . $path,
                ]);
            }
        }

        return redirect()
            ->route('properti.client')
            ->with('success', 'Dokumen berhasil dikirim');
    }
}