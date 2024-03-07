<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Folder views
     */
    protected $_view = 'transaction.';

    /**
     * Route index
     */
    protected $_route = 'transaction.index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->title = 'Transaksi';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with('account', 'category', 'organization')->orderBy('id')->get();
        return view($this->_view.'index', ['title' => $this->title, 'transactions' => $transactions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = Transaction::find($id);
        $accounts = Account::orderBy('id')->get();
        $categories = Category::orderBy('id')->get();
        return view($this->_view.'edit', ['title' => $this->title, 'transaction' => $transaction, 'accounts' => $accounts, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
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

        $account = Account::find($request->account);
        $transaction = Transaction::find($id);
        $transaction->update([
            'account_id' => $account->id,
            'weight' => $request->weight,
            'category_id' => $request->category,
            'organization_id' => $account->organization_id
        ]);

        return redirect()->route($this->_route)->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}
