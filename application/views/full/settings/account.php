<div class="col-md-9">
	<?php if($this->session->flashdata('settings_success')):?>
		<div class="alert alert-success" id="settings-message"><b><?php echo $this->session->flashdata('settings_success');?></b></div>
	<?php elseif(is_array($data) && isset($data['error'])):?>
		<div class="alert alert-danger" id="settings-message"><b><?php echo $data['error'];?></b></div>
	<?php endif;?>

    <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-cog"></span> <?php echo $this->lang->line('topbar_settings_account');?></div>
        <div id="settings-body">
	        <div class="panel-body">
	        <form class="form-horizontal" method="POST" action="<?php echo site_url('settings/account');?>" role="form">

	        <?php if($this->config->item('allow_change_language_settings')):?>
			<div class="form-group">
				<label for="settings-language" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_language');?>:</label>
				<div class="col-sm-10">

	            <select name="settings_language" class="form-control selecter-s-20" id="settings-language">
		            <?php $lang_list = $this->config->item('languages');
		           		  if(array_key_exists($this->vinc_auth->_('language'), $lang_list)):?>
		           		<option value="<?php echo $this->vinc_auth->_('language');?>"><?php echo $lang_list[$this->vinc_auth->_('language')]['native'];?></option>
		           		<?php unset($lang_list[$this->vinc_auth->_('language')]);endif;?>
	           		<?php foreach ($lang_list as $key => $value):?>
						<option value="<?php echo $key;?>"><?php echo $value['native'];?></option>
					<?php endforeach;?>
				</select>

				</div>
			</div>
			<?php endif;?>

			<?php if($this->config->item('allow_change_timezone')):?>
			<div class="form-group">
				<label for="settings-timezone" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_timezone');?>:</label>
				<div class="col-sm-10">

	            <select name="settings_timezone" class="form-control selecter-s-80" id="settings-timezone">
		            <?php $timezone_list = my_timezones();
		          		  if(array_key_exists($this->vinc_auth->_('timezone'), $timezone_list)):?>
		            	<option value="<?php echo $this->vinc_auth->_('timezone');?>"><?php echo $timezone_list[$this->vinc_auth->_('timezone')];?></option>
		            	<?php unset($timezone_list[$this->vinc_auth->_('timezone')]);endif;?>
	            	<?php foreach ($timezone_list as $key => $value):?>
						<option value="<?php echo $key;?>"><?php echo $value;?></option>
					<?php endforeach;?>
				</select>

				</div>
			</div>
			<?php endif;?>

			<?php if(!is_null($d = $this->vinc_auth->_('born_date')) && !empty($d)): list($day, $month, $year) = explode('.', $d);endif;?>
			<div class="form-group">
				<label for="settings-day" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_born');?>:</label>
				<div class="col-sm-10">
					      
		        <select name="settings_born_day" id="settings-day" class="form-control selecter-cover selecter-s-10 selecter-inline">
					<?php for($i = 1; $i <= 31; ++$i):?>
						<option value="<?php echo $i;?>"<?php if(isset($day) && $i == $day):?> selected<?php endif;?>><?php echo $i;?></option>
					<?php endfor;?>
				</select>

				<select name="settings_born_month" id="settings-month" class="form-control selecter-cover selecter-s-20 selecter-inline">
					<?php for($i = 1; $i <= 12; ++$i):?>
						<option value="<?php echo $i;?>"<?php if(isset($month) && $i == $month):?> selected<?php endif;?>><?php echo $this->lang->line('settings_month_' . $i);?></option>
					<?php endfor;?>
				</select>

				<select name="settings_born_year" id="settings-year" class="form-control selecter-cover selecter-s-20 selecter-inline">
					<?php for($i = date('Y'); $i >= 1900; --$i):?>
						<option value="<?php echo $i;?>"<?php if(isset($year) && $i == $year):?> selected<?php endif;?>><?php echo $i;?></option>
					<?php endfor;?>
				</select>
					    
				</div>
			</div>

			<div class="form-group">
				<label for="settings-show-date" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_show_date');?>:</label>
				<div class="col-sm-10">
					      
		        <select name="settings_show_date" id="settings-show-date" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('show_born_date')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				
				</div>
			</div>

			<div class="form-group">
				<label for="settings-gender" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_gender');?>:</label>
				<div class="col-sm-10">

		        <select name="settings_gender" id="settings-gender" class="form-control selecter-cover selecter-s-20 selecter-inline">
		        	<?php if($this->vinc_auth->_('gender') == 0):?>
					<option value="0" selected><?php echo $this->lang->line('settings_gender_0');?></option>
					<option value="1"><?php echo $this->lang->line('settings_gender_1');?></option>
					<option value="2"><?php echo $this->lang->line('settings_gender_2');?></option>
					<?php elseif($this->vinc_auth->_('gender') == 1):?>
					<option value="0"><?php echo $this->lang->line('settings_gender_0');?></option>
					<option value="1" selected><?php echo $this->lang->line('settings_gender_1');?></option>
					<option value="2"><?php echo $this->lang->line('settings_gender_2');?></option>
					<?php else:?>
					<option value="0"><?php echo $this->lang->line('settings_gender_0');?></option>
					<option value="1"><?php echo $this->lang->line('settings_gender_1');?></option>
					<option value="2" selected><?php echo $this->lang->line('settings_gender_2');?></option>
					<?php endif;?>
				</select>
				
				</div>
			</div>

			<hr/>

			<?php if($this->config->item('allow_change_email')):?>
			<div class="form-group">
				<label for="settings-email" class="col-sm-2 control-label">Email:</label>
				<div class="col-sm-10">

	            <input type="text" autocomplete="off" id="settings-email" name="settings_email" class="form-control" placeholder="Email" value="<?php echo $this->vinc_auth->_('email');?>">

				</div>
			</div>
			<?php endif;?>

			<div class="form-group">
				<label for="settings-show-email" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_show_email');?>:</label>
				<div class="col-sm-10">
					      
		        <select name="settings_show_email" id="settings-show-email" class="form-control selecter-cover selecter-s-10 selecter-inline">
		        	<?php if($this->vinc_auth->_('show_email')):?>
					<option value="1" selected><?php echo $this->lang->line('yes');?></option>
					<option value="0"><?php echo $this->lang->line('no');?></option>
					<?php else:?>
					<option value="1"><?php echo $this->lang->line('yes');?></option>
					<option value="0" selected><?php echo $this->lang->line('no');?></option>
					<?php endif;?>
				</select>
				
				</div>
			</div>

			<div class="form-group">
			    <label for="settings-location" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_location');?>:</label>
				<div class="col-sm-10">

	            <input type="text" autocomplete="off" id="settings-location" name="settings_location" class="form-control" placeholder="<?php echo $this->lang->line('settings_location');?>" value="<?php echo $this->vinc_auth->_('location');?>">

				</div>
			</div>

			<?php if($this->config->item('allow_change_login')):?>
			<div class="form-group">
				<label for="settings-login" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_login');?>:</label>
			    <div class="col-sm-10">

	            <input type="text" autocomplete="off" id="settings-login" name="settings_login" class="form-control" placeholder="<?php echo $this->lang->line('settings_login');?>" value="<?php echo $this->vinc_auth->_('login');?>">

				</div>
			</div>
			<?php endif;?>

			<div class="form-group">
			    <label for="settings-name" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_name');?>:</label>
				<div class="col-sm-10">

	            <input type="text" autocomplete="off" id="settings-name" name="settings_name" class="form-control" placeholder="<?php echo $this->lang->line('settings_name');?>" value="<?php echo $this->vinc_auth->_('real_name');?>">

				</div>
			</div>

			<?php if($this->config->item('allow_about')):?>
			<hr/>
			<div class="form-group">
				<label for="settings-about" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_about');?>:</label>
				<div class="col-sm-10">

		        <textarea class="form-control" name="settings_about" style="min-height:80px;" placeholder="<?php echo $this->lang->line('settings_about');?>" id="settings-about"><?php echo $this->vinc_auth->_('about');?></textarea>

				</div>
			</div>
			<?php endif;?>

			<?php if($this->config->item('allow_website')):?>
			<hr/>

			<div class="form-group">
				<label for="settings-website" class="col-sm-2 control-label"><?php echo $this->lang->line('settings_website');?>:</label>
				<div class="col-sm-10">

				<div class="input-group">
				  <span class="input-group-addon">http://</span>
				  <input type="text" autocomplete="off" id="settings-website" name="settings_website" class="form-control" placeholder="<?php echo $this->lang->line('settings_website');?>" value="<?php echo $this->vinc_auth->_('website');?>">
				</div>

				</div>
			</div>
			<?php endif;?>

			<hr/>
			
			<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			    
			    <button type="submit" id="account-save" class="btn btn-success"><?php echo $this->lang->line('settings_save');?></button>
				
				</div>
			</div>
			</form>
	        </div>
	    </div>
    </div>
</div>
</div>