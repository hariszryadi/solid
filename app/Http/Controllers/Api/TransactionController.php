<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class TransactionController extends Controller
{
    /**
     * Create a new TransactionController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('jwt');
    }

    /**
     * Store transaction waste.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'account' => 'required|exists:accounts,id',
                'weight' => 'required|numeric',
                'category' => 'required|exists:categories,id'
            ], [
                'account.required' => 'Akun harus diisi',
                'account.exists' => 'Akun harus valid',
                'weight.required' => 'Berat harus diisi',
                'weight.numeric' => 'Berat harus berupa angka',
                'category.required' => 'Kategori harus diisi',
                'category.exists' => 'Kategori harus valid',
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            $account = Account::find($request->account);
            $data = Transaction::create([
                'account_id' => $request->account,
                'weight' => $request->weight,
                'category_id' => $request->category,
                'organization_id' => $account->organization_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data sampah berhasil disimpan',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Report transaction waste by month.
     */
    public function report_monthly(Request $request)
    {
        try {
            $account = Auth::guard('api')->user();
            $categories = Category::orderBy('id')->get();

            $response = [];
            foreach ($categories as $key => $category) {
                $amount = Transaction::where('account_id', $account->id)->where('category_id', $category->id)->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('weight');
                array_push($response, ['category' => $category->name, 'amount' => $amount]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sukses mendapatkan data report bulanan',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Report transaction waste by date.
     */
    public function report_daily(Request $request)
    {
        try {
            $account = Auth::guard('api')->user();
            $categories = Category::orderBy('id')->get();

            $response = [];
            foreach ($categories as $key => $category) {
                $amount = Transaction::where('account_id', $account->id)->where('category_id', $category->id)->whereDate('created_at', $request->date)->sum('weight');
                array_push($response, ['category' => $category->name, 'amount' => $amount]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sukses mendapatkan data report harian',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
