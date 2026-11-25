<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * 書籍を更新（編集画面へ遷移）できるか判定する。
     *
     * @param  User  $user  ログインユーザー
     * @param  Book  $book  対象書籍モデル
     * @return bool 作成者本人の場合 true
     */
    public function update(User $user, Book $book): bool
    {
        return (int) $user->id === (int) $book->user_id;
    }

    /**
     * 書籍を削除できるか判定する。
     *
     * @param  User  $user  ログインユーザー
     * @param  Book  $book  対象書籍モデル
     * @return bool 作成者本人の場合 true
     */
    public function delete(User $user, Book $book): bool
    {
        return (int) $user->id === (int) $book->user_id;
    }
}
