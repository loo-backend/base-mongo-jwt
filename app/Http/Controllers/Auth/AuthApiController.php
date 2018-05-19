<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Factories\JWTTokenBearerFactory;

use App\Services\UserWhereFirstService;
use JWTAuth;
use JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthApiController extends Controller
{
    /**
     * @var JWTTokenBearerFactory
     */
    private $factory;

    /**
     * AuthApiController constructor.
     * @param JWTTokenBearerFactory $factory
     */
    public function __construct(JWTTokenBearerFactory $factory)
    {

        $this->factory = $factory;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function authenticate(Request $request)
    {

        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {

            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {

                  return response()->json([
                    'success' => false,
                    'error' => 'invalid_credentials'
                ], 401);
            }

        } catch (JWTException $e) {

            // something went wrong whilst attempting to encode the token
            return response()->json([
                'success' => false,
                'error' => 'could_not_create_token'
            ], 500);

        }


        $token = $this->factory->generate($request);

        //Authorization || HTTP_Authorization
        return response()->json([
            'success' => true,
            'HTTP_Authorization' => "Bearer {$token}"
        ], 200);


    }

}
