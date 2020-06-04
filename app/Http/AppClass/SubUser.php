<?php

namespace App\Http\AppClass;

use App\Subuser as AppSubuser;
use Illuminate\Support\Str;

class SubUser
{
    public $Key;
    public $Id;
    public $App;


    function __construct(Apps $app, $userid, $newkey = true)
    {
        $this->App = $app;
        $this->Id = $userid;
        if ($newkey) {
            setkey: $key =  $this->SetNewKey($app, $userid);
        } else {
            $key = $this->GetKey($app, $userid);
            if ($key == false) {
                goto setkey;
            }
        }
        $this->Key = $key;
    }

    public function SetNewKey($appID,  $userid)
    {
        $Subuser =  AppSubuser::where("apps_id", $appID)->where("database_id", $userid)->fisrt();
        if ($Subuser == null) {
            $Subuser = new AppSubuser();
            $Subuser->apps_id =  $appID;
            $Subuser->database_id = $userid;
        }
        $Subuser->key = Str::random(32);
        $Subuser->save();
    }


    public function GetKey($appID,  $userid)
    {

        $Subuser =      AppSubuser::where("apps_id", $appID)->where("database_id", $userid)->fisrt();
        if ($Subuser == null || $Subuser->key)
            return false;

        return $Subuser->key;
    }
}
