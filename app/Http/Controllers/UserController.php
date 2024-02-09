<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = User::all();
        return response()->json($user);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $incomingFields = $request->validate([
            "first_name" => 'required',
            "last_name" => 'required',
            'email' => ['required', 'unique'],
            "password" => 'required',
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $newFields = $request->validate([
            "first_name" => 'required',
            "last_name" => 'required',
            'email' => 'required',
            "password" => 'required',
        ]);

        $user = User::create([
            "first_name" => $newFields['first_name'],
            "last_name" =>  $newFields['last_name'],
            'email' =>  $newFields['email'],
            "password" => bcrypt($newFields['password']),
        ]);
      

        return response()->json([
            'message'=> 'Profile created successfully',
            'user' => $user
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
        $user = User::findOrFail($id);

    return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $updateFields = $request->validate([
            "first_name" => 'required',
            "last_name" => 'required',
            'email' => ['required',  Rule::unique('users')->ignore($user->id)],
            "password" => 'required',
        ]);

        $user->update($updateFields);

        return response()->json($user);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      $user = User::findOrFail($id);

        $user->delete();
        return response()->json(['message' => 'Profile deleted']);
    }
}