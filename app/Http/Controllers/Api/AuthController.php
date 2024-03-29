<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Password;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('jwt', ['except' => ['login', 'register', 'resend', 'category', 'organization', 'forgot_password']]);
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
            ], [
                'email.required' => 'Email harus diisi.',
                'email.email' => 'Email harus berupa alamat email yang valid.',
                'password.required' => 'Password harus diisi.',
                'password.string' => 'Password harus bertipe string.',
                'password.min' => 'Password tidak boleh kurang dari :min karakter.',
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            if (!$token = Auth::guard('api')->attempt($validator->validated())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 200);
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
                'name.required' => 'Nama harus diisi.',
                'name.string' => 'Nama harus bertipe string.',
                'name.between' => 'Nama harus minimal :min karakter dan maksimal :max karakter.',
                'email.required' => 'Email harus diisi.',
                'email.string' => 'Email harus bertipe string.',
                'email.email' => 'Email harus berupa alamat email yang valid.',
                'email.max' => 'Email tidak boleh melebihi :max karakter.',
                'email.unique' => 'Email sudah digunakan.',
                'password.required' => 'Password harus diisi.',
                'password.string' => 'Password harus bertipe string.',
                'password.confirmed' => 'Password konfirmasi tidak cocok.',
                'password.min' => 'Password tidak boleh kurang dari :min karakter.',
                'role.required' => 'Role harus diisi.',
                'role.in' => 'Role harus valid.',
                'organization.required' => 'Instansi harus diisi.',
                'organization.exists' => 'Instansi harus valid.',
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            $account = Account::create(array_merge(
                        $validator->validated(),
                        ['password' => bcrypt($request->password)],
                        ['organization_id' => $request->organization]
            ));
            $account->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Registrasi akun berhasil, silahkan check email.',
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
            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil melakukan logout.'
            ], 200);
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
        ], 200);
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
                'message' => 'Sukses mendapatkan data account.',
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
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'data' => $account
        ], 200);
    }

    /**
     * Resend verify email.
     */
    public function resend($account_id) {
        $account = Account::find($account_id);
        if ($account->email_verified_at != null) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terverifikasi.'
            ], 200);
        }

        $account->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Email verifikasi berhasil dikirim.'
        ], 200);
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
                'message' => 'Sukses mendapatkan data kategori.',
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
                'message' => 'Sukses mendapatkan data instansi.',
                'data' => $organizaions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Forgot password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgot_password(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
            ], [
                'email.required' => 'Email harus diisi.',
                'email.string' => 'Email harus bertipe string.',
                'email.email' => 'Email harus berupa alamat email yang valid.',
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            $user = Account::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan.'
                ], 200);
            }

            $response = Password::broker('accounts')->sendResetLink(
                $request->only('email')
            );

            if ($response === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email reset password berhasil dikirim.',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => trans($response)
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_profile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'account_id' => 'required',
                'name' => 'required|string|between:2,100',
                'avatar' => 'nullable|sometimes|image|mimes:jpeg,png,jpg|max:2000',
                'organization' => 'required|exists:organizations,id',
                'password' => 'nullable|sometimes|string|min:6',
            ], [
                'account.required' => 'Akun harus diisi',
                'name.required' => 'Nama harus diisi.',
                'name.string' => 'Nama harus bertipe string.',
                'name.between' => 'Nama harus minimal :min karakter dan maksimal :max karakter.',
                'avatar.image' => 'Avatar harus berupa gambar',
                'avatar.mimes' => 'Avatar harus berkestensi :mimes.',
                'avatar.max' => 'Avatar tidak boleh melebihi ukuran :max kb.',
                'organization.required' => 'Instansi harus diisi.',
                'organization.exists' => 'Instansi harus valid.',
                'password.string' => 'Password harus bertipe string.',
                'password.min' => 'Password tidak boleh kurang dari :min karakter.'
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            $account = Account::find($request->account_id);
            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan.'
                ], 200);
            }

            $avatar = $account->avatar;
            $password = $account->password;

            if ($request->has('avatar') && $request->avatar != null) {
                $imageName = time().'.'.$request->avatar->extension();
                $request->avatar->move(public_path('uploads/avatar'), $imageName);
                $avatar = 'uploads/avatar' . '/' . $imageName;

                $file_path = public_path() . '/' . $account->avatar;
                if (File::exists($file_path) && ($account->avatar != '' || $account->avatar != null)) {
                    unlink($file_path);
                }
            }

            if ($request->has('password')) {
                $password = bcrypt($request->password);
            }

            $account->update([
                'name' => $request->name,
                'organization_id' => $request->organization,
                'password' => $password,
                'avatar' => $avatar
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diupdate.',
                'data' => $account
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
