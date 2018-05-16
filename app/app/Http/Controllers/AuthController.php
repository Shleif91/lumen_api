<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\JWTService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function authenticate(Request $request)
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
            $jwtService = app(JWTService::class);

            return response()->json([
                'token' => $jwtService->getJWT($user)
            ]);
        }

        return response()->json([
            'error' => ['Email or password is wrong.']
        ], Response::HTTP_BAD_REQUEST);
    }
}