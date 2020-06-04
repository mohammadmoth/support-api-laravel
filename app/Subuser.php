<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subuser extends Model
{


    protected $fillable = [
        'database_id', 'apps_id', 'key',
    ];


    protected $hidden = [];
}
