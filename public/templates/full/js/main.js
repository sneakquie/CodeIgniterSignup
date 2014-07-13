var lastSearch = '';

function changeLanguage(language)
{
    if(language == '') {
        return;
    }
    $.ajax({
        url: App.url + 'language/set/' + language,
        complete: function() {
            location.reload();
        }
    });
}

function liveSeach()
{
	var query = $('#search-input').val();

    if(query == lastSearch) {
        return;
    }
    if(query == '') {
        $('.dropdown-search').css('display', 'none');
    }
    else {
        $('.dropdown-search').css('display', 'block');
    }
    lastSearch = query;
}

function resetAnimation(selecter, oldAnimation, newAnimation)
{
    $(selecter).removeClass(oldAnimation).removeClass(newAnimation).animate({'vinc': null,}, 1, function() {
        $(this).addClass(newAnimation);
    });
}

var Msg = {
    _msg: function(message, type, layout, timeout) {
        if(typeof(layout) === 'undefined') layout = 'top';
        if(typeof(timeout) === 'undefined') timeout = 4000;
        if(typeof(type) === 'undefined') type = 'information';
        noty({text: message, type: type, layout: layout, timeout: timeout});
    },
    error: function(message, layout, timeout) {
        Msg._msg(message, 'error', layout, timeout);
    },
    success: function(message, layout, timeout) {
        Msg._msg(message, 'success', layout, timeout);
    },
    info: function(message, layout, timeout) {
        Msg._msg(message, 'information', layout, timeout);
    },
    warning: function(message, layout, timeout) {
        Msg._msg(message, 'warning', layout, timeout);
    }
}

$(function() {
    $('html').on('click', function() {
        $('.dropdown-search').css('display', 'none');
    });
    $('.dropdown-search').on('click', function() {
        return false;
    });
	$('#search-input').on('keyup', liveSeach);
});