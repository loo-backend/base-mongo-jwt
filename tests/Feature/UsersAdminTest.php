<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

date_default_timezone_set('America/Sao_Paulo');

class UsersAdminTest extends TestCase
{

    private $roles =
        ['name' => 'ADMINISTRATOR',
            'permissions' => [
                'ALL'
            ]
        ];


    public $data = [];

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);


        $this->data = [
            'name' => str_random(10),
            'email' => str_random(6) . '@mail.com',
            'active' => true,
            'is_administrator' => true,
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

    }


    public function getToken()
    {

        $users = factory(User::class)->create(['is_administrator' => true]);
        $users->roles()->create($this->roles);

        $user = User::first();

        $response = $this->post('/auth/authenticate',
            ['email'=>$user->email,'password' => $this->data['password']])
            ->assertStatus(200);

        $data = (array) json_decode( $response->content() );

        return $data['token'];

    }

    public function testUserCreate()
    {

        $data = $this->data;
        $data['roles'] = $this->roles;

        $response = $this->withHeaders([
            'HTTP_Authorization' => 'Bearer '. $this->getToken(),
        ])->json('POST', '/users/admins', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
            'is_administrator' => $data['is_administrator']
        ]);

        $response->assertJsonStructure([
            'success',
            'data' => [
                '_id',
                'user_uuid',
                'name',
                'email',
                'active',
                'is_administrator',
                'roles' => [
                    '*' => [
                        'name', 'permissions'
                    ]
                ]

            ],
            'token'

        ]);

    }


    public function testShowUser()
    {

        $user = User::first();

        $response = $this->withHeaders([
            'HTTP_Authorization' => 'Bearer '. $this->getToken(),
        ])->json('GET', '/users/admins/'. $user->id);


        $response->assertStatus(200);

        $response->assertJsonStructure([
            '_id',
            'user_uuid',
            'name',
            'email',
            'active',
            'roles' => [
                '*' => [
                    'name', 'permissions'
                ]
            ]
        ]);

    }

    public function testAllUsers()
    {

        $response = $this->withHeaders([
            'HTTP_Authorization' => 'Bearer '. $this->getToken(),
        ])->json('GET', '/users/admins');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [

                '_id',
                'user_uuid',
                'name',
                'email',
                'active',
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
            'email' => $user->email,
            'token' => $this->getToken()
        ];

        $response = $this->withHeaders([
            'HTTP_Authorization' => 'Bearer '. $this->getToken(),
        ])->json('PUT', '/users/admins/'.$user->id, $data);


        $response->assertStatus(200);

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
            'password_confirmation' => 123456,
            'token' => $this->getToken()
        ];


        $response = $this->withHeaders([
            'HTTP_Authorization' => 'Bearer '. $this->getToken(),
        ])->json('PUT', '/users/admins/'.$user->id, $data);


        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
            'email' => $user->email,
            '_id' => $user->id
        ]);

    }

    public function testDeleteUser()
    {

        $user_all = User::all();

        foreach ($user_all as $u) {
            User::find($u->id)->forceDelete();
        }

        $user =  factory(User::class)->create()->first();

        $response = $this->withHeaders([
            'HTTP_Authorization' => 'Bearer '. $this->getToken(),
        ])->json('DELETE', '/users/admins/'.$user->id);

        $response->assertStatus(200)
                ->assertExactJson([
                    'response' => 'user_removed'
                ]);


    }

}
