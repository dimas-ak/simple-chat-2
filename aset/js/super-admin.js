$(document).ready(function() {
    let click  = "click",
        aktif  = "aktif",
        submit = "submit";
    $(document).on(click, "a.__btn-admin", function(x) {
        x.preventDefault();
        let ini      = $(this),
            url      = ini.attr('href'),
            category = ini.attr('category'),
            container= $('.__admin'),
            back     = container.find('.__top a.__menu.back');
        if(category === 'open-admin')
        {
            let elem        = container.find('.open-admin'),
                name        = elem.find('#nav-name'),
                link        = elem.find('#nav-url'),
                username    = elem.find('#nav-username'),
                user_related= elem.find('.__right .__inner-body.center');
            $.ajax({
                url : url,
                type: "POST",
                success: function(data) 
                {
                    let json = JSON.parse(data);
                    name.html(json.name);
                    username.html(json.username);
                    link.attr('data-id', json.id);

                    user_related.html(json.user_chat);

                    elem.addClass(aktif);
                    
                    back.removeAttr('style');
                    back.attr('category', category);
                }
            });
            
        }
        else if(category === 'open-chat')
        {
            let body_chat   = container.find('.__body-chat-admin');
            $.ajax({
                url : url,
                type: "POST",
                success: function(data) 
                {
                    let json = JSON.parse(data);
                    body_chat.html(json.view);
                    body_chat.addClass(aktif);
                    back.attr('category','open-chat');
                }
            });
        }
    });
    
    
    $(document).on(click, "a.__menu", function(x) {
        x.preventDefault();
        let ini         = $(this),
            url         = ini.attr('href'),
            category    = ini.attr('category');
        if(category === 'account')
        {}
        else if(category === 'logout')
        {
            window.location.href = url;
        }
    });

    // open account admin
    $(document).on(click, '#nav-url', function(x) {
        x.preventDefault();
        let ini         = $(this),
            url         = ini.attr('href'),
            id          = ini.attr('data-id'),
            container   = $('.__admin'),
            account_body= container.find(".account-admin"),
            chat        = $('.__admin .open-admin .__body-chat-admin');
            back     = container.find('.__top a.__menu.back');
        let color = {
            red     : "#e74c3c",
            yellow  : "#f1c40f",
            green   : "#2ecc71",
            blue    : "#3498db"
        }; 
        $.ajax({
            url : url + id,
            type: "POST",
            success: function (data)
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
                chat.removeClass(aktif);
                back.attr('category', 'account-admin');
            }
        });
    });


    // back menu
    $(document).on(click, "a.__menu.back", function(x) {
        x.preventDefault();
        let ini         = $(this),
            container   = $('.__admin'),
            category    = ini.attr('category');
        if(category === 'open-admin')
        {
            container.find('.open-admin').removeClass(aktif);
            ini.attr('category', "");
            ini.css('display', "none");
        }
        else if(category === 'account-admin')
        {
            ini.attr('category', "open-admin");
            container.find('.account-admin').removeClass(aktif);
        }
        else if(category === 'open-chat')
        {
            ini.attr('category', 'open-admin');
            container.find('.__body-chat-admin').removeClass(aktif);
        }
    });
});