<?php

namespace App\Services\jwt;


use Illuminate\Http\Response;
use App\Exceptions\BaseException;
use App\Http\Resources\UserResource;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class SvAuth extends BaseService
{
    public function getRules()
    {
        return array(
            'credential.email'    => 'required|email',
            'credential.password' => 'required|min:4',
        );
    }

    public function login($params)
    {
        $email      = $params['email'];
        $password   = $params['password'];

        $user = $this->getByEmail($email);

        if (!isset($user) || !Hash::check($password, $user->password)) {
            throw new BaseException('Incorrect email or password', "WRONG_PASSWORD", [], Response::HTTP_BAD_REQUEST);
        }

        if (!$token = Auth::guard('api')->attempt([
            'email'    => $email,
            'password' => $password
        ])) {
            throw new BaseException(
                'Unable to login',
                "WRONG_PASSWORD",
                [],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!$user->is_admin) {
            Auth::logout();
            throw new BaseException('You don\'t have permission to authenticate as admin', "WRONG_PASSWORD", [], 404);
        }

        return array(
            'token' => $token,
            'user'  => new UserResource($user)
        );
    }

    public function getUser()
    {
        return new UserResource(Auth::guard('api')->user());
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return "Successfully logout";
    }

    private function getByEmail($email)
    {
        $user = DB::table('users')
            ->where('email', $email)
            ->select('*')
            ->first();
        return $user;
    }
}
