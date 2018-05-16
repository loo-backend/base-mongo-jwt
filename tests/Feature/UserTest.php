<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
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

        $response = $this->post('/admin/users/authenticate',
                ['email'=>$user->email,'password' => $this->data['password']])
            ->assertStatus(200);

        $content = (array) json_decode($response->content());

        $this->assertArrayHasKey('token', $content);


    }

    public function testShowUser()
    {
        $user = User::first();

        $response = $this->get('/admin/users/'. $user->id)
                ->assertStatus(200);

        $response->assertJsonStructure([
            '_id',
            'name',
            'email',
            'roles' => [
                '*' => [
                    'name', 'permissions'
                ]
            ]
        ]);

    }

    public function testAllUsers()
    {

        $response = $this->get('/admin/users')
                         ->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [

                '_id',
                'name',
                'email',
                'roles' => [
                    '*' => [
                        'name', 'permissions'
                    ]
                ]

            ]

        ]);

    }

    public function testUpdateUserNoPassword()
    {
        $user = User::first();

        $data = [
            'name' => str_random(12),
            'email' => $user->email
        ];

        $this->put('/admin/users/'. $user->id, $data);

        $this->assertDatabaseMissing('users',[
            'name' => $user->name,
            'email' => $user->email,
            '_id' => $user->id
        ]);

    }

    public function testUpdateUserWithPassword()
    {
        $user = User::first();
        $data = [
            'name' => str_random(12),
            'email' => str_random(7) . '@mail.com',
            'password' => 123456,
            'password_confirmation' => 123456
        ];

        $this->put('/admin/users/' . $user->id, $data)
             ->assertStatus(200);


        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
            'email' => $user->email,
            '_id' => $user->id
        ]);

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
