<?php

namespace App\Models;

use App\Enums\StatusPenilaian;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = [
        'project_id', 
        'status_penilaian',
        'nilai_pasar_final',
        'nilai_tanah',
        'nilai_indikasi_dari_pasar',
        'nilai_indikasi_dari_biaya',
        'nilai_likuidasi',
        'nilai_bangunan',
        'nilai_per_m2_tanah',
        'nilai_per_m2_bangunan'
    ];

    protected $casts = [
        'status_penilaian' => StatusPenilaian::class,
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
