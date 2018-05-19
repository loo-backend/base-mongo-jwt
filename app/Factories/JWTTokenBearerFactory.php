<?php

namespace App\Factories;

use Illuminate\Http\Request;
use App\Services\UserWhereFirstService;
use JWTAuth;
use JWTFactory;

/**
 * Class JWTTokenBearerFactory
 * @package App\Factories
 */
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


    /**
     * @param Request $request
     * @return string
     */
    public function generate(Request $request)
    {

        $user = $this->whereFirstService
            ->whereFirst(['email' => $request->input('email')]);

        $factory = JWTFactory::customClaims([
            'sub' => $user
        ]);

        $payload = $factory->make();

        $token = JWTAuth::encode($payload);

        return "Bearer {$token}";

    }

}
