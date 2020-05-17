require('./bootstrap');
const moment = require('moment');
moment.locale('ar');
// require('./moment.min');
/*require('./toastr.min');*/

let roomId = null;
let isRoomsPressed = false;
let roomNumber = null;
let docHeight = null;
let users = [];
let id = $('meta[name=id]').attr('content');
let nick_name = $('meta[name=nick_name]').attr('content');
let name_color = $('meta[name=name_color]').attr('content');

let can_make_private_chat = $('meta[name=can_make_private_chat]').attr('content');
let is_room_supervisor = $('meta[name=is_room_supervisor]').attr('content');
let is_supervisor = $('meta[name=is_supervisor]').attr('content');
var arr = []; // List of users	in popup
/*console.log(typeof is_supervisor);
console.log(typeof can_make_private_chat);*/
let conn = new WebSocket('ws://127.0.0.1:8882');

conn.onopen = function (e) {
    console.log("onOpen");
};
conn.onclose = function (e) {
    console.log("onClose");
};
conn.onerror = function (e) {
    console.log("onError");
};
conn.onmessage = function (e) {
    let data = JSON.parse(e.data);
    let dataType = data.type;
// console.log("onMessage " + dataType);
    if (dataType === 'userSettings') {
        $('#user_settings_model').modal({
            show: false,
        });
        // toastr.success(data.data, 'تم', {timeOut: 5000});
        return false;
    }
    if (dataType === 'room_messages') {
        // console.log(JSON.parse(data.data));
        // console.log(data);
        onRoomMessage(JSON.parse(data.data));
        return false;
    }

    if (dataType === 'room_chat') {
        onRoomMessage(data, false);
        return false;
    }

    if (dataType === 'room_users') {
        // console.log(data.testData);
        // console.log(data.data);
        onRoomUsers(data.data);
        return false;
    }
    if (dataType === 'subscribe_notif') {
        //console.log(data.data);
        onSubscribe(data);
        return false;
    }
    if (dataType === 'un_subscribe_notif') {
        onSubscribe(data, true);
        return false;
    }

    if (dataType === 'privateMessage') {
        privateMessage(data.data);
        return false;
    }
};

function privateMessage(_data) {
    let userID = _data.userID;
    let message = _data.message;
    let username = _data.username;
    let chatBox = $('[rel="user_' + userID + '"]');
    if (chatBox.attr('rel') !== "user_" + userID) {
        console.log('user presed: ' + userID);
        if ($.inArray(userID, arr) != -1) {
            arr.splice($.inArray(userID, arr), 1);
        }

        arr.unshift(userID);
        chatPopup = '<div class="msg_box" style="right:310px" rel="user_' + userID + '">' +
            '<div class="msg_head">' + username +
            '<div class="close">x</div> </div>' +
            '<div class="msg_wrap"> <div class="msg_body">' +
            `<div class="msg-left">${message}</div>` +
            '	<div class="msg_push"></div> </div>' +
            '<div class="msg_footer">' +
            '<div class="msg_input">\n' +
            '            <div class="wrap">\n' +
            '                    <input class="input_press" type="text" placeholder="إكتب...">\n' +
            '              \n' +
            '                <i class="fa fa-paperclip attachment" aria-hidden="true"></i>\n' +
            '                <button class="msg_input_submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>\n' +
            '            </div>\n' +
            '        </div>' +
            '</div> 	</div> 	</div>';

        $("body").append(chatPopup);
        displayChatBox();
    }

    console.log('message: ' + message + ' from: ' + userID);
    $(chatBox).find(".msg_body").append(`<div class="msg-left">${message}</div>`);
}

function onSubscribe(_data, _isUnSubscribe = false) {

    if (_isUnSubscribe) {
        const index = users.indexOf(_data.id);
        if (index > -1) {
            users.splice(index, 1);
        }
        $('#user_' + _data.id).remove();
        return;
    }

    if (users.includes(_data.id)) {
        return;
    } else {
        users.push(_data.id)
    }
    let i = _data.id;
    let item = _data;

    let cantWrite = (item.cantWrite === true) ? "إغلاق الكتم" : "كتم";
    let cantChangeColor = (item.cantChangeColor === true) ? "السماح بتغيير لون الاسم" : "عدم السماح بتغيير لون الاسم";
    let cantSendPrivateMessage = (item.cantSendPrivateMessage === true) ? "السماح بإرسال رسائل خاصة" : "عدم السماح بإرسال رسائل خاصة";
    let display = (isRoomsPressed === true) ? "none" : "block";
    // language=JQuery-CSS-HTML

    /*let private_chat = "";
    if (can_make_private_chat === "1" || is_supervisor === "1") {
        private_chat = `<a class="dropdown-item private_chat" href="#">إرسال رسالة خاصة</a>`;
    }
    let room_supervisor = "";
    if (is_room_supervisor === "1" || is_supervisor === "1") {
        room_supervisor = `
    <a class="dropdown-item my_modal_class block_message_send" href="#user_settings_model"  data-body="إختر مدة الكتم" data-title="إعدادات الكتم" data-role-id="1" data-user-id="${i}" data-room-id="${roomId}" data-toggle="modal">كتم</a>
    <a class="dropdown-item my_modal_class block_from_room" href="#user_settings_model" data-body="إختر مدة الطرد" data-title="إعدادات الطرد" data-role-id="2" data-user-id="${i}" data-room-id="${roomId}" data-toggle="modal">طرد من الغرفة</a>
    <a class="dropdown-item change_color"  id="${i}" href="#" >السماح بتغيير اللون</a>
    <a class="dropdown-item private_message" id="${i}" href="#">عدم السماح بإرسال رسائل خاصة</a>`;

    }
    let supervisor = "";
    if (is_supervisor === "1") {
        supervisor = `<a class="dropdown-item stop_account" id="${i}" href="#" >إيقاف الحساب</a>`;

    }*/


    //alert(item.PageName);
    // language=JQuery-CSS-HTML
    $("#user_room_sec").append(`<div class="dropdown users" id="user_${i}" style="display: ${display}" >
    <li class="dropdown-toggle contact" type="li" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">

        <div class="wrap">
            <span class="contact-status online"></span>
            <img style="height: 40px"
                 src="//previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"
                 alt=""/>
            <div class="meta">
                <p class="name">${_data.nick_name}</p>
                <p class="preview">متصل</p>
            </div>
        </div>

    </li>
    <div style="margin-top: -26%;" class="dropdown-menu" aria-labelledby="dropdownMenuButton">

        ${(can_make_private_chat === "1" || is_supervisor === "1") ?
        `<a class="dropdown-item private_chat" id="${i}" href="#">إرسال رسالة خاصة</a>` : ``}
        ${(is_room_supervisor === "1" || is_supervisor === "1") ?
        `
    <a class="dropdown-item my_modal_class block_message_send" id="${i}" href="#user_settings_model"  data-body="إختر مدة الكتم" data-title="إعدادات الكتم" data-role-id="1" data-user-id="${i}" data-room-id="${roomId}" data-toggle="modal">${cantWrite}</a>
    <a class="dropdown-item my_modal_class block_from_room" href="#user_settings_model" data-body="إختر مدة الطرد" data-title="إعدادات الطرد" data-role-id="2" data-user-id="${i}" data-room-id="${roomId}" data-toggle="modal">طرد من الغرفة</a>
    <a class="dropdown-item change_color"  id="${i}" href="#" >${cantChangeColor}</a>
    <a class="dropdown-item private_message" id="${i}" href="#">${cantSendPrivateMessage}</a>` : ``}
        ${(is_supervisor === "1") ?
        supervisor = `<a class="dropdown-item stop_account" id="${i}" href="#" >إيقاف الحساب</a>` : ``}
    </div>
</div>



        `);
    /*$("#user_room_sec").append(
        `<div class="dropdown users" id="user_${i}" style="display: ${display}">
    <li class="dropdown-toggle contact" type="li" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">

        <div class="wrap">
            <span class="contact-status online"></span>
            <img style="height: 40px"
                 src="//previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"
                 alt=""/>
            <div class="meta">
                <p class="name">${_data.nick_name}</p>
                <p class="preview">متصل</p>
            </div>
        </div>

    </li>
    <div style="margin-top: -26%;" class="dropdown-menu" aria-labelledby="dropdownMenuButton">

        ${private_chat}
        ${room_supervisor}
        ${supervisor}
    </div>
</div>

        `);*/
    /* onClickAfterUserAppend()*/
}

function onRoomMessage(_data, _onOpenRoom = true) {
    if (!_onOpenRoom) {
        // language=JQuery-CSS-HTML
        console.log(_data);
        $("#message_box").append(`
            <li class="replies" >
                <img src="http://emilcarlsson.se/assets/mikeross.png" alt=""/>
                <span>
                    <p class="name" style=" font-size: 75%; margin: -7px 0 7px 0; color:${_data.name}"> ${_data.nick_name} </p>
                    <p style="word-wrap: break-word;">${_data.message}</p>
                    <p class="message-time" style=" font-size: 75%; /* color: wheat; */ margin: 0 0 -5px 0; text-align: right; ">${moment(_data.created_at).format('DD-MM-YYYY HH:mm:ss a')}</p>
                </span>
            </li>`);
        $(".messages").animate({scrollTop: docHeight + 93}, "fast");

        docHeight += 93;
        return;
    }
    $.each(_data, function (i, item) {
        let className = "replies";

        if (id === item.user_id + "".toString()) {
            className = "sent";
        }

        //alert(item.PageName);
        // language=JQuery-CSS-HTML
        $("#message_box").append(`
            <li class="${className}">
                <img src="http://emilcarlsson.se/assets/mikeross.png" alt=""/>
                <span>
                    <p class="name" style=" font-size: 75%; margin: -7px 0 7px 0; color:${item.user.name}"> ${item.user.nick_name} </p>
                    <p style="word-wrap: break-word;">${item.message}</p>
                    <p class="message-time" style=" font-size: 75%; /* color: wheat; */ margin: 0 0 -5px 0; text-align: right; ">${moment(item.created_at).format('DD-MM-YYYY HH:mm:ss a')}</p>
                </span>
            </li>`);

        docHeight += 93;

    });
    $(".messages").animate({scrollTop: docHeight + 93}, "fast");
}

function onRoomUsers(_data, _onOpenRoom = true) {

    if (!_onOpenRoom) {
        // language=JQuery-CSS-HTML
        $("#user_room_sec").append(`
                    <li class="contact users"  id="user_${i}">

                        <div class="wrap">
                            <span class="contact-status online"></span>
                            <img style="height: 40px"
                                 src="//previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"
                                 alt=""/>
                            <div class="meta">
                                <p class="name">${item.userName}</p>
                                <p class="preview">متصل</p>
                            </div>
                        </div>
                    </li>
        `);
    }

    $.each(_data, function (i, item) {
        // console.log(i);
        // console.log(item);
        // console.log(users);
        // console.log(id);
        if (users.includes(i)) {
            return;
        } else {
            users.push(i)
        }
        if (id === i) {
            if (item.cantWrite === true) {
                $('.message-input .wrap').html(`<input type="text" placeholder="تم تعليقك من الكتابة مؤقتا..." readonly>`);
            }
            if (item.cantChangeColor === true) {
                $('#changeNameColorDiv').remove();
            }
            can_make_private_chat = !item.cantSendPrivateMessage;
            return;
        }
        cantWrite = (item.cantWrite === true) ? "إغلاق الكتم" : "كتم";
        cantChangeColor = (item.cantChangeColor === true) ? "السماح بتغيير لون الاسم" : "عدم السماح بتغيير لون الاسم";
        cantSendPrivateMessage = (item.cantSendPrivateMessage === true) ? "السماح بإرسال رسائل خاصة" : "عدم السماح بإرسال رسائل خاصة";
        display = (isRoomsPressed === true) ? "none" : "block";

        /*let private_chat = "";
        if (can_make_private_chat === "1" || is_supervisor === "1") {
            private_chat = `<a class="dropdown-item private_chat" id="${i}" href="#">إرسال رسالة خاصة</a>`;
        }*/
        //     let room_supervisor = "";
        //     if (is_room_supervisor === "1" || is_supervisor === "1") {
        //         room_supervisor = `
        // <a class="dropdown-item my_modal_class block_message_send" id="${i}" href="#user_settings_model"  data-body="إختر مدة الكتم" data-title="إعدادات الكتم" data-role-id="1" data-user-id="${i}" data-room-id="${roomId}" data-toggle="modal">${cantWrite}</a>
        // <a class="dropdown-item my_modal_class block_from_room" href="#user_settings_model" data-body="إختر مدة الطرد" data-title="إعدادات الطرد" data-role-id="2" data-user-id="${i}" data-room-id="${roomId}" data-toggle="modal">طرد من الغرفة</a>
        // <a class="dropdown-item change_color"  id="${i}" href="#" >${cantChangeColor}</a>
        // <a class="dropdown-item private_message" id="${i}" href="#">${cantSendPrivateMessage}</a>`;
        //
        //     }
        //     let supervisor = "";
        //     if (is_supervisor === "1") {
        //         supervisor = `<a class="dropdown-item stop_account" id="${i}" href="#" >إيقاف الحساب</a>`;
        //
        //     }


        //alert(item.PageName);
        // language=JQuery-CSS-HTML
        $("#user_room_sec").append(`<div class="dropdown users" id="user_${i}" style="display: ${display}" >
    <li class="dropdown-toggle contact" type="li" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">

        <div class="wrap">
            <span class="contact-status online"></span>
            <img style="height: 40px"
                 src="//previews.123rf.com/images/littleartvector/littleartvector1901/littleartvector190100024/126067615-interior-background-with-cozy-colorful-living-room-vector-illustration.jpg"
                 alt=""/>
            <div class="meta">
                <p class="name">${item.userName}</p>
                <p class="preview">متصل</p>
            </div>
        </div>

    </li>
    <div style="margin-top: -26%;" class="dropdown-menu" aria-labelledby="dropdownMenuButton">

        ${(can_make_private_chat === "1" || is_supervisor === "1") ?
            `<a class="dropdown-item private_chat" id="${i}" href="#">إرسال رسالة خاصة</a>` : ``}
        ${(is_room_supervisor === "1" || is_supervisor === "1") ?
            `
    <a class="dropdown-item my_modal_class block_message_send" id="${i}" href="#user_settings_model"  data-body="إختر مدة الكتم" data-title="إعدادات الكتم" data-role-id="1" data-user-id="${i}" data-room-id="${roomId}" data-toggle="modal">${cantWrite}</a>
    <a class="dropdown-item my_modal_class block_from_room" href="#user_settings_model" data-body="إختر مدة الطرد" data-title="إعدادات الطرد" data-role-id="2" data-user-id="${i}" data-room-id="${roomId}" data-toggle="modal">طرد من الغرفة</a>
    <a class="dropdown-item change_color"  id="${i}" href="#" >${cantChangeColor}</a>
    <a class="dropdown-item private_message" id="${i}" href="#">${cantSendPrivateMessage}</a>` : ``}
        ${(is_supervisor === "1") ?
            supervisor = `<a class="dropdown-item stop_account" id="${i}" href="#" >إيقاف الحساب</a>` : ``}
    </div>
</div>



        `);


    });
    onClickAfterUserAppend();
    popup_chat();
}

$(document).ready(function () {
    docHeight = $(document).height()
    // console.log( "ready!" );

    /*roomId = $(".room_id").first().html();
    roomNumber = $(".room_number").first().html();
    conn.send(JSON.stringify({command: "subscribe", channel: roomId, roomId: roomNumber}));*/

    //console.log(roomId);
});

$(".rooms").click(function () {
    $("#message_box").empty();
    users = [];
    $(".users").remove();
    let roomName = $(this).find(".room_name").html();
    let roomAvatar = $(this).find(".room_avatar").html();
    $('#room_name').text(roomName);
    $('#room_img').attr("src", roomAvatar);
    roomId = $(this).find(".room_id").html();
    console.log(roomId);
    roomNumber = $(this).find(".room_number").html();
    conn.send(JSON.stringify({command: "subscribe", channel: roomId, roomId: roomNumber}));
    //$("#status-options").toggleClass("active");
});


$("#profile-img").click(function () {
    $("#status-options").toggleClass("active");
});

$(".expand-button").click(function () {
    $("#profile").toggleClass("expanded");
    $("#contacts").toggleClass("expanded");
});

$("#status-options ul li").click(function () {
    $("#profile-img").removeClass();
    $("#status-online").removeClass("active");
    $("#status-away").removeClass("active");
    $("#status-busy").removeClass("active");
    $("#status-offline").removeClass("active");
    $(this).addClass("active");

    if ($("#status-online").hasClass("active")) {
        $("#profile-img").addClass("online");
    } else if ($("#status-away").hasClass("active")) {
        $("#profile-img").addClass("away");
    } else if ($("#status-busy").hasClass("active")) {
        $("#profile-img").addClass("busy");
    } else if ($("#status-offline").hasClass("active")) {
        $("#profile-img").addClass("offline");
    } else {
        $("#profile-img").removeClass();
    }
    ;

    $("#status-options").removeClass("active");
});


function newMessage() {

    message = $(".message-input input").val();
    name_color = $('meta[name=name_color]').attr('content');

    // let msgTime = new Date().toLocaleString('ar-EG', { year:'numeric', month:'numeric', day:'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });
    // var date = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getFullYear();
    // var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    // var dateTime = time + ' ' + date;
    // let dateTime = today.toLocaleString('ar-EG', { year:'numeric', month:'numeric', day:'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });
    if ($.trim(message) == '') {

        return false;

    }

    $("#message_box").append(`
        <li class="sent" >\n\n
            <img src="http://emilcarlsson.se/assets/mikeross.png" alt=""/>\n                    \n
            <span>
                <p class="name" style=" font-size: 75%; margin: -7px 0 7px 0; color:${name_color}"> ${nick_name} </p>
                <p style="word-wrap: break-word;">\n                  \n                    ${message}\n                    </p>
                <p class="message-time" style="
    font-size: 75%;
    /* color: wheat; */
    margin: 0 0 -5px 0;
    text-align: right;
">${moment().format('DD-MM-YYYY HH:mm:ss a')}</p>
            </span>\n
        </li>`);

    $('.message-input input').val(null);

    $('.contact.active .preview').html('<span>You: </span>' + message);

    $(".messages").animate({scrollTop: docHeight + 93}, "fast");

    docHeight += 93;

    conn.send(JSON.stringify({command: "groupchat", message: message, channel: roomId, roomId: roomNumber}));
}

$('.submit').click(function () {
    newMessage();
});

$(window).on('keydown', function (e) {
    if (e.which == 13) {
        newMessage();
        return false;
    }
});

$('.Button').click(function (e) {
    $('.Button').not(this).removeClass('active');
    $(this).toggleClass('active');
    e.preventDefault();
});

$('#rooms').click(function (e) {
    isRoomsPressed = true;
    console.log('rooms cliced');
    $('.rooms').show();
    $('.users').hide();
    $('#edit-form').hide();
    $('#search').show();
});
$('#users').click(function (e) {
    isRoomsPressed = false;
    console.log('users cliced');
    $('.rooms').hide();
    $('.users').show();
    $('#edit-form').hide();
    $('#search').show();
});

$('#settings').click(function (e) {
    console.log('users cliced');
    $('.rooms').hide();
    $('.users').hide();
    $('#edit-form').show();
    $('#search').hide();
});
$(window).on('load', function () {
    $('#myModal').modal({
        backdrop: false,
    });
    $('#frame').show();
});

$('#user_settings_model').on('show.bs.modal', function (e) {

    //get data-id attribute of the clicked element
    let userId = $(e.relatedTarget).data('user-id');
    //let roomId = $(e.relatedTarget).data('room-id');
    let roleId = $(e.relatedTarget).data('role-id');
    let title = $(e.relatedTarget).data('title');
    let body = $(e.relatedTarget).data('body');

    //populate the textbox
    $(e.currentTarget).find('input[name="userId"]').val(userId);
    $(e.currentTarget).find('input[name="roomId"]').val(roomNumber);
    $(e.currentTarget).find('input[name="roleId"]').val(roleId);
    $(e.currentTarget).find('#title').html(title);
    $(e.currentTarget).find('#body').html(body);
});

$('#user_settings_edit_form').submit(function (e) {
    e.preventDefault();
    let data = $('#user_settings_edit_form').serializeArray();
    let userId = data[0]['value'];
    let roomId = data[1]['value'];
    let roleId = data[2]['value'];
    let blockTime = data[3]['value'];
    // console.log(userId);
    switch (roleId) {
        case '1':
            text = $('#user_' + userId).find('.block_message_send').html();
            if (text === 'كتم') {
                $('#user_' + data[0]['value']).find('.block_message_send').html('إغلاق الكتم');
                $('#user_' + data[0]['value']).find('.block_message_send').attr('href', '#');

            } else {
                $('#user_' + data[0]['value']).find('.block_message_send').html('كتم');
            }
            // console.log($('#user_' + data[0]['value']).find('.block_message_send'));
            updateUserRole(userId,2);
            break;
        case '2':
            updateUserRole(userId,100,roomId,blockTime);
            $('#user_' + userId).remove();
            break;
    }

    $('#user_settings_model').modal('hide');

    conn.send(JSON.stringify({
        command: "userSettings",
        userId: userId,
        roleId: data[2]['value'],
        blockTime: data[3]['value'],
        channel: roomId,
        roomId: roomNumber
    }));
});

function newMessagePopup(_this) {
    let message = $(_this).find(".input_press").val();
    if ($.trim(message) == '') {
        return false;
    }
    let userIdReceiver = $(_this).attr('rel').replace('user_', '');
    console.log('message: ' + message + 'to: ' + userIdReceiver + ' from: ' + id);
    conn.send(JSON.stringify({command: "privateMessage", roomId: roomId, to: userIdReceiver, message: message}));
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

function onclick() {
    $('.msg_input_submit').click(function () {
        let msg_box = $(this).parents().find('.msg_box')[0];
        newMessagePopup(msg_box);
        return false;
    });

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


function displayChatBox() {
    i = 270; // start position
    j = 260;  //next position

    $.each(arr, function (index, value) {
        if (index < 4) {
            $('[rel="' + value + '"]').css("right", i);
            $('[rel="' + value + '"]').show();
            i = i + j;
        } else {
            $('[rel="' + value + '"]').hide();
        }
    });
    onclick();
}

function onClickAfterUserAppend() {
    $(".block_message_send").click(function () {
        if ($(this).html() !== 'كتم') {
            $(this).attr('href', '#user_settings_model');
            $(this).html('كتم');
            let userId = $(this).attr('id');

            conn.send(JSON.stringify({
                command: "userSettings",
                userId: userId,
                roleId: 1,
                channel: roomId,
                roomId: roomNumber
            }));
            return false;
        }


        //$("#status-options").toggleClass("active");
    });

    $(".change_color").click(function () {
        let userId = $(this).attr('id');

        updateUserRole(userId,3);

        conn.send(JSON.stringify({
            command: "userSettings",
            userId: userId,
            roleId: 3,
            channel: roomId,
            roomId: roomNumber
        }));
        //$("#status-options").toggleClass("active");
    });

    $(".private_message").click(function () {
        let userId = $(this).attr('id');

        updateUserRole(userId,5);

        conn.send(JSON.stringify({
            command: "userSettings",
            userId: userId,
            roleId: 4,
            channel: roomId,
            roomId: roomNumber
        }));
        //$("#status-options").toggleClass("active");
    });

    $(".stop_account").click(function () {
        let userId = $(this).attr('id');

        updateUserRole(userId,1);

        conn.send(JSON.stringify({
            command: "userSettings",
            userId: userId,
            roleId: 5,
            channel: roomId,
            roomId: roomNumber
        }));
        //$("#status-options").toggleClass("active");
    });

}

function popup_chat() {
    console.log('ready!');


    $(document).on('click', '.msg_head', function () {
        // console.log('box clicked')
        let chatBox = $(this).parents().attr("rel");
        // console.log(chatBox)
        let currentChatBox = $('[rel="' + chatBox + '"] .msg_wrap');
        // console.log(currentChatBox)
        currentChatBox.slideToggle();
        console.log(currentChatBox)
        return false;
    });


    $(document).on('click', '.close', function () {
        var chatbox = $(this).parents().parents().attr("rel");
        $('[rel="' + chatbox + '"]').hide();
        arr.splice($.inArray(chatbox, arr), 1);
        displayChatBox();
        return false;
    });

    $(".users").click(function () {

        let userID = $(this).attr("id");
        let username = $(this).find(".name").html();

        $(this).find(".private_chat").click(function () {


            console.log('user presed: ' + userID);
            if ($.inArray(userID, arr) != -1) {
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
            chatPopup = '<div class="msg_box" style="right:310px" rel="' + userID + '">' +
                '<div class="msg_head">' + username +
                '<div class="close">x</div> </div>' +
                '<div class="msg_wrap"> <div class="msg_body">	<div class="msg_push"></div> </div>' +
                '<div class="msg_footer">' +
                '<div class="msg_input">\n' +
                '            <div class="wrap">\n' +
                '                    <input class="input_press" type="text" placeholder="إكتب...">\n' +
                '              \n' +
                '                <i class="fa fa-paperclip attachment" aria-hidden="true"></i>\n' +
                '                <button class="msg_input_submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>\n' +
                '            </div>\n' +
                '        </div>' +
                '</div> 	</div> 	</div>';

            $("body").append(chatPopup);
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


}

