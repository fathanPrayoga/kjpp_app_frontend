<?php

namespace Database\Seeders;

use App\Models\Nilai;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        $nilaiData = [
            [
                'status_penilaian' => 'sudah dinilai',
                'nilai_pasar_final' => 2500000000,
                'nilai_tanah' => 1500000000,
                'nilai_indikasi_dari_pasar' => 2400000000,
                'nilai_indikasi_dari_biaya' => 2300000000,
                'nilai_likuidasi' => 2200000000,
                'nilai_bangunan' => 1000000000,
                'nilai_per_m2_tanah' => 3000000,
                'nilai_per_m2_bangunan' => 500000,
            ],
            [
                'status_penilaian' => 'belum dinilai',
                'nilai_pasar_final' => null,
                'nilai_tanah' => null,
                'nilai_indikasi_dari_pasar' => null,
                'nilai_indikasi_dari_biaya' => null,
                'nilai_likuidasi' => null,
                'nilai_bangunan' => null,
                'nilai_per_m2_tanah' => null,
                'nilai_per_m2_bangunan' => null,
            ],
        ];

        foreach ($projects as $index => $project) {
            if (isset($nilaiData[$index])) {
                Nilai::create([
                    'project_id' => $project->id,
                    ...$nilaiData[$index],
                ]);
            }
        }
    }
}
