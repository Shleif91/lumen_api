<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function showAllUsers()
    {
        return response()->json(User::all());
    }

    public function showOneUser($id)
    {
        return response()->json(User::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = User::create($request->all());

        return response()->json($user, Response::HTTP_CREATED);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();

        return response('Deleted Successfully');
    }
}