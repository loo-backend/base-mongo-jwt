<?php

namespace App\Factories;

use Illuminate\Http\Request;
use App\Services\UserWhereFirstService;
use JWTAuth;
use JWTFactory;

class JWTTokenBearerFactory {

    /**
     * @var UserWhereFirstService
     */
    private $whereFirstService;

    /**
     * AuthApiController constructor.
     * @param UserWhereFirstService $whereFirstService
     */
    public function __construct(UserWhereFirstService $whereFirstService)
    {

        $this->whereFirstService = $whereFirstService;
    }


    public function generate(Request $request)
    {

        $user = $this->whereFirstService
            ->whereFirst(['email' => $request->input('email')]);

        $factory = JWTFactory::customClaims([
            'sub' => $user
        ]);

        $payload = $factory->make();

        return JWTAuth::encode($payload);

    }

}
