<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserWhereFirstService;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthApiController extends Controller
{
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

        // all good so return the token
        return response()->json([
            'success' => true,
            'token' => $token,
            'data'=> $this->whereFirstService->whereFirst(['email' => $request->input('email')])
        ], 200);

    }

}
