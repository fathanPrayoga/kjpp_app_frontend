<?php

namespace App\Http\Controllers;

use App\Models\ProjectDocument;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class ProjectDocumentController extends Controller
{
    public function __construct()
    {
        // Only karyawan can access these routes
        $this->middleware(['auth', 'verified']);
    }

    // show documents for karyawan view (grouped by project)
    public function index()
    {
        $projects = Project::with('documents')->get();
        return view('modul.properti.karyawan.dokumen', compact('projects'));
    }

    // download a document file
    public function download(ProjectDocument $document)
    {
        $path = $document->file_path;
        $full = public_path($path);
        if (!file_exists($full)) {
            abort(404, 'File not found');
        }
        return response()->download($full, $document->nama_file);
    }

    // download all documents for a project as ZIP
    public function downloadAll(Project $project)
    {
        $documents = $project->documents;
        if ($documents->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada dokumen untuk diunduh');
        }

        $zipFile = storage_path('app/temp_' . $project->id . '_' . time() . '.zip');
        $zip = new ZipArchive();
        
        if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
            foreach ($documents as $doc) {
                $filePath = public_path($doc->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();

            return response()->download($zipFile, "Project_{$project->nama_project}.zip")->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Gagal membuat file ZIP');
    }

    // verify document (approve/reject)
    public function verify(Request $request, ProjectDocument $document)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        $document->status = $request->input('action') === 'approve' ? 'verified' : 'rejected';
        $document->notes = $request->input('notes');
        $document->verified_by = Auth::id();
        $document->verified_at = now();
        $document->save();

        return redirect()->back()->with('success', 'Dokumen berhasil diverifikasi');
    }

    // verify project (set all documents to verified)
    public function verifyProject(Request $request, Project $project)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        $status = $request->input('action') === 'approve' ? 'verified' : 'rejected';
        $notes = $request->input('notes');

        // update all documents for this project
        $project->documents()->update([
            'status' => $status,
            'notes' => $notes,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Project berhasil diverifikasi');
    }

    // verify multiple projects (batch) — accepts project_ids[] and action
    public function verifyBatch(Request $request)
    {
        $request->validate([
            'project_ids' => 'required|array|min:1',
            'project_ids.*' => 'integer|exists:projects,id',
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        $status = $request->input('action') === 'approve' ? 'verified' : 'rejected';
        $notes = $request->input('notes');

        $projectIds = $request->input('project_ids');

        // Update documents for each selected project
        foreach ($projectIds as $pid) {
            $project = Project::find($pid);
            if (!$project) continue;
            $project->documents()->update([
                'status' => $status,
                'notes' => $notes,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Selected projects updated.');
    }
}
