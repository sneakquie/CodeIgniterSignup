<div class="col-md-9">
	<?php if($this->session->flashdata('settings_activate_success')):?>
		<div class="alert alert-success" id="settings-message"><b><?php echo $this->session->flashdata('settings_activate_success');?></b></div>
	<?php endif;?>

    <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-envelope"></span> <?php echo $this->lang->line('topbar_settings_activate');?></div>
        <div id="settings-body">
	        <div class="panel-body">
	        <form class="form-horizontal" method="POST" action="<?php echo site_url('settings/activate');?>" role="form">

			<div class="alert alert-info" style="font-size:13px;"><b><?php echo $this->lang->line('settings_activate_info');?></b></div>
			<hr/>
			
			<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			    
			    <button type="submit" id="activate-send" name="activate_send" class="btn btn-default"><?php echo $this->lang->line('settings_send');?></button>
				
				</div>
			</div>

			</form>
	        </div>
	    </div>
    </div>
</div>
</div>