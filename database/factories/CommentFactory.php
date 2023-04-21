<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now    = Carbon::now()->format('Y-m-d H:i:s');

        return [
            'user_id'               => null,
            'parent_id'             => null,
            'body'                      => null,
            'commentable_id'       => null,
            'commentable_type'   => null,
            'created_at'            => $now,
            'updated_at'            => $now,
        ];
    }
}
