<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * レビューを更新（編集画面へ遷移）できるか判定する。
     *
     * @param  User  $user  ログインユーザー
     * @param  Review  $review  対象レビューモデル
     * @return bool 投稿者本人の場合 true
     */
    public function update(User $user, Review $review): bool
    {
        return (int) $user->id === (int) $review->user_id;
    }

    /**
     * レビューを削除できるか判定する。
     *
     * @param  User  $user  ログインユーザー
     * @param  Review  $review  対象レビューモデル
     * @return bool 投稿者本人の場合 true
     */
    public function delete(User $user, Review $review): bool
    {
        return (int) $user->id === (int) $review->user_id;
    }
}
