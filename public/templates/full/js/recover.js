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
    
    $('#recover-show-password').click(function() {
        if($('#recover-password').hasClass('unhide-password')) {
            $('#recover-password, #recover-password-repeat').attr('type', 'password');
            $('#recover-password, #recover-password-repeat').removeClass('unhide-password');
            $('#recover-show-password > i').removeClass('fa-eye').addClass('fa-eye-slash');
        }
        else {
            $('#recover-password, #recover-password-repeat').attr('type', 'text');
            $('#recover-password, #recover-password-repeat').addClass('unhide-password');
            $('#recover-show-password > i').removeClass('fa-eye-slash').addClass('fa-eye');
        }
        $('#recover-password').focus();
    });

    $('#recover-button-last').click(function (e) {
        if(User.logged === true) {
            return;
        }
        e.preventDefault();
        $('#recover-error').remove();
        $('#recover-password-repeat').parent().removeClass('has-error');
        $('#recover-password').parent().parent().removeClass('has-error');

        var password = $('#recover-password').val();

        if(!Signup.checkPassword(password)) {
            $('#recover-form').prepend('<div class="alert alert-danger" id="recover-error" style="text-align:center;">' + language['wrong_password_format'] + '</div>');
            $('#recover-password').parent().parent().addClass('has-error');
            resetAnimation('.auth', 'bounceInLeft', 'shake');
            return;
        }

        if( $('#recover-password-repeat').length > 0
         && $('#recover-password-repeat').val() !== password
        ) {
            $('#recover-form').prepend('<div class="alert alert-danger" id="recover-error" style="text-align:center;">' + language['passwords_not_equal'] + '</div>');
            $('#recover-password-repeat').parent().addClass('has-error');
            resetAnimation('.auth', 'bounceInLeft', 'shake');
            return;
        }

        $('#recover-form').submit();
    });

    $('#recover-button').click(function (e) {
        if(User.logged === true) {
            return;
        }
        e.preventDefault();
        $('#recover-error').remove();
        $('#recover-username').parent().removeClass('has-error');

        var login = $('#recover-username').val();

        if( !Signup.checkLogin(login)
         && !Signup.checkEmail(login)
        ) {
            $('#recover-form').prepend('<div class="alert alert-danger" id="recover-error" style="text-align:center;">' + language['recover_wrong_data'] + '</div>');
            $('#recover-username').parent().addClass('has-error');
            resetAnimation('.auth', 'bounceInLeft', 'shake');
            $('#recover-username').focus().val($('#recover-username').val());
            return;
        }

        $('#recover-form').submit();
    });
});