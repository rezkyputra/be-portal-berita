<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categories::all();

        return response([
            "message" => "Category berhasil ditampilkan",
            "data" => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedata = $request->validate([
            'name' => 'required',
        ], [
            'required' => 'inputan :attribute harus diisi.'
        ]);

        Categories::create($validatedata);

        return response([
            "message" => "Category berhasil ditambahkan"
        ], 201);
                
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Categories::with("listNews")->find($id);

        if(!$category) {
            return response([
                "message" => "$id tidak ditemukan di DB"
            ], 404);
        }

        return response([
            "message" => "Detail Category berhasil ditampilkan",
            "data" =>$category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedata = $request->validate([
            'name' => 'required',
        ], [
            'required' => 'inputan :attribute harus diisi.'
        ]);

        Categories::where('id', $id)
            ->update($validatedata);

        return response([
            "message" => "Category berhasil diupdate"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Categories::find($id);

        if(!$category) {
            return response([
                "message" => "$id tidak ditemukan di DB"
            ], 404);
        }
 
        $category->delete();

        return response([
            "message" => "Category berhasil didelete"
        ], 200);
    }
}
