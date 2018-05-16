<?php

declare(strict_types=1);

namespace App\Services;

use App\User;
use Firebase\JWT\JWT;

class JWTService
{
    public function getJWT(User $user): string
    {
        $payload = [
            'iss' => 'lumen-jwt',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }
}