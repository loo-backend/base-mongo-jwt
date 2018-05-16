<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class AuthApiTest extends TestCase
{


    public $data = [];

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

    public function testUserCreate()
    {

        $this->post('/admin/users', $this->data)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => $this->data['name'],
            'email' => $this->data['email']
        ]);

    }


    public function testUserAuthenticate(){

        $user = User::first();

        $response = $this->post('/auth/authenticate',
                ['email'=>$user->email,'password' => $this->data['password']])
            ->assertStatus(200);

        $content = (array) json_decode($response->content());

        $this->assertArrayHasKey('token', $content);


    }


    public function testDeleteUser()
    {
        $user = User::first();

        $this->delete('/admin/users/'.$user->id)
            ->assertStatus(200)
            ->assertExactJson([
                'response' => 'user_removed'
            ]);
    }

}
