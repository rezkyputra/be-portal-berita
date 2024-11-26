<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comments;

class CommentsController extends Controller
{
    public function updatecreate(Request $request, string $news_id)
    {
        $request->validate([
            'content' => 'required',
        ], [
            'required' => 'inputan :attribute harus diisi.'
        ]);

        $currentUser = auth()->user();

        $comment = Comments::updateOrCreate(
            ['user_id' => $currentUser->id, 'news_id' => $news_id],
            [
                'news_id' => $news_id, 
                'content' => $request->input('content'),
                'user_id' => $currentUser->id
            ]
        );

        return response([
            "message" => "Berhasil update/Create Comment",
            "data" => $comment
        ], 200);

    }
}
