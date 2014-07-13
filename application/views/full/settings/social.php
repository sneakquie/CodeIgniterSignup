<div class="col-md-9">
	<?php if($this->session->flashdata('settings_success')):?>
		<div class="alert alert-success" id="settings-message"><b><?php echo $this->session->flashdata('settings_success');?></b></div>
	<?php endif;?>

    <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-th-large"></span> <?php echo $this->lang->line('topbar_settings_social');?></div>
        <div id="settings-body">
	        <div class="panel-body">
	        <form class="form-horizontal" method="POST" action="<?php echo site_url('settings/social');?>" role="form">
	        	<?php foreach($this->config->item('profile_social_networks') as $key => $value):?>
	        		<div class="form-group">
					    <label for="settings-social-<?php echo $key;?>" class="col-sm-2 control-label"><i class="fa fa-<?php echo $key;?> btn-<?php echo $key;?>" style="min-width:20px;padding:5px;border-radius:3px;border:1px solid #ddd;"></i> <?php echo $this->lang->line($key);?>:</label>
						<div class="col-sm-10">

						<?php if(is_null($n = $this->vinc_auth->_('network_' . $key))
							   || empty($n)):?>

			            <input type="text" data-url="<?php echo $value;?>" autocomplete="off" onfocusout="changeSocialInput(this, '<?php echo $key;?>'); return false;" id="settings-social-<?php echo $key;?>" name="settings_social_<?php echo $key;?>" class="form-control" placeholder="<?php echo $this->lang->line($key);?>" value="<?php echo $n;?>">
			        	<p class="form-control-static">
			        		<a href="#" class="settings-social-link" onclick="changeSocialField(this, '<?php echo $key;?>'); return false;" style="display:none;"><?php echo $value . $n;?></a>
			        	</p>

			        	<?php else:?>

			            <input type="text" data-url="<?php echo $value;?>" autocomplete="off" style="display:none;" onfocusout="changeSocialInput(this, '<?php echo $key;?>'); return false;" id="settings-social-<?php echo $key;?>" name="settings_social_<?php echo $key;?>" class="form-control" placeholder="<?php echo $this->lang->line($key);?>" value="<?php echo $n;?>">
			        	<p class="form-control-static">
			        		<a href="#" class="settings-social-link" onclick="changeSocialField(this, '<?php echo $key;?>'); return false;"><?php echo $value . $n;?></a>
			        	</p>

			        	<?php endif;?>

						</div>
					</div>
	        	<?php endforeach;?>
	        	<hr/>
				
				<div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				    
				    <button type="submit" id="social-save" name="social_save" class="btn btn-success"><?php echo $this->lang->line('settings_save');?></button>
					
					</div>
				</div>
			</form>
	        </div>
	    </div>
    </div>
</div>
</div>