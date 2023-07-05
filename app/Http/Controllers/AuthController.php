<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->accessToken;

            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function signup(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
        ]);

        $user = User::create([
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $profile = Profile::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'address' => $validatedData['address'],
        ]);

        $user->profile()->save($profile);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['token' => $token], 201);
    }
    public function createNote(Request $request)
    {
        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        
        $response = Http::post('http://server3/notes/create', $request->all());

      
        $responseData = $response->json();

     
        return response()->json($responseData);
    }

    public function updateNote(Request $request, $noteId)
    {
       
        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        
        $response = Http::put("http://server3/notes/update/{$noteId}", $request->all());

      
        $responseData = $response->json();

       
        return response()->json($responseData);
    }

    public function deleteNote(Request $request, $noteId)
    {
       
        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        
        $response = Http::delete("http://server3/notes/delete/{$noteId}");

        
        $responseData = $response->json();

       
        return response()->json($responseData);
    }

    public function logout(Request $request)
    {


    $request->user()->token()->revoke();

    return response()->json(['message' => 'Logged out successfully'], 200);


        
    }
}
