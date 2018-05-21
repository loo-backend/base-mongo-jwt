<?php

namespace App\Services;

use App\User;

/**
 * Class UserAllService
 * @package App\Services
 */
class UserTenantAllService
{

    /**
     * All Users
     *
     * @return bool|array
     */
    public function all()
    {

        $filter = [
            ['is_administrator', false]
        ];

        if (!$user = User::where($filter)->paginate() ) {
            return false;
        }

        return $user;

    }

}
