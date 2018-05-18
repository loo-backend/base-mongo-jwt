<?php

use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->administrator();
        $this->staffSupport();
        $this->staffFinance();
        $this->staffCommercial();

    }


    public function administrator()
    {

        $roles = ['name' => 'ADMINISTRATOR',
            'permissions' => [
                'ALL'
            ]
        ];

        $users = factory(App\User::class,5)->create(['type' => 'ADMIN']);

        $users->each(function ($user) use($roles) {
            $user->roles()->create($roles);
        });
        
    }


    public function staffSupport()
    {

        $roles = ['name' => 'ADMIN_STAFF_SUPPORT',
            'permissions' => [
                'BROWSER',
                'READ',
                'ADD',
                'EDIT'
            ]
        ];

        $users = factory(App\User::class,50)->create(['type' => 'ADMIN']);

        $users->each(function ($user) use($roles) {
            $user->roles()->create($roles);
        });

    }

    public function staffFinance()
    {

        $roles = ['name' => 'ADMIN_STAFF_FINANCE',
            'permissions' => [
                'BROWSER',
                'READ',
                'ADD',
                'EDIT'
            ]
        ];

        $users = factory(App\User::class,50)->create(['type' => 'ADMIN']);

        $users->each(function ($user) use($roles) {
            $user->roles()->create($roles);
        });

    }

    public function staffCommercial()
    {

        $roles = ['name' => 'ADMIN_STAFF_COMMERCIAL',
            'permissions' => [
                'BROWSER',
                'READ',
                'ADD',
                'EDIT'
            ]
        ];

        $users = factory(App\User::class,50)->create(['type' => 'ADMIN']);

        $users->each(function ($user) use($roles) {
            $user->roles()->create($roles);
        });

    }
    

}
