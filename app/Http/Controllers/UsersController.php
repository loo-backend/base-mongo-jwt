<?php

namespace App\Http\Controllers;

use App\Services\UserAllService;
use App\Services\UserCreateService;
use App\Services\UserFindService;
use App\Services\UserRemoveService;
use App\Services\UserUpdateService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTFactory;


class UsersController extends Controller
{


    /**
     * @var UserCreateService
     */
    private $createService;

    /**
     * @var UserFindService
     */
    private $findService;

    /**
     * @var UserAllService
     */
    private $allService;

    /**
     * @var UserRemoveService
     */
    private $removeService;

    /**
     * @var UserUpdateService
     */
    private $updateService;

    /**
     * UsersController constructor.
     * @param UserCreateService $createService
     * @param UserFindService $findService
     * @param UserAllService $allService
     * @param UserRemoveService $removeService
     * @param UserUpdateService $updateService
     */
    public function __construct(UserCreateService $createService,
                                UserFindService $findService,
                                UserAllService $allService,
                                UserRemoveService $removeService,
                                UserUpdateService $updateService)
    {

        $this->createService = $createService;
        $this->findService = $findService;
        $this->allService = $allService;
        $this->removeService = $removeService;
        $this->updateService = $updateService;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function authenticate(Request $request)
    {

        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {

        $result = $this->allService->all();

        if (count($result) <=0 ) {

            return response()->json(['error' => 'users_not_found'], 422);
        }

        return response()->json($result,200);

    }


    /**
     *
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Response
     * @throws \Exception
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|confirmed|min:6|max:255'
        ]);

        if (!$result = $this->createService->create($request)) {

            return response()->json(['error' => 'user_not_created'], 500);
        }

        return response()->json($result, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return bool
     * @throws \Exception
     */
    public function show($id)
    {

        if (!$result = $this->findService->findBy($id)) {

            return response()->json(['error' => 'user_not_found'], 422);
        }

        return response()->json($result,200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validation = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                Rule::unique('users', '_id')->ignore($id),
                'max:255'
            ]
        ];


        if(isset($request->all()['password'])){
            $validation['password'] = 'required|confirmed|max:255';
        }

        $this->validate($request, $validation);


        if (!$result = $this->findService->findBy($id)) {
            return response()->json(['error' => 'user_not_found'], 422);
        }

        if (!$result = $this->updateService->update($request, $id)) {

            return response()->json(['error' => 'user_not_updated'], 422);
        }


        return response()->json($result,200);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (!$result = $this->findService->findBy($id)) {
            return response()->json(['error' => 'user_not_found'], 422);
        }


        if (!$result = $this->removeService->remove($id)) {

            return response()->json(['error' => 'user_not_removed'], 422);
        }

        return response()->json(['response'=> 'user_removed'],200);

    }

}
