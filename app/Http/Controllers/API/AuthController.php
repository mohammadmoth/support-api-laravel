<?php

namespace App\Http\Controllers\Api;

use App\Http\AppClass\API;
use App\Http\AppClass\Apps;
use App\Http\AppClass\SubUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use stdClass;

class AuthController extends Controller
{
    public $successStatus = 200;

    public static $codes = [
        4 => "error Validator",
        0 => "Unauthorised",
        3 => "App Name Not found",
        3 => "error Validator",
        4 => "error Validator",

    ];


    public function register(Request $request)
    {
        return;
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), "code" => 4], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('AppName')->accessToken;
        return response()->json(['success' => $success], $this->successStatus);
    }

    private function _returnAppName($name)
    {
        //TODO تعديل للتأكد من اسم التطبيق
        //TODO اضافة المعلومات في جدول بقاعدة البيانات
        $std = new Apps();
        $std->Urlwebsite = "bottribalwars.test";
        $std->Website = "testsuper";
        $std->Admin = [1];



        return $std;
    }
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), "code" => 4], 401);
        }



        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            //TODO Tpye of Useradmin
            $success['token'] =  $user->createToken("super")->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised', "code" => 0], 401);
        }
    }

    public function loginbySubUser(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [

                'AppName' => 'required',
                'tokenapi' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), "code" => 4], 401);
        }
        $app = $this->_returnAppName($request->AppName);
        if ($app === false) {
            return response()->json(['error' => "App Name Not found", "code" => 3], 401);
        }

        $respon =   $this->CheckUserToken($app,  $request->tokenapi);
        if ($respon->error == true) {
            return response()->json(['error' => "Unauthorised", "code" => 0], 401);
        }

        $subUser =     $this->_makeNewKayForSubuser($app, $respon);

        return response()->json($subUser);
    }
    private function _makeNewKayForSubuser(Apps $app, $respon)
    {

        return new SubUser($app, $respon->Userid);
    }

    public function CheckUserToken(Apps $app, $token)
    {
        $resp = API::CheckUser($app, $token);

        return $resp;
    }

    public function getUser()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
}
