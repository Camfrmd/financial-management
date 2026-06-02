<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all()->makeHidden(['password', 'remember_token']);
        return response()->json(['status' => 'success', 'data' => $users], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', 
            'role' => 'required|in:treasurer,kelian'
        ]);

        $user = User::create($validated);
        return response()->json(['status' => 'success', 'data' => $user->makeHidden('password')], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with(['transactionsCreated', 'transactionsValidated'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $user->makeHidden('password')], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id . ',user_id',
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|required|in:treasurer,kelian'
        ]);

        $user->update($validated);
        return response()->json(['status' => 'success', 'data' => $user->makeHidden('password')], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['status' => 'success', 'message' => 'Utilisateur supprimé'], 200);
    }
}
