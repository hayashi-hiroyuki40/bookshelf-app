<?php

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Genre>
 */
class GenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Genre::class;

    public function definition(): array
    {
        $candidates = [
            '小説',
            'ビジネス',
            '技術書',
            '自己啓発',
            'エッセイ',
            '歴史',
            '科学',
            '芸術',
            '料理',
            '旅行',
        ];

        $name = fake()->optional(0.6)->randomElement($candidates)
            ?? ('ジャンル_'.fake()->unique()->numberBetween(1, 999999));

        return [
            'name' => $name,
        ];
    }
}
