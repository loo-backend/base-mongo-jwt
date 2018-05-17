<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class AuthApiTest extends TestCase
{

    public $data = [];
    public $content;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->data = [
            'name' => str_random(10),
            'email' => str_random(6) . '@mail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

    }

    public function getToken()
    {

        factory(User::class)->create();

        $user = User::first();

        $response = $this->post('/auth/authenticate',
            ['email'=>$user->email,'password' => $this->data['password']])
            ->assertStatus(200);

        $data = (array) json_decode( $response->content() );

        return $data['token'];

    }

    public function testUserCreate()
    {


        $response = $this->withHeaders([
            'Authorization' => 'Bearen '. $this->getToken(),
        ])->json('POST', '/admin/users', $this->data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => $this->data['name'],
            'email' => $this->data['email']
        ]);

    }


    public function testUserAuthenticateValid() {

        $user = User::first();


        $response = $this->post('/auth/authenticate',
                ['email'=>$user->email,'password' => $this->data['password']])
            ->assertStatus(200);

        $response->assertJson(['success' => true]);
        $response->assertJson(['token' => true]);
        $response->assertJson(['data' => true]);

    }


    public function testUserAuthenticateInvalid() {

        $user = User::first();

        $response = $this->post('/auth/authenticate',
                ['email'=>$user->email,'password' => str_random(6)])
            ->assertStatus(401);

        $response->assertJson(['success' => false]);
        $response->assertJson(['error' => 'invalid_credentials']);

    }


}
