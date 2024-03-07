<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;

class OrganizationController extends Controller
{
    /**
     * Folder views
     */
    protected $_view = 'organization.';

    /**
     * Route index
     */
    protected $_route = 'organization.index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->title = 'Instansi';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizations = Organization::orderBy('id')->get();
        return view($this->_view.'index', ['title' => $this->title, 'organizations' => $organizations]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view($this->_view.'create', ['title' => $this->title]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus bertipe string',
            'name.max' => 'Nama tidak boleh melebihi :max karakter',
            'address.string' => 'Alamat harus bertipe string',
            'address.max' => 'Alamat tidak boleh melebihi :max karakter',
        ]);

        Organization::create([
            'name' => $request->name,
            'address' => $request->address
        ]);

        return redirect()->route($this->_route)->with('success', 'Data berhasil disimpan!');


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
        $organization = Organization::find($id);
        return view($this->_view.'edit', ['title' => $this->title, 'organization' => $organization]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus bertipe string',
            'name.max' => 'Nama tidak boleh melebihi :max karakter',
            'address.string' => 'Alamat harus bertipe string',
            'address.max' => 'Alamat tidak boleh melebihi :max karakter',
        ]);

        $organization = Organization::find($id);
        $organization->update([
            'name' => $request->name,
            'address' => $request->address
        ]);

        return redirect()->route($this->_route)->with('success', 'Data berhasil diupdate!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);

    }
}
