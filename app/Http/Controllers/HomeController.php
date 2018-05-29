<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models;
use Pusher;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }



    public function chat($id = null){

        $user = \Auth::user();
        $thread = null;
        if($id == null){  
            $pa =  $user->participants->sortBy('created_at');
            $thread = $pa->first()->thread;
        }
        else{
            $thread = \App\Models\Thread::find($id);
        }

        $test = $thread->Sender($user->id);
        return view('chat',compact(
            'user',
            'thread'
        ));
    }

    public function api(Request $request){

        
        if($request->input('type') == 'add'){


              
            $user = \Auth::user();
            $thread_id = $request->input('thread');
            $content = $request->input('content');

            $mess = new \App\Models\Message();

            $mess->user_id = $user->id;
            $mess->thread_id = $thread_id;
            $mess->body = $content;
            $mess->type = "message";
            $mess->save();

            $thread =  Models\Thread::find($thread_id);
            $to_user = $thread->Sender($user->id);

            $options = array(
                'cluster' => 'us2',
                'encrypted' => true
              );
              $pusher = new Pusher(
                'a67405a003f557ce006c',
                '7616e99448fb67c2f7c1',
                '528961',
                $options
              );

            
            $pusher->trigger('user-'.$to_user->id, 'MessageSent',$mess);

            //$myEvent = new \App\Events\MessageSent($mess,$to_user->id);
           // $result = broadcast($myEvent)->toOthers();

            echo (string)view('chatbox',['t'=>$mess,'user_id'=>$user->id]);
        //$to_user = \App\User::find($test);
        }

        if($request->input('type') == 'create_mess_box'){

            $user = \Auth::user();
            $id = $request->input('mess');
            $mess = Models\Message::find($id);
            echo (string)view('chatbox',['t'=>$mess,'user_id'=>$user->id]);
        }

    }
}
