<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $books = Book::orderBy('id')->get();

        if ($users->isEmpty() || $books->isEmpty()) {
            return;
        }

        $countsPerBook = [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2];

        $comments = [
            5 => 'とても面白く、最後まで一気に読み進められました。学びも多く大満足です。',
            4 => '構成が分かりやすく、読みやすい内容でした。読み返したいところも多いです。',
            3 => '基本的な内容が整理されており、テーマの全体像が掴みやすかったです。',
        ];

        $userIndex = 0;
        $ratingIndex = 0;
        $ratings = [5, 4, 3, 5, 4];

        foreach ($books as $index => $book) {
            $count = $countsPerBook[$index] ?? 2;

            for ($i = 0; $i < $count; $i++) {
                $user = $users[$userIndex % $users->count()];
                $rating = $ratings[$ratingIndex % count($ratings)];

                Review::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'rating' => $rating,
                    'comment' => $comments[$rating],
                ]);

                $userIndex++;
                $ratingIndex++;
            }
        }
    }
}
