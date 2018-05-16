<?php

namespace App\Services;

use App\User;

/**
 * Class UserRemoveService
 * @package App\Services
 */
class UserRemoveService
{

    /**
     * Remove User
     *
     * @param $id
     * @return bool|array
     */
    public function remove($id)
    {

        if (!$user = User::find($id) ) {
            return false;
        }

        return $user->delete();

    }

}
