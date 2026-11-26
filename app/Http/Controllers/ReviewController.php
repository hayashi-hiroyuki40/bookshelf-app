<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * 自分のレビューの編集画面を表示する。
     *
     * @param  Review  $review  編集対象レビューモデル
     * @return View レビュー編集画面
     */
    public function edit(Review $review): View
    {
        $this->authorize('update', $review);

        return view('reviews.edit', compact('review'));
    }

    /**
     * 書籍にレビューを投稿し、同じ詳細画面へ戻る。
     *
     * @param  Book  $book  投稿先の書籍モデル
     * @return RedirectResponse 書籍詳細画面へのリダイレクト
     */
    public function store(ReviewRequest $request, Book $book): RedirectResponse
    {
        $validated = $request->validated();

        $book->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'レビューを投稿しました。');
    }

    /**
     * 書籍にレビューを投稿し、同じ詳細画面へ戻る。
     *
     * @param  Review  $review  更新対象のレビューモデル
     * @return RedirectResponse 書籍詳細画面へのリダイレクト
     */
    public function update(ReviewRequest $request, Review $review): RedirectResponse
    {
        $this->authorize('update', $review);

        $validated = $request->validated();

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()
            ->route('books.show', $review->book_id)
            ->with('success', 'レビューを更新しました。');
    }

    /**
     * 自分のレビューと関連いいねを削除し、書籍詳細画面へ戻る。
     *
     * @param  Review  $review  削除対象レビューモデル
     * @return RedirectResponse 書籍詳細画面へのリダイレクト
     */
    public function destroy(Review $review): RedirectResponse
    {
        $this->authorize('delete', $review);

        $bookId = $review->book_id;

        $review->delete();

        return redirect()
            ->route('books.show', $bookId)
            ->with('success', 'レビューを削除しました。');
    }
}
