$(function() {
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

    
    $('#login-button').click(function (e) {
        if(User.logged === true) {
            return;
        }
        e.preventDefault();

        $('#login-button').addClass('disabled').attr('disabled', 'disabled');
        $('#login-error').remove();
        $('#login-password').parent().removeClass('has-error').removeClass('has-warning');
        $('#login-username').parent().removeClass('has-error').removeClass('has-warning');

        var login    = $('#login-username').val();
        var password = $('#login-password').val();

        if( !Signup.checkPassword(password)
         || !(Signup.checkLogin(login)
         || Signup.checkEmail(login))
        ) {
            $('#login-password').parent().addClass('has-error');
            $('#login-username').parent().addClass('has-error');
            $('#login-form').prepend('<div class="alert alert-danger" id="login-error" style="text-align:center;">' + language['wrong_login_data'] + '</div');
            $('#login-button').removeClass('disabled').removeAttr('disabled');
            resetAnimation('.auth', 'bounceInLeft', 'shake');
            $('#login-username').focus().val($('#login-username').val());
            return;
        }

        $.ajax({
            url: App.url + 'auth/checklogin',
            data: {
                login: login,
                password: password,
                recaptcha_response_field: $('[name="recaptcha_response_field"]').val(),
                recaptcha_challenge_field: $('[name="recaptcha_challenge_field"]').val(),
            },
            cache: false,
            dataType: 'json',
            method: 'POST',
            success: function (data) {
                $('#login-button').removeClass('disabled').removeAttr('disabled');
                $('#login-error').remove();
                $('#login-password').parent().removeClass('has-error').removeClass('has-warning');
                $('#login-username').parent().removeClass('has-error').removeClass('has-warning');

                if (data.error) {
                    $('#login-password').parent().addClass('has-error');
                    $('#login-username').parent().addClass('has-error');
                    $('#login-form').prepend('<div class="alert alert-danger" id="login-error" style="text-align:center;">' + data.error + '</div');
                    resetAnimation('.auth', 'bounceInLeft', 'shake');
                    $('#login-username').focus().val($('#login-username').val());
                }
                else if(data.warning) {
                    $('#login-password').parent().addClass('has-warning');
                    $('#login-username').parent().addClass('has-warning');
                    $('#login-form').prepend('<div class="alert alert-warning" id="login-error" style="text-align:center;">' + data.warning + '</div');
                    resetAnimation('.auth', 'bounceInLeft', 'shake');
                }
                else if(data.message) {
                    $('#login-form').submit();
                    return;
                }
            }
        });
    });
});