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

        return ['HTTP_Authorization' => "Bearer {$token}"];

    }


}
