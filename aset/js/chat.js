$(document).ready(function() {
    let http   = (window.location.protocol === 'http:') ? "http://" : "https://";
        socket = io(http + window.location.hostname + ":3000");
    let click  = "click",
        aktif  = "aktif",
        submit = "submit";
    let color = {
            red     : "#e74c3c",
            yellow  : "#f1c40f",
            green   : "#2ecc71",
            blue    : "#3498db"
        };    
    // show chat
    $(document).on(click, ".__container-chat .__show-chat", function() {
        let container = $(this).parents().eq(1);
        (container.hasClass(aktif)) ? container.removeClass(aktif) : container.addClass(aktif);
    });

    // star on hover
    $(document).on({
        mouseover: function()
        {
            let ini         = $(this),
                index       = ini.index(),
                parent      = ini.parent(),
                color_star  = parent.find(".__star-color");

            // 30 is width from img star
            let total = 30 * index;

            let aktif_color = "";

            if     (total <= 30)  aktif_color = "red";
            else if(total <= 60)  aktif_color = color.red;
            else if(total <= 90)  aktif_color = color.yellow;
            else if(total <= 120) aktif_color = color.green;
            else                  aktif_color = color.blue;

            color_star.css({width: total + "px", background: aktif_color});

        },
        mouseleave: function()
        {
            let ini         = $(this),
                parent      = ini.parent(),
                color_star  = parent.find(".__star-color"),
                data_star   = parent.attr("data-star");
            
            let aktif_color = "";

            let total       = parseInt(data_star);    
            if     (total <= 30)  aktif_color = "red";
            else if(total <= 60)  aktif_color = color.red;
            else if(total <= 90)  aktif_color = color.yellow;
            else if(total <= 120) aktif_color = color.green;
            else                  aktif_color = color.blue;

            let width      = (data_star.length === 0) ? 0 : total,
                background = (data_star.length === 0) ? "transparent" : aktif_color;
            color_star.css({width: width, background: background});
        }
    }, '.__star .__star-inner img');

    // click star
    $(document).on(click, ".__star .__star-inner img", function() {
        let ini         = $(this),
            parent      = ini.parent(),
            input       = parent.parent().find("#star");

        let total       = 30 * parseInt(ini.index());
        input.val(ini.index());
        parent.attr('data-star', total);
    });

    /*  variable socket
        1. finish
        2. send
        3. isTyping
    */

    // form login chat
    $(document).on(click, '.__review', function(x) { 
        x.preventDefault();
        let ini     = $(this),
            url     = ini.attr("href");
        if(confirm("Are you sure to end this chat?"))
        {
            $.ajax({
                url: url,
                type: "POST",
                success: function(data)
                {
                    let json = JSON.parse(data),
                        chat = $('body').find('.__container-chat .__inner-chat');
                    ini.remove(); // remove button logout
                    chat.html(json.view); // set view login

                    let msg  = {
                        admin  : json.admin,
                        user   : json.user,
                        status : 2 // status chat done by user
                    };
                    // send status close chat to Officer WebChat
                    socket.emit("finish", msg);
                }
            });
        }
    });

    //submit form review
    $(document).on(submit, '#chat-review', function(x) { 
        x.preventDefault();
        let ini     = $(this),
            url     = ini.attr("action"),
            msg     = ini.find("#msg-msg"),
            star    = ini.find("#msg-star");
        $.ajax({
            url : url,
            type: "POST",
            data: ini.serialize(),
            success: function(data)
            {
                let json = JSON.parse(data),
                    chat = $('body').find('.__container-chat .__inner-chat');
                if(json.result)
                {
                    chat.html(json.view);
                    //tell the officer WebChat that user already has sending a rated of services
                    let info = 
                    {
                        admin  : json.admin,
                        user   : json.user,
                        status : 5
                    }
                    socket.emit("finish", info);
                }
                else
                {
                    console.log(json.msg);
                    msg.html(json.msg);
                    star.html(json.star);
                }
            }
        });
    });

    //submit form login
    $(document).on(submit, '#chat-login', function(x) {
        x.preventDefault();
        let ini     = $(this),
            url     = ini.attr("action"),
            name    = ini.find("#msg-name"),
            email   = ini.find('#msg-email'),
            address = ini.find('#msg-address'),
            telephon= ini.find('#msg-telephon'),
            chat    = ini.parent(),
            title   = $('body').find('.__container-chat .__colomn-chat.title');
        $.ajax({
            url : url,
            type: "POST",
            data: ini.serialize(),
            success: function(data)
            {
                let json = JSON.parse(data);
                // result success / true
                if(json.result)
                {
                    chat.html(json.view);
                    if(json.send_msg)
                    {
                        let send = {
                            type : "start", // just start the chat
                            from : json.user,
                            to   : json.admin,
                            nameAdmin: json.nameAdmin,
                            nameUser: json.nameUser,
                            photo: json.photo
                        };
                        title.append(json.logout);

                        // send info to Officer WebChat
                        socket.emit("send", send);
                    }
                }
                else
                {
                    name.html(json.name);
                    email.html(json.email);
                    address.html(json.address);
                    telephon.html(json.telephon);
                }
            }
        });
    });

    // remove msg error and success
    $(document).on(click, ".__msg-error, .__msg-success", function(){ $(this).remove(); });

    // send message on type
    $(document).on('keyup', '.__send-input', function (e) { 
        let event  = e || window.event,
            key    = event.which || event.keyCode,
            ini    = $(this),
            form   = ini.parents('form');
        
        let msg = 
            {
                onTyping : false,
                typingFor: 1, //for Officer WebChat
                user     : ini.attr("id-user"),
                admin    : ini.attr('id-admin'),
                replaceStat:""
            }
        if (key === 13 && !event.shiftKey && ini.val().trim().length !== 0) //same as "ENTER" and "SHIFT"
        { 
            e.preventDefault();
            msg.onTyping    = false;
            msg.replaceStat = ini.attr('id-user');
            $(form).trigger("submit");
        }
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

    
    //submit form send message
    $(document).on(submit, '#form-send', function(x) { 
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
                            msg_for : 1, // for admin
                            nameAdmin: json.nameAdmin,
                            nameUser: json.nameUser,
                            date    : json.date
                        };
                        socket.emit("send", msg);
                        // set value textarea to null / empty
                        input.val("");

                        // true is for user
                        chat.append(appendChat(msg, true)).focus();

                        input.focus();

                        focusLast();
                        
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

    socket.on("isTyping", function(data) {
        let textarea = $("body").find(".__container-chat .__send-input"),
            id_admin = textarea.attr('id-admin'),
            id_user  = textarea.attr('id-user');
        if(data.admin === id_admin && id_user === data.user && data.onTyping && data.typingFor === 2)
        {
            textarea.attr('placeholder', 'Admin is typing ...');
        }
        if(data.admin === id_admin && id_user === data.user && !data.onTyping && data.typingFor === 2 && data.replaceStat === id_user)
        {
            textarea.attr('placeholder', 'Type your message...');
        }
    });

    socket.on("send", function(data) {
        let textarea = $("body").find(".__container-chat .__send-input"),
            id_admin = textarea.attr('id-admin'),
            id_user  = textarea.attr('id-user'),
            chat     = $('body').find(".__container-chat .__chat"); 
        let dt_admin = data.to,
            dt_user  = data.from,
            type     = data.type;
        if(type === 'chat')
        {
            if(parseInt(id_admin) === parseInt(dt_admin) && parseInt(id_user) === parseInt(dt_user) && data.msg_for === 2)
            {
                chat.find(".__wrap-chat").append(appendChat(data));

                focusLast();
            }
        }
    });

});

function focusLast()
{
    let el          = $('body').find(".__container-chat .__body-chat .__wrap-chat"),
        chat        = el.find(".__user"),
        height      = 0;
    chat.each(function() {
        height += $(this).outerHeight();
    });
    console.log(el);
    return el.scrollTop(height);
}

function appendChat(data, isUser)
{
    let cls   = (isUser) ? "left" : "right",
        level = (!isUser) ? "(CS)" : "",
        name  = (isUser) ? data.nameUser : data.nameAdmin;
    let elem =   '<div class="__user ' + cls + '">' +
                    '<div class="__inner-user">' +
                        '<div class="__img">' +
                            '<img src="' + data.photo + '">' +
                        '</div>' +
                        '<div class="__info-user">' +
                            '<div class="__name">' + name+ ' <strong>' + level + '</strong></div>' +
                            '<div class="__date">' + data.date + '</div>' +
                        '</div>' + 
                        '<div class="__msg">' +
                            '<pre>' + data.message + '</pre>'
                        '</div>'
                    '</div>'
                '</div>'
    return elem;
}