<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function updatecreate(Request $request){
        //Validation
       $request->validate([
            'age' => 'required',
            'bio' => 'required',
            'address' => 'required',
        ], [
            'required' => 'inputan :attribute harus diisi.'
        ]);

        $currentUser = auth()->user();

        $profile = Profile::updateOrCreate(
            ['user_id' => $currentUser->id],
            [
                'age' => $request->input('age'), 
                'bio' => $request->input('bio'),
                'address' => $request->input('address'),
                'user_id' => $currentUser->id
            ]
        );

        return response([
            "message" => "Berhasil update/Create Profile",
            "data" => $profile
        ], 200);
    }
}
