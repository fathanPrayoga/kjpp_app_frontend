<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function create()
    {
        return view('modul.properti.client.tambah');
    }

    public function store(Request $request)
    {
        $project = Project::create([
            'client_id'      => auth()->id(),
            'nama_project'   => $request->nama_project,
            'contract_date'  => $request->contract_date,
            'contact_person' => $request->contact_person,
            'deskripsi'      => $request->deskripsi,
            'status'         => 'pending',
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = Storage::disk('public')->putFile('documents', $file);

                $project->documents()->create([
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => 'storage/' . $path,
                ]);
            }
        }

        return redirect()->route('properti.dokumen')
            ->with('success', 'Dokumen berhasil ditambahkan');
    }

    public function edit(Project $project)
    {
        return view('modul.properti.karyawan.fisik_edit', compact('project'));
    }

    public function show(Project $project)
    {
        return view('modul.properti.karyawan.fisik_show', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'status'       => 'nullable|string|in:pending,proses,selesai',
        ]);

        $project->update($data);

        return redirect()->route('properti.karyawan')->with('success', 'Project updated');
    }
}
