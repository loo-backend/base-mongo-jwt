<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;


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


    public function migrateAndFactory()
    {
        Artisan::call('migrate', [
            '--path' => "app/database/migrations"
        ]);

        $users = factory(User::class)->create(['is_administrator' => true]);
        $users->roles()->create($this->roles);

    }


    public function testUserCreate()
    {

        $this->migrateAndFactory();

        $data = $this->data;
        $data['roles'] = $this->roles;


        $user = User::first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/vnd.laravel.v1+json',
            'HTTP_Authorization' => 'Bearer ' . $token
        ];

        $response = $this->post('/users/admins', $data, $headers)
            ->assertStatus(200);


        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
            'is_administrator' => $data['is_administrator']
        ]);

        $response->assertJsonStructure([
            'success',
            'HTTP_Authorization'
        ]);

    }


    public function testShowUser()
    {


        $user = User::first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/vnd.laravel.v1+json',
            'HTTP_Authorization' => 'Bearer ' . $token
        ];

        $response = $this->get('/users/admins/'. $user->id, $headers)
            ->assertStatus(200);


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

        $user = User::first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/vnd.laravel.v1+json',
            'HTTP_Authorization' => 'Bearer ' . $token
        ];

        $response = $this->get('/users/admins', $headers)
            ->assertStatus(200);


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
            'email' => $user->email
        ];

        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/vnd.laravel.v1+json',
            'HTTP_Authorization' => 'Bearer ' . $token
        ];

        $this->put('/users/admins/'.$user->id, $data, $headers)
            ->assertStatus(200);


        $this->assertDatabaseMissing('users',[
            'name' => $user->name,
            'email' => $user->email,
            '_id' => $user->id
        ]);

    }

    public function testUpdateUserWithPassword()
    {

        $data = [
            'name' => str_random(12),
            'email' => str_random(7) . '@mail.com',
            'password' => 123456,
            'password_confirmation' => 123456
        ];


        $user = User::first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/vnd.laravel.v1+json',
            'HTTP_Authorization' => 'Bearer ' . $token
        ];

        $this->put('/users/admins/'.$user->id, $data, $headers)
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
            'email' => $user->email,
            '_id' => $user->id
        ]);

    }

    /**
     *
     */
    public function testDeleteUser()
    {

        $user = User::first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);


        $response = $this->withHeaders([
            'HTTP_Authorization' => 'Bearer '. $token,
        ])->json('DELETE', '/users/admins/'.$user->id);

        $response->assertStatus(200)
            ->assertExactJson([
                'response' => 'user_removed'
            ]);


        $users = User::all();

        foreach ($users as $user) {
            User::find($user->id)->forceDelete();
        }


    }

}
