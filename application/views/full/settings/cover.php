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
function deleteCover()
{
    $('.settings-loader').remove();
    $('.panel-heading').prepend('<img src="../public/templates/full/images/loader.gif" class="settings-loader"/>');

    $.ajax({
        url: App.url + 'settings/deletecover',
        cache: false,
        dataType: 'json',
        method: 'POST',
        success: function (data) {
            $('#settings-message').remove();
            if(data.image_url) {
                $('#settings-cover').attr('src', data.image_url);
                $('#settings-delete-cover').attr('disabled', 'disabled');
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
	$('#settings-cover-upload').bind('fileuploaddone', function (e, data) {
		$('#settings-message').remove();
		if(data.result.image_url) {
			$('#settings-cover').attr('src', data.result.image_url);
			$('#settings-delete-cover').removeAttr('disabled');
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
	    var dropZone = $('#settings-cover-dropzone'),
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
        <div class="panel-heading"><span class="glyphicon glyphicon-picture"></span> <?php echo $this->lang->line('topbar_settings_cover');?></div>
        <div id="settings-body">
	        <div class="panel-body">

			<form class="form-horizontal" id="settings-cover-upload" method="POST" action="<?php echo site_url('settings/cover');?>" role="form" enctype="multipart/form-data">

		    <div class="row">
		        <div class="col-sm-5" style="margin-bottom:15px;">
		        	<a href="#" onclick="deleteCover(); return false;" class="btn btn-danger" id="settings-delete-cover"<?php if(is_null($l = $this->vinc_auth->_('photo_cover')) || empty($l)):?> disabled="disabled"<?php endif;?>><span class="glyphicon glyphicon-trash"></span> <?php echo $this->lang->line('delete');?></a>
				    <img src="<?php echo $this->vinc_auth->getCover();?>" id="settings-cover" alt="<?php echo $this->vinc_auth->_('login');?>" class="img-rounded">
		        </div>

		        <div class="col-sm-7">
			            <div class="fileinput-button fileupload-dropzone" id="settings-cover-dropzone">
			            	<div class="drop-text-icon" style="top:30%;">
				            	<span class="fa-stack fa-lg fa-5x">
						          <i class="fa fa-circle fa-stack-2x"></i>
						          <i class="fa fa-camera-retro fa-stack-1x fa-inverse"></i>
						        </span>
					    	</div>
			            	<span class="drop-text" style="top:41%;"> <?php echo $this->lang->line('fileupload_drop_text');?></span>
				            <a href="#" class="btn btn-info btn-fileupload" style="margin-top: 66px;">
				                <span><?php echo $this->lang->line('settings_choose_pic');?></span>
				               	<input type="file" name="settings_upload_cover" multiple/>
				            </a>
				            <span class="help-block drag-text"><?php echo $this->lang->line('settings_drag_n_drop');?></span>
				            <span class="text-muted image-size"><?php printf($this->lang->line('settings_avatar_info'), $this->config->item('cover_max_size'));?></span>
			            </div>
		        </div>
		    </div>

			</form>

	        </div>
	    </div>
    </div>
</div>
</div>