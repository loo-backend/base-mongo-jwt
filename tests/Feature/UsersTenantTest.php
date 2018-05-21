<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class UsersTenantTest extends TestCase
{

    private $roles =
        ['name' => 'TENANT_ADMINISTRATOR',
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
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

    }

    public function migrateAndFactory()
    {
        Artisan::call('migrate', [
            '--path' => "app/database/migrations"
        ]);

        $users = factory(User::class)->create();
        $users->roles()->create($this->roles);

    }


    public function testUserCreate()
    {

        $this->migrateAndFactory();

        $data = $this->data;
        $data['roles'] = $this->roles;


        $user = User::where('is_administrator', false)->first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/vnd.laravel.v1+json',
            'HTTP_Authorization' => 'Bearer ' . $token
        ];

        $response = $this->post('/users/tenants', $data, $headers)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $response->assertJsonStructure([
            'success',
            'HTTP_Authorization'
        ]);

    }


    public function testShowUser()
    {

        $user = User::where('is_administrator', false)->first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/vnd.laravel.v1+json',
            'HTTP_Authorization' => 'Bearer ' . $token
        ];

        $response = $this->get('/users/tenants/'. $user->id, $headers)
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

    public function testAllUsersTenants()
    {

        $user = User::where('is_administrator', false)->first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/vnd.laravel.v1+json',
            'HTTP_Authorization' => 'Bearer ' . $token
        ];

        $response = $this->get('/users/tenants', $headers)
            ->assertStatus(200);


        $response->assertJsonStructure([
            'data' => [

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

            ]

        ]);

    }

    public function testUpdateUserNoPassword()
    {

        $user = User::where('is_administrator', false)->first();

        $data = [
            'name' => str_random(12),
            'email' => $user->email
        ];

        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/vnd.laravel.v1+json',
            'HTTP_Authorization' => 'Bearer ' . $token
        ];

        $this->put('/users/tenants/'.$user->id, $data, $headers)
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


        $user = User::where('is_administrator', false)->first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/vnd.laravel.v1+json',
            'HTTP_Authorization' => 'Bearer ' . $token
        ];

        $this->put('/users/tenants/'.$user->id, $data, $headers)
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
            'email' => $user->email,
            '_id' => $user->id
        ]);

    }

//    public function testDeleteUser()
//    {
//
//        $user = User::first();
//        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
//
//        $response = $this->withHeaders([
//            'HTTP_Authorization' => 'Bearer '. $token,
//        ])->json('DELETE', '/users/tenants/'.$user->id);
//
//        $response->assertStatus(200)
//            ->assertExactJson([
//                'response' => 'user_removed'
//            ]);
//
//
//        $users = User::all();
//
//        foreach ($users as $user) {
//            User::find($user->id)->forceDelete();
//        }
//
//
//    }

}
