<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function getUser(Request $request, $userId)
    {
        
        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

       
        $response = Http::get("http://server2/user/{$userId}");

      
        $responseData = $response->json();

      
        return response()->json($responseData);
    }
}
