<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'sender_id' => User::factory(),
            'recipient_id' => User::factory(),
            'body' => $this->faker->sentence(),
            'attachment_path' => null,
            'is_read' => $this->faker->boolean(20),
            'read_at' => null,
        ];
    }
}
