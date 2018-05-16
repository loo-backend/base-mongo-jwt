<?php

namespace App\Services;

use App\User;

/**
 * Class UserAllService
 * @package App\Services
 */
class UserAllService
{

    /**
     * All Users
     *
     * @return bool|array
     */
    public function all()
    {

        if (!$user = User::all() ) {
            return false;
        }

        return $user;

    }

}
