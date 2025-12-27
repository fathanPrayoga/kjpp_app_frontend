<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alice = User::where('email', 'karyawan@test.com')->first();
        $bob = User::where('email', 'client@test.com')->first();
        $anto = User::where('email', 'pekerja@test.com')->first();

        if (! $alice || ! $bob || ! $anto) {
            return;
        }

        Message::factory()->create([
            'sender_id' => $alice->id,
            'recipient_id' => $bob->id,
            'body' => 'Assalammualaikum, apakah progres laporan sudah siap?',
            'is_read' => false,
        ]);

        Message::factory()->create([
            'sender_id' => $bob->id,
            'recipient_id' => $alice->id,
            'body' => 'Waalaikum salam, sedang diproses dan akan saya kirim sore ini.',
            'is_read' => true,
            'read_at' => now(),
        ]);

        Message::factory()->count(3)->create([
            'sender_id' => $alice->id,
            'recipient_id' => $anto->id,
        ]);
    }
}
