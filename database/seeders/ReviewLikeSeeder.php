<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $reviews = Review::all();

        if ($users->isEmpty() || $reviews->isEmpty()) {
            return;
        }

        foreach ($reviews as $review) {
            $candidateUsers = $users->where('id', '!=', $review->user_id)->values();
            if ($candidateUsers->isEmpty()) {
                continue;
            }

            $likeCount = min($candidateUsers->count(), rand(0, 3));
            if ($likeCount === 0) {
                continue;
            }

            $likedUserIds = $candidateUsers->shuffle()->take($likeCount)->pluck('id')->all();
            $review->likedByUsers()->syncWithoutDetaching($likedUserIds);
        }
    }
}
