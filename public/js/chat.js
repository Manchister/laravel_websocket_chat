
/*window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});*/

/*
window.Echo.join(`online`)
    .here((users) => {
        //console.log(users);
        users.forEach(function (user) {
            if (userId == user.id)return;
            $('#online_users').append(`<div class="chat_list active_chat"  id="user-${user.id}"><div class="chat_people">
                                    <div class="chat_img"><img src="https://ptetutorials.com/images/user-profile.png"
                                                               alt="sunil"></div>
                                    <div class="chat_ib">
                                        <h2>${user.name}</h2>
                                    </div>
                                </div></div>`);
        })
    })
    .joining((user) => {
        $('#online_users').append(`<div class="chat_list active_chat"  id="user-${user.id}"><div class="chat_people">
                                    <div class="chat_img"><img src="https://ptetutorials.com/images/user-profile.png"
                                                               alt="sunil"></div>
                                    <div class="chat_ib">
                                        <h2>${user.name}</h2>
                                    </div>
                                </div></div>`);
    })
    .leaving((user) => {
        $(`#user-${user.id}`).remove()
    });*/
