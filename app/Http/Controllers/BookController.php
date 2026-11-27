<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * 書籍一覧画面（トップ）を表示する。
     *
     * @return View 書籍一覧画面
     */
    public function index(): View
    {
        $books = Book::query()
            ->with('genres')
            ->latest()
            ->paginate(10);

        return view('books.index', compact('books'));
    }

    /**
     * 書籍詳細画面を表示する。
     *
     * @param  Book  $book  表示対象の書籍モデル
     * @return View 書籍詳細画面
     */
    public function show(Book $book): View
    {
        $book->load([
            'genres',
            'reviews' => function ($query) {
                $query->with(['user', 'likedByUsers'])->latest();
            },
        ]);

        return view('books.show', compact('book'));
    }

    /**
     * 書籍登録画面を表示する。
     *
     * @return View 書籍登録画面
     */
    public function create(): View
    {
        $genres = Genre::orderBy('id')->get();
        $bookGenreIds = [];

        return view('books.create', compact('genres', 'bookGenreIds'));
    }

    /**
     * 書籍とジャンル紐付けを保存し、書籍詳細画面へリダイレクトする。
     *
     * @param  StoreBookRequest  $request  検証済み書籍登録リクエスト
     * @return RedirectResponse 書籍詳細画面へのリダイレクト
     */
    public function store(StoreBookRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $book = DB::transaction(function () use ($validated) {
            $book = Book::create([
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'author' => $validated['author'],
                'isbn' => $validated['isbn'],
                'published_date' => $validated['published_date'],
                'description' => $validated['description'] ?? null,
                'image_url' => $validated['image_url'] ?? null,
            ]);

            $book->genres()->sync($validated['genres']);

            return $book;
        });

        return redirect()
            ->route('books.show', $book)
            ->with('success', '書籍を登録しました。');
    }

    /**
     * 自分が登録した書籍の編集画面を表示する。
     *
     * @param  Book  $book  編集対象の書籍モデル
     * @return View 書籍編集画面
     */
    public function edit(Book $book): View
    {
        $this->authorize('update', $book);
        $book->load('genres');
        $genres = Genre::query()->orderBy('id')->get();

        return view('books.edit', compact('book', 'genres'));
    }

    /**
     * 自分が登録した書籍とジャンル紐付けを更新し、書籍詳細画面へリダイレクトする。
     *
     * @param  UpdateBookRequest  $request  検証済み書籍更新リクエスト
     * @param  Book  $book  更新対象の書籍モデル
     * @return RedirectResponse 書籍詳細画面へのリダイレクト
     */
    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $this->authorize('update', $book);

        $validated = $request->validated();

        DB::transaction(function () use ($book, $validated) {
            $book->update([
                'title' => $validated['title'],
                'author' => $validated['author'],
                'isbn' => $validated['isbn'],
                'published_date' => $validated['published_date'],
                'description' => $validated['description'] ?? null,
                'image_url' => $validated['image_url'] ?? null,
            ]);

            $book->genres()->sync($validated['genres']);
        });

        return redirect()
            ->route('books.show', $book)
            ->with('success', '書籍情報を更新しました。');
    }

    /**
     * 自分が登録した書籍と関連データを削除し、トップ画面へ遷移する。
     *
     * @param  Book  $book  削除対象の書籍モデル
     * @return RedirectResponse トップ画面へのリダイレクト
     */
    public function destroy(Book $book): RedirectResponse
    {
        $this->authorize('delete', $book);

        $book->delete();

        return redirect('/')->with('success', '書籍を削除しました。');
    }

    /**
     * お気に入り状態をトグル（追加/解除）する。
     *
     * @param  Book  $book  対象書籍モデル
     * @return RedirectResponse 書籍詳細画面へのリダイレクト
     */
    public function favoriteToggle(Book $book): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->favoriteBooks()->toggle($book->id);

        return back();
    }

    /**
     * レビューへのいいね状態をトグル（追加/解除）する。
     *
     * @param  Review  $review  対象レビューモデル
     * @return RedirectResponse 書籍詳細画面へのリダイレクト
     */
    public function likedToggle(Review $review): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->likedReviews()->toggle($review->id);

        return redirect()->route('books.show', $review->book_id);
    }
}
