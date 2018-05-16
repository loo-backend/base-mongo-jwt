<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;

/**
 * Class UserUpdateService
 * @package App\Services
 */
class UserUpdateService
{

    /**
     * Remove User
     *
     * @param Request $request
     * @param $id
     * @return bool|array
     */
    public function update(Request $request, $id)
    {

        $user = User::find($id);

        if ($user) {
           $user->update($request->all());
        }

        return $user;
    }

}
