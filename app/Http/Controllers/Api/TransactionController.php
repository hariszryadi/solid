<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
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
    }
}