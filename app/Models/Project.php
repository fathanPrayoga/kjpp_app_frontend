<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProjectDocument;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'nama_project',
        'contract_date',
        'contact_person',
        'deskripsi',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }
}
