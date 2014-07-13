<?php
	$segment = $this->uri->segment(2);
?>
<div class="row">
    <div class="col-md-3">
        <div class="list-group settings-left-list">
        	<a href="<?php echo site_url('settings/account');?>" data-title="<?php echo $this->lang->line('topbar_settings_account') . $this->config->item('site_name');?>" data-settings="account" class="list-group-item<?php if($segment == 'account'):?> active<?php endif;?>">
        		<span class="glyphicon glyphicon-cog"></span> <?php echo $this->lang->line('topbar_settings_account');?>
        	</a>
			<a href="<?php echo site_url('settings/notifications');?>" data-title="<?php echo $this->lang->line('topbar_settings_not') . $this->config->item('site_name');?>" data-settings="notifications" class="list-group-item<?php if($segment == 'notifications'):?> active<?php endif;?>">
				<span class="glyphicon glyphicon-bell"></span> <?php echo $this->lang->line('topbar_settings_not');?>
			</a>
			<a href="<?php echo site_url('settings/avatar');?>" class="list-group-item<?php if($segment == 'avatar'):?> active<?php endif;?>">
				<span class="glyphicon glyphicon-camera"></span> <?php echo $this->lang->line('topbar_settings_avatar');?>
			</a>
			<a href="<?php echo site_url('settings/cover');?>" class="list-group-item<?php if($segment == 'cover'):?> active<?php endif;?>">
				<span class="glyphicon glyphicon-picture"></span> <?php echo $this->lang->line('topbar_settings_cover');?>
			</a>
			<?php if($this->config->item('allow_change_password')):?>
				<a href="<?php echo site_url('settings/password');?>" data-title="<?php echo $this->lang->line('topbar_settings_password') . $this->config->item('site_name');?>" data-settings="password" class="list-group-item<?php if($segment == 'password'):?> active<?php endif;?>">
					<span class="glyphicon glyphicon-tower"></span> <?php echo $this->lang->line('topbar_settings_password');?>
				</a>
			<?php endif;?>
			<?php if($this->config->item('allow_social_profiles')):?>
				<a href="<?php echo site_url('settings/social');?>" data-title="<?php echo $this->lang->line('topbar_settings_social') . $this->config->item('site_name');?>" data-settings="social" class="list-group-item<?php if($segment == 'social'):?> active<?php endif;?>">
					<span class="glyphicon glyphicon-th-large"></span> <?php echo $this->lang->line('topbar_settings_social');?>
				</a>
			<?php endif;?>
			<?php if(!$this->vinc_auth->_('confirmed') && $this->config->item('signup_confirm_email')):?>
				<a href="<?php echo site_url('settings/activate');?>" data-title="<?php echo $this->lang->line('topbar_settings_activate') . $this->config->item('site_name');?>" data-settings="activate" class="list-group-item<?php if($segment == 'activate'):?> active<?php endif;?>">
					<span class="glyphicon glyphicon-envelope"></span> <?php echo $this->lang->line('topbar_settings_activate');?>
				</a>
			<?php endif;?>
		</div>
    </div>