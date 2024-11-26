<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Roles;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = auth()->user();

        $roleAdmin = Roles::where('name', 'admin')->first();

        if($currentUser->role_id === $roleAdmin->id){
            return $next($request);
        }

        return response()->json([
            'message' => "cuman admin yang bisa mengakses halaman ini"
        ], 401);

    }
}
