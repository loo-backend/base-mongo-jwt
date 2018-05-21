<?php

namespace App\Http\Controllers\Auth;

use App\Factories\JWTTokenBearerFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use JWTFactory;

class AuthApiController extends Controller
{
    /**
     * @var JWTTokenBearerFactory
     */
    private $bearerFactory;

    /**
     * AuthApiController constructor.
     * @param JWTTokenBearerFactory $bearerFactory
     */
    public function __construct(JWTTokenBearerFactory $bearerFactory)
    {
        $this->bearerFactory = $bearerFactory;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function authenticate(Request $request)
    {


        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {

            return response()->json([
                'success' => false,
                'error' => 'invalid_credentials'
            ], 401);
        }

        //Authorization || HTTP_Authorization
        return response()->json([
            'success' => true,
            'HTTP_Authorization' => $this->bearerFactory->generate($request)
        ], 200);


    }

}
