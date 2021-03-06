<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6|max:15'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400); // Bad Request
        }

        ['email' => $email, 'password' => $decryptedPassword] = $request->all();

        if(User::where('email', $email)->get()->first()) {
            return response()->json(['error' => 'User already exist'], 400); // Bad Request
        }

        $user = User::create($request->all());
        Collection::create([
            'name' => 'Favorites',
            'user_id' => $user->id
        ]);

        $token = auth()->attempt(['email' => $user->email, 'password' => $decryptedPassword]);

        return response()->json(['user' => $user, 'access_token' => $token], 201); // Created
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request): JsonResponse
    {
        $id = $request->attributes->get('loggedUserID');

        if(!$user = User::find($id)) {
            return response()->json(['error' => 'User not found'], 404); // Not found
        }

        return response()->json($user->load('collections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'min:3',
            'email' => 'email',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400); // Bad Request
        }

        if(!$request->all()){
            return response()->json(['error' => 'Nothing was sent'], 400); // Bad Request
        }

        $userId = $request->attributes->get('loggedUserID');
        $user = User::find($userId);

        $user->update($request->all());

        return response()->json($user->load('collections'), 200); // OK
    }

}
