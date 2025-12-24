<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the existing Asri Client from UserSeeder
        $client = User::where('email', 'client@test.com')->first();

        if (!$client) {
            // Fallback if client doesn't exist
            $client = User::where('role', 'client')->first();
        }

        if (!$client) {
            return; // No client available
        }

        // Create 2 sample projects
        $projectsData = [
            [
                'nama_project' => 'Penilaian Properti Kantor Pusat',
                'deskripsi' => 'Penilaian properti untuk kantor pusat di Jakarta Pusat dengan luas tanah 500 m2 dan bangunan 2000 m2',
                'status' => 'proses',
                'contract_date' => '2025-01-10',
                'contact_person' => 'Budi Santoso',
            ],
            [
                'nama_project' => 'Penilaian Ruko Komersial',
                'deskripsi' => 'Penilaian ruko komersial di area Sudirman dengan luas tanah 100 m2 dan bangunan 200 m2 per lantai',
                'status' => 'pending',
                'contract_date' => '2025-02-01',
                'contact_person' => 'Siti Rahmawati',
            ],
        ];

        foreach ($projectsData as $data) {
            Project::create([
                'client_id' => $client->id,
                'nama_project' => $data['nama_project'],
                'deskripsi' => $data['deskripsi'],
                'status' => $data['status'],
                'contract_date' => $data['contract_date'],
                'contact_person' => $data['contact_person'],
            ]);
        }
    }
}
