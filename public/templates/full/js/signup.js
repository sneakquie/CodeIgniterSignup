var lastPassword = lastEmail = lastLogin = lastInvite = '';

$(function() {
    $('#signup-show-password').click(function() {
        if($('#signup-password').hasClass('unhide-password')) {
            $('#signup-password, #signup-password-repeat').attr('type', 'password');
            $('#signup-password, #signup-password-repeat').removeClass('unhide-password');
            $('#signup-show-password > i').removeClass('fa-eye').addClass('fa-eye-slash');
        }
        else {
            $('#signup-password, #signup-password-repeat').attr('type', 'text');
            $('#signup-password, #signup-password-repeat').addClass('unhide-password');
            $('#signup-show-password > i').removeClass('fa-eye-slash').addClass('fa-eye');
        }
        $('#signup-password').focus();
    });

    $('#signup-captcha-image').click(function () {
        if(User.logged === true) {
            return;
        }
        Signup.reloadCaptcha(function(data) {
            if(data.captcha) {
                $('#signup-captcha-image').html(data.captcha);
            }
        });
        $('#captcha').focus();
    });

    $('#signup-password').on('keyup change', function() {
        if( User.logged === true
         || (password = $(this).val()) == lastPassword
        ) {
            return;
        }
        lastPassword = password;
        $('#signup-password-error').remove();
        $('#signup-password').parent().parent().removeClass('has-error');
        $('#signup-password-repeat-error').remove();
        $('#signup-password-repeat').parent().removeClass('has-error');

        if($('#signup-password-repeat').val() != password) {
            $('#signup-password-repeat').after('<span class="help-block error" id="signup-password-repeat-error">' + language['passwords_not_equal'] + '</span>');
            $('#signup-password-repeat').parent().addClass('has-error');
        }

        if(!Signup.checkPassword(password)) {
            $('#signup-password').parent().after('<span class="help-block error" id="signup-password-error">' + language['wrong_password_format'] + '</span>');
            $('#signup-password').parent().parent().addClass('has-error');
            return;
        }
    });

    $('#signup-password-repeat').on('keyup change', function() {
        if(User.logged === true) {
            return;
        }
        var password = $(this).val();
        $('#signup-password-repeat-error').remove();
        $('#signup-password-repeat').parent().removeClass('has-error');

        if(password != $('#signup-password').val()) {
            $('#signup-password-repeat').after('<span class="help-block error" id="signup-password-repeat-error">' + language['passwords_not_equal'] + '</span>');
            $('#signup-password-repeat').parent().addClass('has-error');
        }
    });

    $('#signup-login').on('keyup change', function (e) {
        if( User.logged === true
         || (login = $(this).val()) == lastLogin
        ) {
            return;
        }
        lastLogin = login;
        $('#signup-login-error').remove();
        $('#signup-login').parent().removeClass('has-error');

        if (!Signup.checkLogin(login)) {
            $('#signup-login').after('<span class="help-block error" id="signup-login-error">' + language['wrong_login_format'] + '</span>');
            $('#signup-login').parent().addClass('has-error');
            return;
        }
        Signup.checkLoginExists(login, function(data) {
            if (data.error) {
                $('#signup-login-error').remove();
                $('#signup-login').parent().removeClass('has-error');
                $('#signup-login').after('<span class="help-block error" id="signup-login-error">' + data.error + '</span>');
                $('#signup-login').parent().addClass('has-error');
            }
        });
    });

    $('#signup-email').on('keyup change', function () {
        if( User.logged === true
         || (email = $(this).val()) == lastEmail
        ) {
            return;
        }
        lastEmail = email;
        $('#signup-email-error').remove();
        $('#signup-email').parent().removeClass('has-error');

        if (!Signup.checkEmail(email)) {
            $('#signup-email').after('<span class="help-block error" id="signup-email-error">' + language['wrong_email_format'] + '</span>');
            $('#signup-email').parent().addClass('has-error');
            return;
        }
        Signup.checkEmailExists(email, function(data) {
            if (data.error) {
                $('#signup-email-error').remove();
                $('#signup-email').parent().removeClass('has-error');
                $('#signup-email').after('<span class="help-block error" id="signup-email-error">' + data.error + '</span>');
                $('#signup-email').parent().addClass('has-error');
            }
        });
    });

    $('#signup-invite').on('keyup change', function () {
        if( User.logged === true
         || (invite = $.trim($(this).val())) == lastInvite
        ) {
            return;
        }
        lastInvite = invite;
        $('#signup-invite-error').remove();
        $('#signup-invite').parent().removeClass('has-error');

        if (invite.length > 0) {
            if(invite.length !== 32) {
                $('#signup-invite').after('<span class="help-block error" id="signup-invite-error">' + language['invite_wrong_format'] + '</span>');
                $('#signup-invite').parent().addClass('has-error');
            }
        }
        else if($('#signup-invite-not-required').length == 0) {
            $('#signup-invite').after('<span class="help-block error" id="signup-invite-error">' + language['invite_required'] + '</span>');
            $('#signup-invite').parent().addClass('has-error');
        }
    });

    $('#signup-button').click(function (e) {
        if(User.logged === true) {
            return;
        }
        e.preventDefault();
        $('#signup-button').addClass('disabled').attr('disabled', 'disabled');
        $('#signup-login-error').remove();
        $('#signup-login').parent().removeClass('has-error');
        $('#signup-email').parent().removeClass('has-error');
        $('#signup-email-error').remove();
        $('#signup-password-repeat-error').remove();
        $('#signup-password-repeat').parent().removeClass('has-error');
        $('#signup-password').parent().parent().removeClass('has-error');
        $('#signup-password-error').remove();
        $('#signup-terms-error').remove();
        $('#signup-invite-error').remove();
        $('#signup-invite').parent().removeClass('has-error');

        var login = $('#signup-login').val();
        var email = $('#signup-email').val();
        var password = $('#signup-password').val();
        var invite = $('#signup-invite').val();

        var hasError = false;
        if (!Signup.checkLogin(login)) {
            $('#signup-login').after('<span class="help-block error" id="signup-login-error">' + language['wrong_login_format'] + '</span>');
            $('#signup-login').parent().addClass('has-error');
            hasError = true;
        } else {
            Signup.checkLoginExists(login, function(data) {
                if (data.error) {
                    $('#signup-login-error').remove();
                    $('#signup-login').parent().removeClass('has-error');
                    $('#signup-login').after('<span class="help-block error" id="signup-login-error">' + data.error + '</span>');
                    $('#signup-login').parent().addClass('has-error');
                    resetAnimation('.auth', 'bounceInLeft', 'shake');
                }
            });
        }

        if (!Signup.checkEmail(email)) {
            $('#signup-email').after('<span class="help-block error" id="signup-email-error">' + language['wrong_email_format'] + '</span>');
            $('#signup-email').parent().addClass('has-error');
            hasError = true;
        } else {
            Signup.checkEmailExists(email, function(data) {
                if (data.error) {
                    $('#signup-email-error').remove();
                    $('#signup-email').parent().removeClass('has-error');
                    $('#signup-email').after('<span class="help-block error" id="signup-email-error">' + data.error + '</span>');
                    $('#signup-email').parent().addClass('has-error');
                    resetAnimation('.auth', 'bounceInLeft', 'shake');
                }
            });
        }

        if($('#signup-invite').length > 0) {
            if (invite.length > 0) {
                if(invite.length !== 32) {
                    $('#signup-invite').after('<span class="help-block error" id="signup-invite-error">' + language['invite_wrong_format'] + '</span>');
                    $('#signup-invite').parent().addClass('has-error');
                    hasError = true;
                }
            }
            else if($('#signup-invite-not-required').length == 0) {
                $('#signup-invite').after('<span class="help-block error" id="signup-invite-error">' + language['invite_required'] + '</span>');
                $('#signup-invite').parent().addClass('has-error');
                hasError = true;
            }
        }

        if($('#signup-password').length > 0) {
            if(!Signup.checkPassword(password)) {
                $('#signup-password').parent().after('<span class="help-block error" id="signup-password-error">' + language['wrong_password_format'] + '</span>');
                $('#signup-password').parent().parent().addClass('has-error');
                hasError = true;
            }

            if( $('#signup-password-repeat').length > 0
             && $('#signup-password-repeat').val() !== $('#signup-password').val()
            ) {
                $('#signup-password-repeat').after('<span class="help-block error" id="signup-password-repeat-error">' + language['passwords_not_equal'] + '</span>');
                $('#signup-password-repeat').parent().addClass('has-error');
                hasError = true;
            }
        }

        if( $('#signup-terms').length > 0
         && $('#signup-terms').is(':checked') == false
        ) {
            $('#signup-terms').next().after('<span class="has-error help-block error" id="signup-terms-error">' + language['agree_terms'] + '</span>');
            hasError = true;
        }

        if(hasError == true) {
            resetAnimation('.auth', 'bounceInLeft', 'shake');
            $('#signup-button').removeClass('disabled').removeAttr('disabled');
            $('body').animate({
                scrollTop: $(".auth-top").offset().top
            }, 200);
            return;
        }
        else {
            $.ajax({
                url: App.url + 'auth/checksignup/',
                data: {
                    email: email,
                    login: login,
                    password: password,
                    repeat: $('#signup-password-repeat').val(),
                    invite: $('#signup-invite').val(),
                    terms: $('#signup-terms').is(':checked'),
                },
                cache: false,
                dataType: 'json',
                method: 'POST',
                success: function (data) {
                    $('#signup-button').removeClass('disabled').removeAttr('disabled');
                    if (data.errors) {
                        resetAnimation('.auth', 'bounceInLeft', 'shake');
                        $('#signup-login-error').remove();
                        $('#signup-login').parent().removeClass('has-error');
                        $('#signup-email').parent().removeClass('has-error');
                        $('#signup-email-error').remove();
                        $('#signup-password-repeat-error').remove();
                        $('#signup-password-repeat').parent().removeClass('has-error');
                        $('#signup-password').parent().parent().removeClass('has-error');
                        $('#signup-password-error').remove();
                        $('#signup-terms-error').remove();
                        $('#signup-invite-error').remove();
                        $('#signup-invite').parent().removeClass('has-error');

                        $.each(data.errors, function(index, value) {
                            if(index == 'password') {
                                $('#signup-password').parent().after('<span class="has-error help-block error" id="signup-password-error">' + value + '</span>');
                                $('#signup-password').parent().parent().addClass('has-error');
                            }
                            else if(index == 'terms') {
                                $('#signup-terms').next().after('<span class="has-error help-block error" id="signup-terms-error">' + value + '</span>');
                            }
                            else {
                                $('#signup-' + index).after('<span class="has-error help-block error" id="signup-' + index + '-error">' + value + '</span>');
                                $('#signup-' + index).parent().addClass('has-error');
                            }
                        });
                        $('body').animate({
                            scrollTop: $(".auth-top").offset().top
                        }, 200);
                    }
                    else {
                        $('#signup-form').submit();
                    }
                }
            });
        }
    });
});