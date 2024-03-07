<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Organization;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Folder views
     */
    protected $_view = 'account.';

    /**
     * Route index
     */
    protected $_route = 'account.index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->title = 'Akun';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::orderBy('id')->get();
        return view($this->_view.'index', ['title' => $this->title, 'accounts' => $accounts]);
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
        $account = Account::find($id);
        $organizations = Organization::orderBy('id')->get();
        return view($this->_view.'edit', ['title' => $this->title, 'account' => $account, 'organizations' => $organizations]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:accounts,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
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
            'password.confirmed' => 'Password konfirmasi tidak cocok',
            'password.min' => 'Password tidak boleh kurang dari :min karakter',
            'role.required' => 'Role harus diisi',
            'role.in' => 'Role harus valid',
            'organization.required' => 'Instansi harus diisi',
            'organization.exists' => 'Instansi harus valid',
        ]);

        $password = '';
        $account = Account::find($id);

        if ($request->has('password')) {
            $password = bcrypt($request->password);
        } else {
            $password = $account->password;
        }

        $account->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => $request->role,
            'organization_id' => $request->organization
        ]);

        return redirect()->route($this->_route)->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}
