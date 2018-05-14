<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected function getJWT(User $user): string
    {
        $payload = [
            'iss' => 'lumen-jwt',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60*60,
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function authenticate(Request $request, User $user)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->get('email'))->first();

        if (is_null($user)) {
            return response()->json([
                'error' => ['Email does not exist.']
            ], Response::HTTP_BAD_REQUEST);
        }

        if (Hash::check($request->get('password'), $user->password)) {
            $user->update(['last_login_at' => Carbon::now()]);
            return response()->json([
                'token' => $this->getJWT($user)
            ]);
        }

        return response()->json([
            'error' => ['Email or password is wrong.']
        ], Response::HTTP_BAD_REQUEST);
    }
}