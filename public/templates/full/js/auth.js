var Signup = {
    checkEmail: function(email) {
        var email_test = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return !(email.length == 0 || !email_test.test(email));
    },
    checkPassword: function(password) {
        return ($.trim(password).length > 5);
    },
    checkLogin: function(login) {
        return !(login.length == 0 || !/^[a-zA-Z0-9._\-]{4,30}$/.test(login));
    },
    checkEmailExists: function(email, callback) {
        $.ajax({
            url: App.url + 'auth/checkemail/',
            data: {email: email,},
            dataType: 'json',
            method: 'POST',
            success: function(data) {
                callback(data);
                if(data.error) {
                    return false;
                }
            },
        });
    },
    checkLoginExists: function(login, callback) {
        $.ajax({
            url: App.url + 'auth/checkusername/',
            data: {login: login,},
            dataType: 'json',
            method: 'POST',
            success: function(data) {
                callback(data);
                if(data.error) {
                    return false;
                }
            },
        });
    },
    reloadCaptcha: function(callback) {
        $.ajax({
            url: App.url + 'auth/captcha/',
            dataType: 'json',
            method: 'POST',
            success: function(data) {
                callback(data);
            },
        });
    },
}