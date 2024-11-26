<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = News::query();

        if($request->has("search")){
            $searching = $request->input("search");
            $query->where('title', "LIKE", "%$searching%");
        }

        $perPage = $request->input('per_page', 3);

        $news = $query->paginate($perPage);

        return response([
            "message" => "News berhasil ditampilkan",
            "data" => $news
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedata = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'required' => 'inputan :attribute harus diisi.',
            'exists' => 'category_id yang diinput tidak ditemukan',
            'image' => 'file harus berupa gambar',
            'mimes' => "format file hanya boleh jpg,jpeg atau png",
            'max' => 'size file tidak boleh lebih dari 2 mb'
        ]);

        if($request->has('image_url')){
            $uploadedFileUrl = cloudinary()->upload($request->file('image_url')->getRealPath(), [
                'folder' => 'uploads'
            ])->getSecurePath();
        }        


        News::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'category_id' => $request->input('category_id'),
            'image_url' =>$uploadedFileUrl
        ]);

        return response([
            "message" => "Berhasil tambah Berita"
        ], 201);
                
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::with(['category','listComments'=>[
            'createdBy'
        ]])->find($id);

        if(!$news) {
            return response([
                "message" => "$id berita tidak ditemukan di DB"
            ], 404);
        }

        return response([
            "message" => "Detail News berhasil ditampilkan",
            "data" =>$news
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedata = $request->validate([
            'title'=>'string',
            'content'=>'string',
            'category_id' => 'exists:categories,id',
            'image_url' => 'image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'exists' => 'category_id yang diinput tidak ditemukan'
        ]);

        if($request->has('image_url')){
            $uploadedFileUrl = cloudinary()->upload($request->file('image_url')->getRealPath(), [
                'folder' => 'uploads'
            ])->getSecurePath();
        }        

        News::where('id', $id)
        ->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'category_id' => $request->input('category_id'),
            'image_url' => $uploadedFileUrl
        ]);

        return response([
            "message" => "News berhasil diupdate"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $news = News::find($id);

        if(!$news) {
            return response([
                "message" => "$id tidak ditemukan di DB"
            ], 404);
        }
 
        $news->delete();

        return response([
            "message" => "Berita berhasil didelete"
        ], 200);
    }
}
