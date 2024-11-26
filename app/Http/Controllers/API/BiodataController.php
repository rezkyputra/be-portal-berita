<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BiodataController extends Controller
{
    public function index()
    {
        return response(
            [
                "message" => "Testing API"
            ],
            200
        );
    }

    public function daftar(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'required' => 'inputan :attribute harus diisi.',
            'min' => 'inputan :attribute minimal harus :min karakter',
            'email' => 'inputan harus berformat email foo@mail.com',
        ]);

        return response([
            "message" => $validated
        ], 200);
    }
}
