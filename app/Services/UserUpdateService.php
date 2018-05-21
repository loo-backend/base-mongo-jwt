<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

            $data = $request->all();

            if (isset($request->all()['password'])) {
                $data['password'] = Hash::make($request->all()['password']);
            }

            $user->update($data);
        }

        return $user;
    }

}
