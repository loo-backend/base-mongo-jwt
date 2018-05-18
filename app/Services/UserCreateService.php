<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Webpatser\Uuid\Uuid;

/**
 * Class UserCreateService
 * @package App\Services
 */
class UserCreateService
{

    /**
     * Standard permissions rules
     *
     * @var array
     */
    private $roles =
        ['name' => 'TENANT_ADMINISTRATOR',
            'permissions' => [
                'ALL'
            ]
        ];

    /**
     * Create User
     *
     * @param Request $request
     * @return bool
     * @throws \Exception
     */
    public function create(Request $request)
    {

        $data = $request->all();

        if (!empty($request['roles'])) {
            $this->roles = $request['roles'];
        }

        $collection = collect($this->roles);

        $contains = $collection->contains('ADMINISTRATOR');
        if($contains === false) {
            unset($data['is_administrator']);
        }

        unset($data['roles']);

        $data['password'] = Hash::make($request->all()['password']);
        $data['user_uuid'] = Uuid::generate(4)->string;

        if (!isset($request['active'])) {
            $data['active'] = false;
        }

        if (!$create = User::create($data) ) {
            return false;
        }

        $create->roles()->create($this->roles);

        return $create;

    }

}
