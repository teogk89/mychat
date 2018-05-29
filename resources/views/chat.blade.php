@extends('layouts.app')


@push('custom_css')
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chat/css/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chat/css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chat/css/line-awesome.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chat/css/line-awesome-font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chat/css/font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chat/css/jquery.mCustomScrollbar.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chat/lib/slick/slick.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chat/lib/slick/slick-theme.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chat/css/style.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chat/css/responsive.css')}}">
@endpush
@section('content')
<?php

$current_user = \Auth::user();

$toUser = $thread->Sender($current_user->id);
?>


<section class="messages-page">
	<div class="container">
		<div class="messages-sec">
			<div class="row">
				<div class="col-lg-4 col-md-12 no-pdd">
					<div class="msgs-list">
						<div class="msg-title">
							<h3>Messages</h3>
						</div>
						<div class="messages-list">
							<ul>
								@foreach($user->participants as $p)
								<a href="{{ route('my-chat',['id'=>$p->thread->id])}}">
								<li class="<?php echo ($p->thread->id == $thread->id? 'active':'') ?>">
									<div class="usr-msg-details">
										<div class="usr-ms-img">
											<img src="{{ $p->thread->sender($current_user->id)->avatar_path }}" alt="">
											<span class="msg-status"></span>
										</div>
										<div class="usr-mg-info">
											<h3>{{ $p->thread->Sender($current_user->id)->name }}</h3>
											<p><img src="images/smley.png" alt=""></p>
										</div><!--usr-mg-info end-->
										<span class="posted_time">1:55 PM</span>
										<span class="msg-notifc">1</span>
									</div><!--usr-msg-details end-->
								</li>
								</a>
								@endforeach
							</ul>
						</div>
					</div>
					
				</div>
				<div class="col-lg-8 col-md-12 pd-right-none pd-left-none">
					<div class="main-conversation-box">
						<div class="message-bar-head">
							<div class="usr-msg-details">
								<div class="usr-ms-img">
									<img src="{{ $thread->sender($current_user->id)->avatar_path }}" alt="">
								</div>
								<div class="usr-mg-info">
									<h3>{{ $thread->sender($current_user->id)->name }}</h3>
									<p>Online</p>
								</div><!--usr-mg-info end-->
							</div>
							<a href="#" title=""><i class="fa fa-ellipsis-v"></i></a>
						</div>
						<div class="messages-line" id="box">
							@foreach($thread->messages as $t)
								@include('chatbox',['t'=>$t,'user_id'=>$current_user->id])
							@endforeach
						</div>
						<div class="message-send-area">
									<form>
										<div class="mf-field">
											<input id="my-content" placeholder="Type a message here">
											<button id="send" data-thread="{{$thread->id}}" type="submit">Send</button>
										</div>
										<ul>
											<li><a href="#" title=""><i class="fa fa-smile-o"></i></a></li>
											<li><a href="#" title=""><i class="fa fa-camera"></i></a></li>
											<li><a href="#" title=""><i class="fa fa-paperclip"></i></a></li>
										</ul>
									</form>
								</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</section>
<div class="row">
	
	<div class="col-md-4">
		@foreach($user->participants as $p)

			<a>{{ $p->thread->sender($current_user->id)->name }}</a>
		@endforeach

	</div>
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-12" id="boxx">
				
				@foreach($thread->messages as $t)

					
				@endforeach
			</div>	
			<div class="col-md-12">
				<textarea id="amy-content"></textarea>
				<button id="asend" data-thread="{{$thread->id}}">Send</button>
			</div>
		</div>
		
	</div>
</div>
@endsection


@push('scripts')
<script type="text/javascript" src="{{ asset('/plugins/chat/js/popper.js')}}"></script>
<script type="text/javascript" src="{{ asset('/plugins/chat/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/plugins/chat/js/jquery.mCustomScrollbar.js')}}"></script>
<script type="text/javascript" src="{{ asset('/plugins/chat/lib/slick/slick.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/plugins/chat/js/scrollbar.js')}}"></script>
<script type="text/javascript" src="{{ asset('/plugins/chat/js/script.js')}}"></script>
<script src="https://js.pusher.com/4.1/pusher.min.js"></script>
<script>
	  $("#box").mCustomScrollbar({
      	 theme:"dark",
      	alwaysShowScrollbar:1
      });

	 var pusher = new Pusher('a67405a003f557ce006c', {
      cluster: 'us2',
      encrypted: true
    });
	 var mychanel = 'user-{{$current_user->id}}';
	 var channel = pusher.subscribe(mychanel);
     channel.bind('MessageSent', function(data) {
      console.log(data);
      	$.post('/api',{_token:'{{ csrf_token() }}',type:'create_mess_box',mess:data.id},function(data){
      		$('#mCSB_1_container').append(data);

			
			$('#box').mCustomScrollbar("scrollTo","bottom","bottom");
      	});
    });
	
    $(document).keypress(function(e) {
    	if(e.which == 13) {
       		 document.getElementById("#send").click();
    	}
	});
      
	function sendMess(thread){

		var to = thread;
		var content = $('#my-content').val();
		$.post('/api',{_token:'{{ csrf_token() }}',type:'add',thread:to,content:content},function(data){

				$('#mCSB_1_container').append(data);

				$('#my-content').val('');
				$('#box').mCustomScrollbar("scrollTo","bottom","bottom");
				//$('#mCSB_1_container').scrollTop($('#mCSB_1_container')[0].scrollHeight);
		});


	};
	$('#send').click(function(e){
		e.preventDefault();
	    var to = $(this).attr('data-thread');
		var content = $('#my-content').val();
		$.post('/api',{_token:'{{ csrf_token() }}',type:'add',thread:to,content:content},function(data){

				$('#mCSB_1_container').append(data);

				$('#my-content').val('');
				$('#box').mCustomScrollbar("scrollTo","bottom","bottom");
				//$('#mCSB_1_container').scrollTop($('#mCSB_1_container')[0].scrollHeight);
		});
	});
</script>
@endpush