var UserProfile = {
	follow: function(id) {
		id = parseInt(id);

		if( id <= 0
		 || !User.logged
		) {
			return;
		}

		$.ajax({
	        url: App.url + 'userc/follow',
	        data: {
	            user_id: id,
	        },
	        cache: false,
	        dataType: 'json',
	        method: 'POST',
	        success: function (data) {
	        	if(data.success) {
	        		$('.follow-btn').css('display', 'none');
	        		$('.unfollow-btn').css('display', 'inline-block');
	        		Msg.success(data.success);
	        	}
	        	else if(data.error) {
	        		Msg.warning(data.error);
	        	}
	        }
    	});
	},
	unfollow: function(id) {
		id = parseInt(id);

		if( id <= 0
		 || !User.logged
		) {
			return;
		}

		$.ajax({
	        url: App.url + 'userc/unfollow',
	        data: {
	            user_id: id,
	        },
	        cache: false,
	        dataType: 'json',
	        method: 'POST',
	        success: function (data) {
	        	if(data.success) {
	        		$('.unfollow-btn').css('display', 'none');
	        		$('.follow-btn').css('display', 'inline-block');
	        		Msg.success(data.success);
	        	}
	        	else if(data.error) {
	        		Msg.warning(data.error);
	        	}
	        }
    	});
	},
	flag: function(id, type) {
		if(typeof(type) === 'undefined') type = 1;

		if((id = parseInt(id)) <= 0) {
			return;
		}
		$.ajax({
	        url: App.url + 'userc/flag',
	        data: {
	            user_id: id,
	            type: type,
	        },
	        cache: false,
	        dataType: 'json',
	        method: 'POST',
	        success: function (data) {
	        	if(data.success) {
	        		Msg.info(data.success);
	        		$('.flag-btn').css('display', 'none');
	        		$('.flag-disabled-btn').css('display', 'block');
	        	}
	        	else if(data.error) {
	        		Msg.warning(data.error);
	        	}
	        }
    	});
	},
	showNoteForm: function() {
		$('#user-note-link').css('display', 'none');
		$('#user-note-form').css('display', 'block');
	},
	closeNoteForm: function() {
		$('#user-note-link').css('display', 'inline');
		$('#user-note-form').css('display', 'none');
	},
	saveNote: function(id) {
		if((id = parseInt(id)) <= 0) {
			return;
		}
		$.ajax({
	        url: App.url + 'userc/note',
	        data: {
	            user_id: id,
	            text: $('#user-note-input').val(),
	        },
	        cache: false,
	        dataType: 'json',
	        method: 'POST',
	        success: function (data) {
	        	if(data.success) {
	        		Msg.success(data.success);
	        		$('#user-note-link').html(data.note);
	        		UserProfile.closeNoteForm();
	        	}
	        	else if(data.error) {
	        		Msg.warning(data.error);
	        	}
	        }
    	});
	},
	showContactForm: function() {
		$('#user-contact-link').css('display', 'none');
		$('#user-contact-form').css('display', 'block');
		$('#user-contact-textarea').focus();
	},
	closeContactForm: function() {
		$('#user-contact-link').css('display', 'inline');
		$('#user-contact-form').css('display', 'none');
		$('#user-contact-textarea').val('');
	},
	sendEmail: function(id) {
		if((id = parseInt(id)) <= 0) {
			return;
		}
		$.ajax({
	        url: App.url + 'userc/email',
	        data: {
	            user_id: id,
	            text: $('#user-contact-textarea').val(),
	        },
	        cache: false,
	        dataType: 'json',
	        method: 'POST',
	        success: function (data) {
	        	if(data.success) {
	        		Msg.success(data.success);
	        		UserProfile.closeContactForm();
	        	}
	        	else if(data.error) {
	        		Msg.warning(data.error);
	        	}
	        }
    	});
	},
	edit: function(id) {
		if((id = parseInt(id)) <= 0) {
			return;
		}
		$.ajax({
	        url: App.url + 'userc/edit',
	        data: {
	            user_id: id,
	            birth_day: $('#user-edit-day').val(),
	            birth_month: $('#user-edit-month').val(),
	            birth_year: $('#user-edit-year').val(),
	            email: $('#user-edit-email').val(),
	            location: $('#user-edit-location').val(),
	            login: $('#user-edit-login').val(),
	            name: $('#user-edit-name').val(),
	            about: $('#user-edit-about').val(),
	            website: $('#user-edit-website').val(),
	            gender: $('#user-edit-gender').val(),
	            avatar: $('#user-edit-avatar').val(),
	            cover: $('#user-edit-cover').val(),
	            verified: $('#user-edit-verified').val(),
	            password: $('#user-edit-password').val(),
	        },
	        cache: false,
	        dataType: 'json',
	        method: 'POST',
	        success: function (data) {
	        	if(data.success) {
	        		Msg.success(data.success);
	        		$('.user-profile-cover').css('background-image', 'url(' + data.photo_cover + ')');
	        		$('.user-profile-avatar-img').attr('src', data.photo_avatar);
	        		$('.user-location').text(data.location);
	        		$('.user-born-date').text(data.born_date);
	        		$('.user-email').text(data.email).attr('href', 'mailto:' + data.email);
	        		$('.user-website').text(data.website).attr('href', 'http://' + data.website);
	        		$('.user-profile-login').text(data.login);

	        		$('body, html').animate({
		                scrollTop: 0
		            }, 200);
	        	}
	        	else if(data.error) {
	        		Msg.warning(data.error);
	        	}
	        }
    	});
	},
	loadFollowings: function(user_id, offset) {
		if( (user_id = parseInt(user_id)) <= 0
		 || (offset  = parseInt(offset)) <= 0
		) {
			return;
		}
		$.ajax({
	        url: App.url + 'userc/followingsload',
	        data: {
	            user_id: user_id,
	            offset: offset
	        },
	        cache: false,
	        dataType: 'json',
	        method: 'POST',
	        beforeSend: function() {
			    $('#follow-user-loader').show();
			    $('#follow-user-load').hide();
	        },
	        complete: function() {
			    $('#follow-user-loader').hide();
			    $('#follow-user-load').show();
	        },
	        success: function (data) {
	        	if(data.success) {
	        		if(data.followings.length > 0) {
	        			for(key in data.followings) {
		        			$('#follow-user-load').parent().before(data.followings[key]);
		        		}
		        		$('[data-toggle="tooltip"]').tooltip();
	        		}
	        		if(data.offset) {
	        			followingsOffset = data.offset;
	        		}
	        		else {
	        			$('#follow-user-load').remove();
	        		}
	        	}
	        	else if(data.error) {
	        		Msg.warning(data.error);
	        	}
	        }
    	});
	},
	loadFollowers: function(user_id, offset) {
		if( (user_id = parseInt(user_id)) <= 0
		 || (offset  = parseInt(offset)) <= 0
		) {
			return;
		}
		$.ajax({
	        url: App.url + 'userc/followersload',
	        data: {
	            user_id: user_id,
	            offset: offset
	        },
	        cache: false,
	        dataType: 'json',
	        method: 'POST',
	        beforeSend: function() {
			    $('#follow-user-loader').show();
			    $('#follow-user-load').hide();
	        },
	        complete: function() {
			    $('#follow-user-loader').hide();
			    $('#follow-user-load').show();
	        },
	        success: function (data) {
	        	if(data.success) {
	        		if(data.followers.length > 0) {
	        			for(key in data.followers) {
		        			$('#follow-user-load').parent().before(data.followers[key]);
		        		}
		        		$('[data-toggle="tooltip"]').tooltip();
	        		}
	        		if(data.offset) {
	        			followersOffset = data.offset;
	        		}
	        		else {
	        			$('#follow-user-load').remove();
	        		}
	        	}
	        	else if(data.error) {
	        		Msg.warning(data.error);
	        	}
	        }
    	});
	},
}

$(function() {
	$.each($('select'), function(x, y) {
        $(y).selecter({customClass: $(y).attr('class').replace(/(form-control)+/i, ''), cover: $(y).hasClass('selecter-cover'),});
    });

    $('html').on('click', '#user-edit-save', function(e) {
    	e.preventDefault();
    	UserProfile.edit($('#user-edit-save').data('usereditid'));
    });
});