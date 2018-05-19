<?php

class JWTTokenService {

    public function generateToken() {

        $factory = JWTFactory::customClaims([
            'sub' => $this->whereFirstService->whereFirst([
                'email' => $request->input('email')
            ])
        ]);

        $payload = $factory->make();
        $token = JWTAuth::encode($payload);

        //Authorization || HTTP_Authorization
        return $token;
        // return response()->json([
        //     'success' => true,
        //     'token' => "Bearer {$token}",
        // ], 200);

    }


}
