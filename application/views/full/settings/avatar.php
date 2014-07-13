<script src="/public/js/fileupload/vendor/jquery.ui.widget.min.js"></script>
<script src="/public/js/fileupload/jquery.iframe-transport.min.js"></script>
<script src="/public/js/fileupload/jquery.fileupload.min.js"></script>
<script src="/public/js/fileupload/jquery.fileupload-process.min.js"></script>
<script src="/public/js/fileupload/jquery.fileupload-validate.min.js"></script>
<script src="/public/js/fileupload/jquery.fileupload-ui.min.js"></script>
<script src="/public/templates/full/js/fileupload.js"></script>

<noscript><link rel="stylesheet" href="/public/templates/full/css/fileupload/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="/public/templates/full/css/fileupload/jquery.fileupload-ui-noscript.css"></noscript>
<script type="text/javascript">
function deleteAvatar()
{
    $('.settings-loader').remove();
    $('.panel-heading').prepend('<img src="../public/templates/full/images/loader.gif" class="settings-loader"/>');

    $.ajax({
        url: App.url + 'settings/deleteavatar',
        cache: false,
        dataType: 'json',
        method: 'POST',
        success: function (data) {
            $('#settings-message').remove();
            if(data.image_url) {
                $('#settings-avatar').attr('src', data.image_url);
                $('#topbar-avatar').attr('src', data.image_url);
                $('#settings-delete-avatar').attr('disabled', 'disabled');
            }
            else if(data.error) {
                $('.panel-default').before('<div class="alert alert-danger" id="settings-message"><b>' + language['error'] + '</b></div>');
                queue = setTimeout(function() {
                    $('#settings-message').fadeOut();
                }, 12000);
            }
        },
        complete: function() {
            setTimeout(function() {
                $('.settings-loader').fadeOut();
            }, 3000);
        }
    });
}
$(function() {
	$('#settings-avatar-upload').bind('fileuploaddone', function (e, data) {
		$('#settings-message').remove();
		if(data.result.image_url) {
			$('#settings-avatar').attr('src', data.result.image_url);
			$('#topbar-avatar').attr('src', data.result.image_url);
			$('#settings-delete-avatar').removeAttr('disabled');
			return;
		}
		if(data.result.error) {
			$('.panel-default').before('<div class="alert alert-warning" id="settings-message">' + data.result.error + '</div>');
			queue = setTimeout(function() {
                $('#settings-message').fadeOut();
            }, 12000);
            $('body, html').animate({
                scrollTop: $("#settings-message").offset().top - parseInt($("#settings-message").css('height')) * 1.2
            }, 200);
		}
	});


	$(document).bind('dragover', function (e) {
	    var dropZone = $('#settings-avatar-dropzone'),
	        timeout = window.dropZoneTimeout;
	    if (timeout) {
	        clearTimeout(timeout);
	    }
	    dropZone.addClass('fileupload-dropzone-hover');
	    $('.drop-text').css('display', 'block');
	    $('.drop-text').css('opacity', '1');
	    $('.drop-text-icon').css('display', 'block');
	    $('.drop-text-icon').css('opacity', '0.6');
	    window.dropZoneTimeout = setTimeout(function () {
	        window.dropZoneTimeout = null;
            dropZone.removeClass('fileupload-dropzone-hover');
            $('.drop-text').css('display', 'none');
            $('.drop-text-icon').css('display', 'none');
	    }, 1000);
	});
});
</script>
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-camera"></span> <?php echo $this->lang->line('topbar_settings_avatar');?></div>
        <div id="settings-body">
	        <div class="panel-body">

	        <form class="form-horizontal" id="settings-avatar-upload" method="POST" action="<?php echo site_url('settings/avatar');?>" role="form" enctype="multipart/form-data">

			<div class="row">
		        <div class="col-sm-9" style="margin-bottom:10px;">
			            <div class="fileinput-button fileupload-dropzone" id="settings-avatar-dropzone">
			            	<div class="drop-text-icon">
				            	<span class="fa-stack fa-lg fa-5x">
						          <i class="fa fa-circle fa-stack-2x"></i>
						          <i class="fa fa-camera-retro fa-stack-1x fa-inverse"></i>
						        </span>
					    	</div>
			            	<span class="drop-text"> <?php echo $this->lang->line('fileupload_drop_text');?></span>
				            <a href="#" class="btn btn-default btn-fileupload">
				                <span><?php echo $this->lang->line('settings_choose_pic');?></span>
				               	<input type="file" name="settings_upload_avatar" multiple/>
				            </a>
				            <span class="help-block drag-text"><?php echo $this->lang->line('settings_drag_n_drop');?></span>
				            <span class="text-muted image-size"><?php printf($this->lang->line('settings_avatar_info'), $this->config->item('avatar_max_size'));?></span>
			            </div>
		        </div>

		        <div class="col-sm-3">
		        	<div style="max-width:180px;left:0;right:0;margin-left:auto;margin-right:auto;">
				        <img src="<?php echo $this->vinc_auth->getAvatar();?>" id="settings-avatar" alt="<?php echo $this->vinc_auth->_('login');?>" class="img-thumbnail">
				        <a href="#" onclick="deleteAvatar(); return false;" id="settings-delete-avatar" class="btn btn-warning"<?php if(is_null($l = $this->vinc_auth->_('photo_avatar')) || empty($l)):?> disabled="disabled"<?php endif;?>><span class="glyphicon glyphicon-trash"></span> <?php echo $this->lang->line('delete');?></a>
			    	</div>
		        </div>
		    </div>

			</form>

	        </div>
	    </div>
    </div>
</div>
</div>