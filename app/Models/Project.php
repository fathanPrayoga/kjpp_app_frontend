<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Project extends Model
{
    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = ['client_id', 'nama_project', 'deskripsi', 'status'];

    // Relasi: Project ini dimiliki oleh Client
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}