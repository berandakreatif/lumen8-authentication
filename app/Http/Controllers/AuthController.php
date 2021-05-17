<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class AuthController extends Controller
{

    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:6'
        ]);

        $email = $request->input("email");
        $password = $request->input("password");

        $hashPwd = Hash::make($password);

        $data = [
            "email" => $email,
            "password" => $hashPwd
        ];

        if (User::create($data)) {
            $out = [
                'status' => 'success',
                "message" => "register_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                'status' => 'error',
                "message" => "vailed_regiser",
                "code"   => 404,
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        $email = $request->input("email");
        $password = $request->input("password");

        $user = User::where("email", $email)->first();

        if (!$user) {
            $out = [
                'status' => 'error',
                "message" => "login_vailed",
                "code"    => 401,
                "data"  => [
                    "token" => null,
                ]
            ];
            return response()->json($out, $out['code']);
        }

        if (Hash::check($password, $user->password)) {
            $newtoken  = $this->generateRandomString();

            $user->update([
                'token' => $newtoken
            ]);

            $out = [
                'status' => 'success',
                "message" => "login_success",
                "code"    => 200,
                "data"  => [
                    "token" => $newtoken,
                ]
            ];
        } else {
            $out = [
                'status' => 'error',
                "message" => "login_vailed",
                "code"    => 401,
                "data"  => [
                    "token" => null,
                ]
            ];
        }

        return response()->json($out, $out['code']);
    }

    function generateRandomString($length = 80)
    {
        $char = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charlen = strlen($char);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $char[rand(0, $charlen - 1)];
        }
        return $str;
    }
}
