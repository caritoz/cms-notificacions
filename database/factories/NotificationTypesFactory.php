<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationTypes>
 */
class NotificationTypesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        return [
            'description'   => Str::random(10),
            'group'          => Str::random(5),
            'class'          => null,
            'created_at'        =>  $now,
            'updated_at'        =>  $now,
            'order'             =>  1
        ];
    }
}
