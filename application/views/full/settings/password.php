<div class="col-md-9">
	<?php if($this->session->flashdata('settings_success')):?>
		<div class="alert alert-success" id="settings-message"><b><?php echo $this->session->flashdata('settings_success');?></b></div>
	<?php elseif(is_array($data) && isset($data['error'])):?>
		<div class="alert alert-danger" id="settings-message"><b><?php echo $data['error'];?></b></div>
	<?php endif;?>

    <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-tower"></span> <?php echo $this->lang->line('topbar_settings_password');?></div>
        <div id="settings-body">
	        <div class="panel-body">
	        <form class="form-horizontal" method="POST" action="<?php echo site_url('settings/password');?>" role="form">

	        	<?php if( $this->config->item('old_password_required')
	        		   && (is_null($l = $this->vinc_auth->_('social_login'))
	        		   || empty($l)
	        		   || $this->vinc_auth->_('last_change') > 0)):?>
	        	<div class="form-group">
			    	<label for="settings-old-password" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_old_password');?>:</label>
					<div class="col-sm-10">

	            	<input type="password" id="settings-old-password" name="settings_old_password" class="form-control" placeholder="<?php echo $this->lang->line('settings_old_password');?>">

					</div>
				</div>

				<hr/>
				<?php endif;?>

				<div class="form-group">
				    <label for="settings-new-password" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_new_password');?>:</label>
					<div class="col-sm-10">

		            <input type="password" id="settings-new-password" name="settings_new_password" class="form-control" placeholder="<?php echo $this->lang->line('settings_new_password');?>">

					</div>
				</div>

				<div class="form-group">
			    	<label for="settings-repeat-password" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_repeat_password');?>:</label>
					<div class="col-sm-10">

		            <input type="password" id="settings-repeat-password" name="settings_repeat_password" class="form-control" placeholder="<?php echo $this->lang->line('settings_repeat_password');?>">

					</div>
				</div>

				<hr/>

				<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			    
			    <button type="submit" id="password-save" class="btn btn-primary"><?php echo $this->lang->line('settings_change');?></button>
				
				</div>

			</div>
			</div>
			</form>
	        </div>
	    </div>
    </div>
</div>
</div>