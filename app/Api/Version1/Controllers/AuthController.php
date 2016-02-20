<?php
namespace App\Api\Version1\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Api\Version1\Bases\ApiController;

class AuthController extends ApiController {

    public function login(Request $request) {
        $input   = $request->only('account', 'password');
        $account = $input['account'];

        if (preg_match("/^[0-9+]{8}$/i", $account) == true) {
            $input['phone'] = $account;
        }else{
            $input['email'] = $account;
        }

        unset($input['account']);

        try {
            $token = JWTAuth::attempt($input);

            if (!$token) {
                throw new AccessDeniedHttpException('invalid credentials');
            }
        } catch (JWTException $e) {
            throw new HttpException('Could not create token');
        }

        return $this->response->array([
            'token' => $token
        ]);
    }

}
