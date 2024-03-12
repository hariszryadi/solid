<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class FrontEndController extends Controller
{
    /**
     * URL Api.
     */
    protected $api_url;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->api_url = env('API_URL');
    }

    /**
     * Index page.
     *
     * @return view
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Login page.
     *
     * @return view
     */
    public function login()
    {
        return view('frontend.login');
    }

    /**
     * Register page.
     *
     * @return view
     */
    public function register()
    {
        $response = Http::get($this->api_url . '/organization')->json();
        return view('frontend.register', ['response' => $response]);
    }

    /**
     * Verify email.
     *
     * @return view
     */
    public function verify($account_id, Request $request) {
        if (!$request->hasValidSignature()) {
            return view('email.verify-email-failed', ['message' => 'Email gagal diverifikasi.', 'sub_message' => 'Silahkan hubungi Administrator Aplikasi SOLID']);
        }

        $account = Account::findOrFail($account_id);

        if (!$account->hasVerifiedEmail()) {
            $account->markEmailAsVerified();
        }

        return view('email.verify-email-success', ['message' => 'Email berhasil diverifikasi.', 'sub_message' => 'Silahkan buka kembail Aplikasi SOLID']);
    }

    /**
     * Verify success.
     *
     * @return view
     */
    public function verify_success()
    {
        return view('email.verify-email-success');
    }

    /**
     * Verify success.
     *
     * @return view
     */
    public function verify_failed()
    {
        return view('email.verify-email-failed');
    }

    /**
     * Reset password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset_password(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:accounts',
            'password' => 'required|string|min:6|confirmed'
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email harus berupa alamat email yang valid',
            'email.exists' => 'Email harus valid',
            'password.required' => 'Password harus diisi',
            'password.string' => 'Password harus bertipe string',
            'password.min' => 'Password tidak boleh kurang dari :min karakter',
            'password.confirmed' => 'Password konfirmasi tidak cocok',
        ]);

        $status = Password::broker('accounts')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Account $account, string $password) {
                $account->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $account->save();

                event(new PasswordReset($account));
            }
        );

        return $status === Password::PASSWORD_RESET
                ? redirect('/user/login')->with('success', 'Password berhasil diubah!')
                : back()->withErrors(['email' => [__($status)]]);
    }
}
