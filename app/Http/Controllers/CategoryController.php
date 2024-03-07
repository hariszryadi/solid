<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Folder views
     */
    protected $_view = 'category.';

    /**
     * Route index
     */
    protected $_route = 'category.index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->title = 'Kategori';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id')->get();
        return view($this->_view.'index', ['title' => $this->title, 'companies' => $categories]);

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
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus bertipe string',
            'name.max' => 'Nama tidak boleh melebihi :max karakter',
            'description.string' => 'Deskripsi harus bertipe string',
            'description.max' => 'Deskripsi tidak boleh melebihi :max karakter',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description
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
        $category = Category::find($id);
        return view($this->_view.'edit', ['title' => $this->title, 'category' => $category]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus bertipe string',
            'name.max' => 'Nama tidak boleh melebihi :max karakter',
            'description.string' => 'Deskripsi harus bertipe string',
            'description.max' => 'Deskripsi tidak boleh melebihi :max karakter',
        ]);

        $category = Category::find($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route($this->_route)->with('success', 'Data berhasil diupdate!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);

    }
}
