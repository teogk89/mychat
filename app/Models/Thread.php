<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    //

    protected $table = 'threads';




    public function messages(){

    	return $this->hasMany('App\Models\Message','thread_id','id');
    }

    public function participant(){

        return $this->hasMany('App\Models\Participant','thread_id','id');
    }

    public function Sender($id){


        $pa = $this->participant->where('user_id','!=',$id)->first();


    	return \App\User::find($pa->user_id);
    }
}
