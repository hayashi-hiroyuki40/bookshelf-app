<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * お気に入り一覧画面を表示する。
     *
     * @return View お気に入り一覧画面
     */
    public function index(): View|RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $books = $user->favoriteBooks()
            ->latest('favorites.created_at')
            ->paginate(10);

        if ($books->isEmpty() && $books->currentPage() > 1) {
            return redirect()->route('favorites.index', [
                'page' => max(1, $books->lastPage()),
            ]);
        }

        return view('favorites.index', compact('books'));
    }
}
