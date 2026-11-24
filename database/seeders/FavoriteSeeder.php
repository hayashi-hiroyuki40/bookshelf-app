<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::orderBy('id')->get();
        $books = Book::orderBy('id')->get();

        if ($users->isEmpty() || $books->isEmpty()) {
            return;
        }

        foreach ($users as $index => $user) {
            $count = min($books->count(), 3 + ($index % 3));
            $bookIds = $books->shuffle()->take($count)->pluck('id')->all();

            $user->favoriteBooks()->syncWithoutDetaching($bookIds);
        }
    }
}
