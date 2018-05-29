<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //


    protected $table = 'threads_messages';


    public function user(){

        return $this->hasOne('App\User','id','user_id');

    }
}
