<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Book::class;

    public function definition(): array
    {
        $num = fake()->unique()->numberBetween(1, 999999);

        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'isbn' => '978'.str_pad((string) fake()->unique()->numberBetween(0, 999999999), 9, '0', STR_PAD_LEFT).'1',
            'published_date' => fake()->date(),
            'description' => fake()->paragraph(),
            'image_url' => "https://placehold.co/200x300/e2e8f0/475569?text={$num}",
        ];
    }
}
