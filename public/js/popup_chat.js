$(".rooms").click(function () {
     console.log('ready!');
	 var arr = []; // List of users	
	
	$(document).on('click', '.msg_head', function() {	
		var chatbox = $(this).parents().attr("rel") ;
		$('[rel="'+chatbox+'"] .msg_wrap').slideToggle('slow');
		return false;
	});
	
	
	$(document).on('click', '.close', function() {	
		var chatbox = $(this).parents().parents().attr("rel") ;
		$('[rel="'+chatbox+'"]').hide();
		arr.splice($.inArray(chatbox, arr), 1);
		displayChatBox();
		return false;
	});

	$(".users").click(function () {

		let userID = $(this).attr("id");
		let username = $(this).find(".name").html();

		$(this).find(".private_chat").click(function () {


		console.log('user presed: '+userID);
		if ($.inArray(userID, arr) != -1)
		{
			arr.splice($.inArray(userID, arr), 1);
		}

		/*<div class="msg_input">
            <div class="wrap">
                    <input type="text" placeholder="إكتب...">

                <i class="fa fa-paperclip attachment" aria-hidden="true"></i>
                <button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            </div>
        </div>*/
		arr.unshift(userID);
		chatPopup =  '<div class="msg_box" style="right:310px" rel="'+ userID+'">'+
			'<div class="msg_head">'+username +
			'<div class="close">x</div> </div>'+
			'<div class="msg_wrap"> <div class="msg_body">	<div class="msg_push"></div> </div>'+
			'<div class="msg_footer">' +
			'<div class="msg_input">\n' +
			'            <div class="wrap">\n' +
			'                    <input class="input_press" type="text" placeholder="إكتب...">\n' +
			'              \n' +
			'                <i class="fa fa-paperclip attachment" aria-hidden="true"></i>\n' +
			'                <button class="msg_input_submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>\n' +
			'            </div>\n' +
			'        </div>' +
			'</div> 	</div> 	</div>' ;

		$("body").append(  chatPopup  );
		displayChatBox();
		});
	});
	/*$(document).on('click', '#user_box', function() {
	
	 var userID = $(this).attr("id").split(' ')[0];
	 var username = $(this).children().text() ;
	 
	 if ($.inArray(userID, arr) != -1)
	 {
      arr.splice($.inArray(userID, arr), 1);
     }
	 
	 arr.unshift(userID);
	 chatPopup =  '<div class="msg_box" style="right:270px" rel="'+ userID+'">'+
					'<div class="msg_head">'+username +
					'<div class="close">x</div> </div>'+
					'<div class="msg_wrap"> <div class="msg_body">	<div class="msg_push"></div> </div>'+
					'<div class="msg_footer"><textarea class="msg_input" rows="4"></textarea></div> 	</div> 	</div>' ;					
				
     $("body").append(  chatPopup  );
	 displayChatBox();
	});*/
	function newMessagePopup(_this) {

		console.log('message');
		let message = $(_this).find(".input_press").val();
		console.log('message: '+ message);
		if ($.trim(message) == '') {

			return false;

		}

		$(_this).find(".msg_body").append(`<div class="msg-right">${message}</div>`);

		$(_this).find('.input_press').val(null);

		// $('.contact.active .preview').html('<span>You: </span>' + message);
		//
		// $(".messages").animate({scrollTop: docHeight + 93}, "fast");
		//
		// docHeight += 93;
		//
		// conn.send(JSON.stringify({command: "groupchat", message: message, channel: roomId, roomId: roomNumber}));
	}

	function onclick()
	{
		/*$('.msg_input_submit').click(function () {
			newMessagePopup();
		});*/

		$('.msg_box').on('keydown', function (e) {
			if (e.which == 13) {
				newMessagePopup(this);
				return false;
			}
		});
	}


	/*$('.input_press').keypress(
		function(e){

			if (e.keyCode == 13) {
				//var msg = $(this).val();
				let msg = $(".msg_input input").val();
				$(this).val('');
				if(msg.trim().length != 0){
					var chatbox = $(this).parents().parents().parents().attr("rel") ;
					$('<div class="msg-right">'+msg+'</div>').insertBefore('[rel="'+chatbox+'"] .msg_push');
					$('.msg_body').scrollTop($('.msg_body')[0].scrollHeight);
				}
			}
		});*/
	
		
    
	function displayChatBox(){ 
	    i = 270 ; // start position
		j = 260;  //next position
		
		$.each( arr, function( index, value ) {  
		   if(index < 4){
	         $('[rel="'+value+'"]').css("right",i);
			 $('[rel="'+value+'"]').show();
		     i = i+j;			 
		   }
		   else{
			 $('[rel="'+value+'"]').hide();
		   }
        });
		onclick();
	}
	
	
	
	
});