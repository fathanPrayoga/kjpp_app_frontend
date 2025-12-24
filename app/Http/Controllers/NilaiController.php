<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        $projects = Project::with(['client', 'nilai'])->latest()->get();

        return view('properti.penilaian', compact('projects'));
    }
}
