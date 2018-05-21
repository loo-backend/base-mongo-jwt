<?php

namespace App\Services;

use App\User;

/**
 * Class UserAdminAllService
 * @package App\Services
 */
class UserAdminAllService
{

    /**
     * All Users
     *
     * @return bool|array
     */
    public function all()
    {

        $filter = [
            ['is_administrator', true]
        ];

        if (!$user = User::where($filter)->paginate() ) {
            return false;
        }

        return $user;

    }

}
