<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('jwt', ['except' => ['login', 'register', 'verify', 'resend', 'category', 'organization']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if (!$token = Auth::guard('api')->attempt($validator->validated())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->createNewToken($token);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register a Account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:accounts',
                'password' => 'required|string|confirmed|min:6',
                'role' => 'required|in:user,pic',
                'organization' => 'required|exists:organizations,id'
            ], [
                'name.required' => 'Nama harus diisi',
                'name.string' => 'Nama harus bertipe string',
                'name.between' => 'Nama harus minimal :min karakter dan maksimal :max karakter',
                'email.required' => 'Email harus diisi',
                'email.string' => 'Email harus bertipe string',
                'email.email' => 'Email harus berupa alamat email yang valid',
                'email.max' => 'Email tidak boleh melebihi :max karakter',
                'email.unique' => 'Email sudah digunakan',
                'password.required' => 'Password harus diisi',
                'password.string' => 'Password harus bertipe string',
                'password.confirmed' => 'Password konfirmasi tidak cocok',
                'password.min' => 'Password tidak boleh kurang dari :min karakter',
                'role.required' => 'Role harus diisi',
                'role.in' => 'Role harus valid',
                'organization.required' => 'Instansi harus diisi',
                'organization.exists' => 'Instansi harus valid',
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $account = Account::create(array_merge(
                        $validator->validated(),
                        ['password' => bcrypt($request->password)],
                        ['organization_id' => $request->organization]
            ));
            $account->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Registrasi akun berhasil, silahkan check email',
                'data' => $account
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Log the account out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        try {
            Auth::guard('api')->logout();
            return response()->json(['message' => 'Akun berhasil melakukan logout'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        // return $this->createNewToken(Auth::guard('api')->refresh());

        $account = Auth::guard('api')->user();
        $account->load('organization');

        return response()->json([
            'token' => Auth::guard('api')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'data' => $account
        ]);
    }

    /**
     * Get the authenticated Account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountProfile() {
        try {
            $account = Auth::guard('api')->user();
            $account->load('organization');

            return response()->json([
                'success' => true,
                'message' => 'Sukses mendapatkan data account',
                'data' => $account
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        $account = Auth::guard('api')->user();
        $account->load('organization');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'data' => $account
        ], 200);
    }

    /**
     * Verify email.
     */
    public function verify($account_id, Request $request) {
        if (!$request->hasValidSignature()) {
            // return response()->json(['message' => 'Invalid/Expired url provided.'], 401);
            return view('email.verify-email-failed', ['message' => 'Email gagal diverifikasi.', 'sub_message' => 'Silahkan hubungi Administrator Aplikasi SOLID']);
        }

        $account = Account::findOrFail($account_id);

        if (!$account->hasVerifiedEmail()) {
            $account->markEmailAsVerified();
        }

        // return response()->json(['message' => 'Email berhasil diverifikasi.']);
        return view('email.verify-email-success', ['message' => 'Email berhasil diverifikasi.', 'sub_message' => 'Silahkan buka kembail Aplikasi SOLID']);
    }

    /**
     * Resend verify email.
     */
    public function resend($account_id) {
        $account = Account::find($account_id);
        if ($account->email_verified_at != null) {
            return response()->json(['message' => 'Email sudah terverifikasi.'], 400);
        }

        $account->sendEmailVerificationNotification();

        return response()->json(['message' => 'Email verifikasi berhasil dikirim']);
    }

    /**
     * Get categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function category()
    {
        try {
            $categories = Category::orderBy('id')->get();

            return response()->json([
                'success' => true,
                'message' => 'Sukses mendapatkan data kategori',
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Organizations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function organization()
    {
        try {
            $organizaions = Organization::orderBy('id')->get();

            return response()->json([
                'success' => true,
                'message' => 'Sukses mendapatkan data instansi',
                'data' => $organizaions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
