<div class="col-md-9">
	<?php if($this->session->flashdata('settings_success')):?>
		<div class="alert alert-success" id="settings-message"><b><?php echo $this->session->flashdata('settings_success');?></b></div>
	<?php endif;?>

    <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-bell"></span> <?php echo $this->lang->line('topbar_settings_not');?></div>
        <div id="settings-body">
	        <div class="panel-body">
	        <form class="form-horizontal" method="POST" action="<?php echo site_url('settings/notifications');?>" role="form">


			<?php if($this->config->item('last_login_show') == 2):?>
			<div class="form-group">
				<label for="settings-last-login" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_last_login');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_last_login" id="settings-last-login" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('show_last_login')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				<p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('settings_last_login_info');?></p>
				</div>
			</div>
			<?php endif;?>

			<?php if($this->config->item('allow_contact_email') == 2):?>
			<div class="form-group">
				<label for="settings-allow-email" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_allow_email');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_allow_email" id="settings-allow-email" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('allow_email')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				<p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('settings_allow_email_info');?></p>
				</div>
			</div>
			<?php endif;?>

			<hr/>

			<div class="form-group">
				<label for="settings-notify-comments" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_notify_comments');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_notify_comments" id="settings-notify-comments" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('notify_comments')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				<p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('settings_notify_comments_info');?></p>
				
				</div>
			</div>

			<div class="form-group">
				<label for="settings-notify-comments-email" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_notify_comments_email');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_notify_comments_email" id="settings-notify-comments-email" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('notify_comments_email')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				
				</div>
			</div>

			<hr/>

			<div class="form-group">
				<label for="settings-notify-answers" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_notify_answers');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_notify_answers" id="settings-notify-answers" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('notify_answers')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				<p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('settings_notify_answers_info');?></p>
				
				</div>
			</div>

			<div class="form-group">
				<label for="settings-notify-answers-email" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_notify_answers_email');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_notify_answers_email" id="settings-notify-answers-email" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('notify_answers_email')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				
				</div>
			</div>

			<hr/>

			<div class="form-group">
				<label for="settings-notify-messages" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_notify_messages');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_notify_messages" id="settings-notify-messages" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('notify_messages')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				<p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('settings_notify_messages_info');?></p>
				
				</div>
			</div>

			<div class="form-group">
				<label for="settings-notify-messages-email" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_notify_messages_email');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_notify_messages_email" id="settings-notify-messages-email" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('notify_messages_email')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				
				</div>
			</div>

			<hr/>

			<div class="form-group">
				<label for="settings-notify-follow" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_notify_follow');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_notify_follow" id="settings-notify-follow" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('notify_follow_news')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				<p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('settings_notify_follow_info');?></p>
				
				</div>
			</div>

			<div class="form-group">
				<label for="settings-notify-cats" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_notify_cats');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_notify_cats" id="settings-notify-cats" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('notify_cats_news')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				<p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('settings_notify_cats_info');?></p>
				
				</div>
			</div>

			<hr/>

			<div class="form-group">
				<label for="settings-notify-likes" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_notify_likes');?>:</label>
				<div class="col-sm-9">

		        <select name="settings_notify_likes" id="settings-notify-likes" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('notify_likes')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				
				</div>
			</div>

			<hr/>
			
			<div class="form-group">
			    <div class="col-sm-offset-3 col-sm-9">
			    
			    <button type="submit" id="notifications-save" class="btn btn-success"><?php echo $this->lang->line('settings_save');?></button>
				
				</div>
			</div>

			</form>
	        </div>
	    </div>
    </div>
</div>
</div>