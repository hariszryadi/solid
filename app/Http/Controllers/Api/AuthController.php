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
        $this->middleware('jwt', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
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
    }

    /**
     * Register a Account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:accounts',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|in:user,pic',
            'organization' => 'required|exists:organizations,id'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $account = Account::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)],
                    ['organization_id' => $request->organization]
                ));

        return response()->json([
            'message' => 'Registrasi akun berhasil',
            'account' => $account
        ], 201);
    }

    /**
     * Log the account out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Akun berhasil melakukan logout']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(Auth::guard('api')->refresh());
    }

    /**
     * Get the authenticated Account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountProfile() {
        $account = Auth::guard('api')->user();
        $account->load('organization');

        return response()->json($account);
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
            'account' => Auth::guard('api')->user($account)
        ]);
    }

    /**
     * Get categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function category()
    {
        $categories = Category::orderBy('id')->get();

        return response()->json($categories);
    }

    /**
     * Get Organizations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function organization()
    {
        $organizaions = Organization::orderBy('id')->get();

        return response()->json($organizaions);
    }
}
