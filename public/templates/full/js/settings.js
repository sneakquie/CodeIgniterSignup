var lastLogin = lastEmail = '';
var queue = 0;

function changeSocialField(el, network)
{
    $(el).css('display', 'none');
    $('#settings-social-' + network).css('display', 'block').focus().val($('#settings-social-' + network).val());
}

function changeSocialInput(el, network)
{
    if(el.value != '') {
        $(el).css('display', 'none');
        $('#settings-social-' + network + ' + p > a.settings-social-link').css('display', 'inline-block').html($(el).data('url') + $(el).val());
    }
}

function loginUpdate()
{
    login = $('#settings-login').val();

    if(login == lastLogin) {
        return;
    }

    $('#settings-login-error').remove();
    $('#settings-login').parent().removeClass('has-error');

    lastLogin = login;

    if (!Signup.checkLogin(login)) {
        $('#settings-login').after('<span class="help-block error" id="settings-login-error">' + language['wrong_login_format'] + '</span>');
        $('#settings-login').parent().addClass('has-error');
        return;
    }
    Signup.checkLoginExists(login, function(data) {
        if (data.error) {
            $('#settings-login-error').remove();
            $('#settings-login').parent().removeClass('has-error');
            $('#settings-login').after('<span class="help-block error" id="settings-login-error">' + data.error + '</span>');
            $('#settings-login').parent().addClass('has-error');
        }
    });
}

function emailUpdate()
{
    email = $('#settings-email').val();

    if(email == lastEmail) {
        return;
    }

    $('#settings-email-error').remove();
    $('#settings-email').parent().removeClass('has-error');

    lastEmail = email;

    if (!Signup.checkEmail(email)) {
        $('#settings-email').after('<span class="help-block error" id="settings-email-error">' + language['wrong_email_format'] + '</span>');
        $('#settings-email').parent().addClass('has-error');
        return;
    }
    Signup.checkEmailExists(email, function(data) {
        if (data.error) {
            $('#settings-email-error').remove();
            $('#settings-email').parent().removeClass('has-error');
            $('#settings-email').after('<span class="help-block error" id="settings-email-error">' + data.error + '</span>');
            $('#settings-email').parent().addClass('has-error');
        }
    });
}

function accountChanges()
{
    queue > 0 && clearTimeout(queue);
    $('#settings-login-error').remove();
    $('#settings-login').parent().removeClass('has-error');
    $('#settings-email-error').remove();
    $('#settings-email').parent().removeClass('has-error');

    $('.settings-loader').remove();
    $('#settings-message').remove();
    $('.panel-heading').prepend('<img src="../public/templates/full/images/loader.gif" class="settings-loader"/>');

    $.ajax({
        url: App.url + 'settings/checkaccount',
        data: {
            language: $('#settings-language').val(),
            timezone: $('#settings-timezone').val(),
            birth_day: $('#settings-day').val(),
            birth_month: $('#settings-month').val(),
            birth_year: $('#settings-year').val(),
            show_date: $('#settings-show-date').val(),
            email: $('#settings-email').val(),
            show_email: $('#settings-show-email').val(),
            location: $('#settings-location').val(),
            login: $('#settings-login').val(),
            name: $('#settings-name').val(),
            about: $('#settings-about').val(),
            website: $('#settings-website').val(),
            gender: $('#settings-gender').val(),
        },
        cache: false,
        dataType: 'json',
        method: 'POST',
        success: function (data) {
            $('#settings-message').remove();
            if(data.success) {
                $('.panel-default').before('<div class="alert alert-success" id="settings-message"><b>' + data.success + '</b></div>');
            }
            else if(data.error) {
                $('.panel-default').before('<div class="alert alert-danger" id="settings-message"><b>' + data.error + '</b></div>');
            }
            queue = setTimeout(function() {
                $('#settings-message').fadeOut();
            }, 12000);
            $('body, html').animate({
                scrollTop: $("#settings-message").offset().top - parseInt($("#settings-message").css('height')) * 1.2
            }, 200);
        },
        complete: function() {
            setTimeout(function() {
                $('.settings-loader').fadeOut();
            }, 3000);
        }
    });
}

function socialChanges()
{
    queue > 0 && clearTimeout(queue);

    $('.settings-loader').remove();
    $('#settings-message').remove();
    $('.panel-heading').prepend('<img src="../public/templates/full/images/loader.gif" class="settings-loader"/>');

    $.ajax({
        url: App.url + 'settings/checksocial',
        data: {
            social_facebook: $('#settings-social-facebook').val(),
            social_vkontakte: $('#settings-social-vkontakte').val(),
            social_twitter: $('#settings-social-twitter').val(),
            social_steam: $('#settings-social-steam').val(),
            social_flickr: $('#settings-social-flickr').val(),
            social_vimeo: $('#settings-social-vimeo').val(),
            social_youtube: $('#settings-social-youtube').val(),
            social_googleplus: $('#settings-social-googleplus').val(),
            social_odnoklassniki: $('#settings-social-odnoklassniki').val(),
            social_linkedin: $('#settings-social-linkedin').val(),
            social_tumblr: $('#settings-social-tumblr').val(),
        },
        cache: false,
        dataType: 'json',
        method: 'POST',
        success: function (data) {
            $('#settings-message').remove();
            if(data.success) {
                $('.panel-default').before('<div class="alert alert-success" id="settings-message"><b>' + data.success + '</b></div>');
            }
            else if(data.error) {
                $('.panel-default').before('<div class="alert alert-danger" id="settings-message"><b>' + data.error + '</b></div>');
            }
            queue = setTimeout(function() {
                $('#settings-message').fadeOut();
            }, 12000);
            $('body, html').animate({
                scrollTop: $("#settings-message").offset().top - parseInt($("#settings-message").css('height')) * 1.2
            }, 200);
        },
        complete: function() {
            setTimeout(function() {
                $('.settings-loader').fadeOut();
            }, 3000);
        }
    });
}

function passwordChanges()
{
    queue > 0 && clearTimeout(queue);
    $('.settings-loader').remove();
    $('#settings-message').remove();
    $('#settings-old-password').parent().removeClass('has-error');
    $('#settings-new-password').parent().removeClass('has-error');
    $('#settings-repeat-password').parent().removeClass('has-error');
    $('.panel-heading').prepend('<img src="../public/templates/full/images/loader.gif" class="settings-loader"/>');

    old_password = $('#settings-old-password').val();
    new_password = $('#settings-new-password').val();
    repeat_password = $('#settings-repeat-password').val();
    hasError = false;

    if( $('#settings-old-password').length > 0
     && !Signup.checkPassword(old_password)
    ) {
        $('.panel-default').before('<div class="alert alert-danger" id="settings-message"><b>' + language['wrong_password_format'] + '</b></div>');
        $('#settings-old-password').parent().addClass('has-error');
        $('#settings-old-password').focus();
        hasError = true;
    }
    else if(!Signup.checkPassword(new_password)) {
        $('.panel-default').before('<div class="alert alert-danger" id="settings-message"><b>' + language['wrong_password_format'] + '</b></div>');
        $('#settings-new-password').parent().addClass('has-error');
        $('#settings-new-password').focus();
        hasError = true;
    }
    else if(new_password != repeat_password) {
        $('.panel-default').before('<div class="alert alert-danger" id="settings-message"><b>' + language['passwords_not_equal'] + '</b></div>');
        $('#settings-repeat-password').parent().addClass('has-error');
        $('#settings-repeat-password').focus();
        hasError = true;
    }
    
    if(hasError == true) {
        setTimeout(function() {
            $('.settings-loader').fadeOut();
        }, 3000);
        queue = setTimeout(function() {
            $('#settings-message').fadeOut();
        }, 12000);
        $('body, html').animate({
            scrollTop: $("#settings-message").offset().top - parseInt($("#settings-message").css('height')) * 1.2
        }, 200);
        return;
    }

    $.ajax({
        url: App.url + 'settings/changepassword',
        data: {
            old_password: old_password,
            new_password: new_password,
            repeat_password: repeat_password,
        },
        cache: false,
        dataType: 'json',
        method: 'POST',
        success: function (data) {
            $('#settings-message').remove();
            if(data.success) {
                $('.panel-default').before('<div class="alert alert-success" id="settings-message"><b>' + data.success + '</b></div>');
                $('#settings-old-password').val('');
                $('#settings-new-password').val('');
                $('#settings-repeat-password').val('');
            }
            else if(data.error) {
                $('.panel-default').before('<div class="alert alert-danger" id="settings-message"><b>' + data.error + '</b></div>');
            }
            queue = setTimeout(function() {
                $('#settings-message').fadeOut();
            }, 12000);
            $('body, html').animate({
                scrollTop: $("#settings-message").offset().top - parseInt($("#settings-message").css('height')) * 1.2
            }, 200);
        },
        complete: function() {
            setTimeout(function() {
                $('.settings-loader').fadeOut();
            }, 3000);
        }
    });
}

function activateSend()
{
    queue > 0 && clearTimeout(queue);

    $.ajax({
        url: App.url + 'settings/checkactivate',
        cache: false,
        dataType: 'json',
        method: 'POST',
        success: function (data) {
            $('#settings-message').remove();
            if(data.success) {
                $('.panel-default').before('<div class="alert alert-success" id="settings-message"><b>' + data.success + '</b></div>');
                queue = setTimeout(function() {
                    $('#settings-message').fadeOut();
                }, 12000);
                $('body, html').animate({
                    scrollTop: $("#settings-message").offset().top - parseInt($("#settings-message").css('height')) * 1.2
                }, 200);
            }
        },
        complete: function() {
            setTimeout(function() {
                $('.settings-loader').fadeOut();
            }, 3000);
        }
    });
}

function notificationsChanges()
{
    queue > 0 && clearTimeout(queue);

    $('.settings-loader').remove();
    $('#settings-message').remove();
    $('.panel-heading').prepend('<img src="../public/templates/full/images/loader.gif" class="settings-loader"/>');

    $.ajax({
        url: App.url + 'settings/checknotifications',
        data: {
            show_last_login: $('#settings-last-login').val(),
            allow_email: $('#settings-allow-email').val(),
            notify_comments: $('#settings-notify-comments').val(),
            notify_comments_email: $('#settings-notify-comments-email').val(),
            notify_answers: $('#settings-notify-answers').val(),
            notify_answers_email: $('#settings-notify-answers-email').val(),
            notify_messages: $('#settings-notify-messages').val(),
            notify_messages_email: $('#settings-notify-messages-email').val(),
            notify_follow_news: $('#settings-notify-follow').val(),
            notify_cats_news: $('#settings-notify-cats').val(),
            notify_likes: $('#settings-notify-likes').val(),
        },
        cache: false,
        dataType: 'json',
        method: 'POST',
        success: function (data) {
            $('#settings-message').remove();
            if(data.success) {
                $('.panel-default').before('<div class="alert alert-success" id="settings-message"><b>' + data.success + '</b></div>');
            }
            else if(data.error) {
                $('.panel-default').before('<div class="alert alert-danger" id="settings-message"><b>' + data.error + '</b></div>');
            }
            queue = setTimeout(function() {
                $('#settings-message').fadeOut();
            }, 12000);
            $('body, html').animate({
                scrollTop: $("#settings-message").offset().top - parseInt($("#settings-message").css('height')) * 1.2
            }, 200);
        },
        complete: function() {
            setTimeout(function() {
                $('.settings-loader').fadeOut();
            }, 3000);
        }
    });
}

function initSelecter()
{
    $.each($('select'), function(x, y) {
        $(y).selecter({customClass: $(y).attr('class').replace(/(form-control)+/i, ''), cover: $(y).hasClass('selecter-cover'),});
    });
}

$(function() {
    $('#settings-body').on('keyup change', '#settings-login', loginUpdate);
    $('#settings-body').on('keyup change', '#settings-email', emailUpdate);

    $('#settings-body').on('click', '#account-save', function(e) {
        e.preventDefault();
        accountChanges();
    });

    $('#settings-body').on('click', '#social-save', function(e) {
        e.preventDefault();
        socialChanges();
    });

    $('#settings-body').on('click', '#notifications-save', function(e) {
        e.preventDefault();
        notificationsChanges();
    });

    $('#settings-body').on('click', '#password-save', function(e) {
        e.preventDefault();
        passwordChanges();
    });

    $('#settings-body').on('click', '#activate-send', function(e) {
        e.preventDefault();
        activateSend();
    });

    $('.panel-heading').prepend('<img src="../public/templates/full/images/loader.gif" class="settings-loader"/>');

    setTimeout(function() {
        $('.settings-loader').fadeOut();
    }, 3000);

    $("input[type=checkbox], input[type=radio]").picker();
    initSelecter();

    $('.settings-left-list > .list-group-item').on('click', function(e) {
        el = $(this);
        if( typeof el.data('settings') !== 'undefined'
         && el.data('settings').length > 0
        ) {
            e.preventDefault();
            $('#settings-message').remove();
            $('.settings-loader').remove();
            $('.panel-heading').html(el.html());
            $('.settings-left-list > .list-group-item').removeClass('active');
            el.addClass('active');
            $('.panel-heading').prepend('<img src="../public/templates/full/images/loader.gif" class="settings-loader"/>');

            setTimeout(function() {
                $('.settings-loader').fadeOut();
            }, 3000);

            $('#settings-body').load(App.url + 'settings/' + el.data('settings') + ' .panel-body', function() {
                $("input[type=checkbox], input[type=radio]").picker();
                initSelecter();
                History.pushState(null, el.data('title'), App.url + 'settings/' + el.data('settings'));
            });
        }
    });
});