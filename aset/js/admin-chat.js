$(document).ready(function() {
    let http   = (window.location.protocol === 'http:') ? "http://" : "https://";
        socket = io(http + window.location.hostname + ":3000");

    let click = "click",
        aktif = "aktif";

    let color = {
        red     : "#e74c3c",
        yellow  : "#f1c40f",
        green   : "#2ecc71",
        blue    : "#3498db"
    }; 
    socket.on("send", function(data) {
        let id_admin = $('.__admin').attr('id-admin'),
            url      = $('.__admin').attr('url-chat'),
            chat     = $('.__admin .__right .__chat'),
            id_user  = chat.attr('id-user'); 
        let dt_admin = data.to,
            dt_user  = data.from,
            type     = data.type,
            name     = data.nameUser,
            photo    = data.photo,
            date     = data.date,
            message  = data.msg;
        if(type === 'start')
        {
            if(parseInt(id_admin) === parseInt(dt_admin))
            {
                let elem = '<a id-user="' + dt_user + '" class="__open-chat" href="' + url + dt_user + '">'+
                                '<div class="__contact">' +
                                    '<div class="img"><img src="' + photo + '"></div>' +
                                    '<div class="__name">' + name + '</div>' +
                                    '<div class="__info"></div>' +
                                '</div>';
                $('.__admin .__left .__inner-body').prepend(elem);
            }
        }
        else if(type === 'chat')
        {
            if(parseInt(id_admin) === parseInt(dt_admin) && parseInt(id_user) === parseInt(dt_user) && data.msg_for === 1)
            {
                chat.find(".__wrap-chat").append(appendChat(data));

                focusLast(chat.find(".__wrap-chat"));
            }
        }
    });

    socket.on("finish", function(msg) {
        let container = $(".__admin .__right .__inner-body"),
            chat      = container.find(".__chat"),
            form      = container.find(".form-send"),
            officer   = $(".__admin");
        
        let id_admin    = msg.admin,
            id_user     = msg.user,
            attr_admin  = officer.attr("id-admin"),
            attr_user   = chat.attr("id-user"),
            status      = msg.status;
        let info        = "";
        
        // user end the chatUser already has sending a rated
        if      (status === 2) info = '<div class="__msg-error">User end the chat.</div>';
        else if (status === 5) info = '<div class="__msg-success">User already has sending a rated.</div>';

        if(parseInt(id_admin) === parseInt(attr_admin) && parseInt(id_user) === parseInt(attr_user))
        {
            form.html(info);
        }

    });
   
    socket.on("isTyping", function(data) {
        let container = $(".__admin .__left .__inner-body")
            id_admin  = $('.__admin').attr('id-admin'),
            contact   = container.find(".__open-chat");
        for(let i = 0; i < contact.length; i++)
        {
            let ini     = contact.eq(i),
                id_user = ini.attr('id-user');
            // set text is typing
            if(id_user === data.user && id_admin === data.admin && data.onTyping && data.typingFor === 1)
            {
                ini.find('.__info').html("User is typing...");
            }
            // remove text is typing
            if(id_user === data.user && id_admin === data.admin && !data.onTyping && data.typingFor === 1 && id_user === data.replaceStat)
            {
                ini.find('.__info').html("");
            }
        }
        console.log(data);
    });


    $(document).on('keyup', '.__send-input', function (e) { 
        let event  = e || window.event,
            key    = event.which || event.keyCode,
            ini    = $(this),
            form   = ini.parents('form');
        let msg = 
        {
            onTyping : false,
            typingFor: 2, // for user
            user     : ini.attr("id-user"),
            admin    : ini.attr('id-admin'),
            replaceStat:""
        }
        if (key === 13 && !event.shiftKey && ini.val().trim().length !== 0) //same as "ENTER" and "SHIFT"
        { 
            e.preventDefault();
            msg.onTyping = false;
            msg.replaceStat = ini.attr('id-user');
            $(form).trigger("submit");
        }
        // send to officer WebChat that user is on Typing
        if(ini.val().trim().length !== 0) 
        {
            msg.onTyping    = true;
            msg.replaceStat = "";
        }
        else
        {
            msg.onTyping    = false;
            msg.replaceStat = ini.attr('id-user');
        }
        socket.emit("isTyping", msg);
    });

    // open chat by contact
    $(document).on(click, ".__open-chat", function(x) {
        x.preventDefault();
        let ini       = $(this),
            url       = ini.attr('href'),
            id_user   = ini.attr('id-user'),
            chat     = $('.__admin .__right');
        $.ajax({
            url    : url,
            type   : "POST",
            success: function(data) 
            {
                let json      = JSON.parse(data),
                    body_chat = $(".__right .__inner-body"),
                    menu_del  = $(".__top .__menu.delete");

                body_chat.html(json.view);
                menu_del.attr('id-user', id_user);

                focusLast(chat.find(".__chat .__wrap-chat"));
            }
        });
    });

    // menu top
    $(document).on(click, 'a.__menu', function(x) {
        x.preventDefault();
        let ini             = $(this),
            cate            = ini.attr('data-menu'),
            url             = ini.attr('href'),
            account_body    = $('.__account'),
            top_menu        = $(".__admin .__top");

        if      (cate === 'delete')
        {
            let id_user = ini.attr('id-user');
            if(id_user.length !== 0)
            {
                if(confirm("Do you really want to delete this chat?"))
                {
                    $.ajax({
                        url : url + id_user,
                        type: "POST",
                        success: function(data)
                        {
                            let json = JSON.parse(data);
                            if(json.result)
                            {
                                alert(json.msg);
                                window.location.href = json.url;
                            }
                            else
                            {
                                alert(json.msg);
                            }
                        }
                    });
                }
            }
            else
            {
                alert("Please select User on the left side first.");
            }
        }
        else if (cate === 'account')
        {
            if(account_body.hasClass(aktif))
            {
                account_body.removeClass(aktif);
            }
            else
            {
                $.ajax({
                    url : url,
                    type: "POST",
                    success: function(data)
                    {
                        let json        = JSON.parse(data),
                            review      = account_body.find(".review-star"),
                            username    = account_body.find("#username"),
                            name        = account_body.find("#name"),
                            star_5      = review.find("#review-5"),
                            star_4      = review.find("#review-4"),
                            star_3      = review.find("#review-3"),
                            star_2      = review.find("#review-2"),
                            star_1      = review.find("#review-1"),
                            color_star  = account_body.find(".__star .__star-color"),
                            rate_star   = account_body.find("#rate-star");
                            tot_user    = account_body.find('#total-user');
    
                        let s5      = parseInt(json.star_5),                    
                            s4      = parseInt(json.star_4),                    
                            s3      = parseInt(json.star_3),                    
                            s2      = parseInt(json.star_2),                    
                            s1      = parseInt(json.star_1);                    
                        // total user for admin which is related
                        let total   = s5 + s4 + s3 + s2 + s1;
    
                        let count_s5 = (s5 / total) * 100,
                            count_s4 = (s4 / total) * 100,
                            count_s3 = (s3 / total) * 100,
                            count_s2 = (s2 / total) * 100,
                            count_s1 = (s1 / total) * 100;
                        username.val(json.username);
                        name.val(json.name);
                        
                        account_body.addClass(aktif);
    
                        let avg     = ( 5 * s5 + 4 * s4 + s3 * 3 + s2 * 2 + s1 * 1) / total,
                            perc_avg= avg * 20;
                        
                            // rate out of 5 star
                        rate_star.html(avg.toFixed(1));

                        let color_aktif = "";
                        if      (perc_avg <= 20) { color_aktif = "red"; }
                        else if (perc_avg <= 40) { color_aktif = color.red; }
                        else if (perc_avg <= 60) { color_aktif = color.yellow; }
                        else if (perc_avg <= 80) { color_aktif = color.green; }
                        else                     { color_aktif = color.blue; }

                        // total of users
                        tot_user.html(total + " Users");

                        // tool-tip meter
                        star_5.find('.meter-user span').html("5 Star : " + s5 + " Users");
                        star_4.find('.meter-user span').html("4 Star : " + s4 + " Users");
                        star_3.find('.meter-user span').html("3 Star : " + s3 + " Users");
                        star_2.find('.meter-user span').html("2 Star : " + s2 + " Users");
                        star_1.find('.meter-user span').html("1 Star : " + s1 + " Users");

                        setTimeout(function() {

                            // background meter
                            star_5.find('.meter-value').css({background: color.blue,    width: count_s5.toFixed(1) + "%"});
                            star_4.find('.meter-value').css({background: color.green,   width: count_s4.toFixed(1) + "%"});
                            star_3.find('.meter-value').css({background: color.yellow,  width: count_s3.toFixed(1) + "%"});
                            star_2.find('.meter-value').css({background: color.red,     width: count_s2.toFixed(1) + "%"});
                            star_1.find('.meter-value').css({background: "red",         width: count_s1.toFixed(1) + "%"});
    
                            // value meter
                            star_5.find('.percent').html(count_s5.toFixed(1) + "%");
                            star_4.find('.percent').html(count_s4.toFixed(1) + "%");
                            star_3.find('.percent').html(count_s3.toFixed(1) + "%");
                            star_2.find('.percent').html(count_s2.toFixed(1) + "%");
                            star_1.find('.percent').html(count_s1.toFixed(1) + "%");

                            //background star
                            color_star.css({width: perc_avg + "%", background: color_aktif});

                        }, 500);
                    }
                });
            }
        }
        else if (cate === 'status')
        {
            let status = top_menu.find('#status-admin');
            $.ajax({
                url : url,
                type: "POST",
                beforeSend: function()
                {
                    status.html("Loading for your status...");
                },
                success: function(data)
                {
                    status.html(data);
                }
            });
        }
        else
        {
            window.location.href = url;
        }
    });

    //submit form send message
    $(document).on("submit", '#form-send', function(x) { 
        x.preventDefault();
        let ini     = $(this),
            input   = ini.find('.__send-input'),
            url     = ini.attr("action"),
            msg_info= ini.find('#msg-msg'),
            chat    = ini.parent().find(".__wrap-chat");
        $.ajax({
        url : url,
        type: "POST",
        data: ini.serialize(),
        beforeSend: function()
        {
            input.attr('disabled', true);
        },
        success: function(data)
        {
            let json = JSON.parse(data);
                // onTYping  = false
                let typing = 
                {
                    onTyping : false,
                    user     : json.user,
                    admin    : json.admin
                }
                socket.emit("isTyping", typing);

                input.attr('disabled', false);
                // form validation true
                if(json.result)
                {
                    // if result for chat is true
                    if(json.result_chat)
                    {
                        let msg     = 
                        {
                            from    : json.user,
                            to      : json.admin,
                            message : json.message,
                            photo   : json.photo,
                            type    : "chat",
                            msg_for : 2, //for user
                            nameAdmin: input.attr('name-admin'),
                            nameUser: input.attr('name-user'),
                            date    : json.date
                        };
                        socket.emit("send", msg);
                        // set value textarea to null / empty
                        input.val("");

                        // true is for user
                        chat.append(appendChat(msg, true)).focus();

                        input.focus();

                        focusLast(chat);
                        
                    }
                    else
                    {
                        ini.html(json.view);
                    }
                }
                else
                {
                    msg_info.html(json.msg);
                }
        } 
        });
    });
});

function focusLast(el)
{
    let chat        = el.find(".__user"),
        height      = 0;
    chat.each(function() {
        height += $(this).outerHeight();
    });

    return el.scrollTop(height);
}

function appendChat(data, isAdmin)
{
    let cls   = (isAdmin) ? "left" : "right",
        level = (isAdmin) ? "(You)" : "",
        name  = (isAdmin) ? data.nameAdmin : data.nameUser;
    let elem =   '<div class="__user ' + cls + '">' +
                    '<div class="__inner-user">' +
                        '<div class="__img">' +
                            '<img src="' + data.photo + '">' +
                        '</div>' +
                        '<div class="__info-user">' +
                            '<div class="__name">' + name + ' <strong>' + level + '</strong></div>' +
                            '<div class="__date">' + data.date + '</div>' +
                        '</div>' + 
                        '<div class="__msg">' +
                            '<pre>' + data.message + '</pre>'
                        '</div>'
                    '</div>'
                '</div>'
    return elem;
}
