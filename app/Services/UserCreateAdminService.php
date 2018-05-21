<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Webpatser\Uuid\Uuid;

/**
 * Class UserCreateAdminService
 * @package App\Services
 */
class UserCreateAdminService
{

    /**
     * Standard permissions rules
     *
     * @var array
     */
    private $roles =
        ['name' => 'ADMIN_STAFF_INITIAL',
            'permissions' => [
                'BROWSER',
                'READ'
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

        $data['password'] = Hash::make($request->all()['password']);
        $data['user_uuid'] = Uuid::generate(4)->string;

        if (!isset($request['active'])) {
            $data['active'] = false;
        }

        $data['is_administrator'] = true;
        unset($data['roles']);

        if (!$create = User::create($data) ) {
            return false;
        }

        $create->roles()->create($this->roles);

        return $create;

    }

}
