<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
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
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $data = Transaction::create([
                'account_id' => $request->account,
                'weight' => $request->weight,
                'category_id' => $request->category
            ]);

            return response()->json([
                'message' => 'Data sampah berhasil disimpan',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e,
            ], 500);
        }
    }

    /**
     * Report transaction waste.
     */
    public function report(Request $request)
    {
        try {
            $categories = Category::orderBy('id')->get();

            $response = [];
            foreach ($categories as $key => $category) {
                $amount = Transaction::where('category_id', $category->id)->where('created_at', '>=', $request->start_date)->where('created_at', '<=', $request->end_date)->count();
                array_push($response, ['category' => $category->name, 'amount' => $amount]);
            }

            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e,
            ], 500);
        }
    }
}
