<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected CreateNewUser $creator;

    /**
     * 依存するユーザー作成アクションを注入する。
     */
    public function __construct(CreateNewUser $creator)
    {
        $this->creator = $creator;
    }

    /**
     * 会員登録画面を表示する。
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * 会員登録を行い、認証メールを送信したうえでメール認証誘導画面へ遷移する。
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = $this->creator->create($request->validated());

        Auth::login($user);

        return redirect('/');
    }

    /**
     * 一般ユーザーのログインを実行する。
     *
     * @param  LoginRequest  $request  メールアドレス・パスワードを含むログインリクエスト
     * @return RedirectResponse トップ画面へのリダイレクト、または認証失敗時の差し戻し
     */
    public function doLogin(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect('/');
        }

        return redirect()->back()->withInput()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    /**
     * 一般ユーザーをログアウトする。
     *
     * @return RedirectResponse ログイン画面へのリダイレクト
     */
    public function doLogout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
