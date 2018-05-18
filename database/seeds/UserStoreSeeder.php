<?php

use Illuminate\Database\Seeder;

class UserStoreSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->storeAdmin();
        $this->storeStaff();
        $this->storeDevelop();

    }


    public function storeAdmin()
    {

        $roles = ['name' => 'STORE_ADMINISTRATOR',
            'permissions' => [
                'ALL'
            ]
        ];

        $users = factory(App\User::class,5)->create(['type_store' => true]);

        $users->each(function ($user) use($roles) {
            $user->roles()->create($roles);
        });
        
    }


    public function storeStaff()
    {

        $roles = ['name' => 'STORE_STAFF',
            'permissions' => [
                'BROWSER',
                'READ',
                'ADD',
                'EDIT'
            ]
        ];

        $users = factory(App\User::class,50)->create(['type_store' => true]);

        $users->each(function ($user) use($roles) {
            $user->roles()->create($roles);
        });

    }

    public function storeDevelop()
    {

        $roles = ['name' => 'STORE_DEVELOP',
            'permissions' => [
                'BROWSER',
                'READ',
                'ADD',
                'EDIT'
            ]
        ];

        $users = factory(App\User::class,50)->create(['type_store' => true]);

        $users->each(function ($user) use($roles) {
            $user->roles()->create($roles);
        });

    }

}
