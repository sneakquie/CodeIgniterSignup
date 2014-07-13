<body>
<nav class="navbar navbar-fixed-top navbar-default" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo site_url();?>" style="float:center;"><img src="http://s.pikabu.ru/images/logo2013.png" height="40" style="margin-top:5px;"/></a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <?php if($this->config->item('show_categories')):?>
      <ul class="nav navbar-nav">
        <li class="dropdown border-bottom">
          <a href="<?php echo site_url('categories');?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-folder-open"></i> <?php echo $this->lang->line('topbar_categories');?> <b class="hidden-md hidden-lg hidden-sm caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Категория №1</a></li>
            <li><a href="#">Категория №2</a></li>
          </ul>
        </li>
      </ul>
      <?php endif;?>

      <ul class="nav navbar-nav navbar-right">
      <?php if($this->config->item('allow_search')):?>
      <li class="hidden-md hidden-lg hidden-sm"><a href="<?php echo site_url('search');?>"><span class="glyphicon glyphicon-search"></span> <?php echo $this->lang->line('topbar_search_placeholder');?></a></li>
      <form class="navbar-form navbar-left hidden-sm hidden-xs" role="search" id="topbar-search-menu" method="GET" action="<?php echo site_url('search');?>">
        <div class="form-group input-group">
          <input type="text" name="q" id="search-input" autocomplete="off" class="form-control" placeholder="<?php echo $this->lang->line('topbar_search_placeholder');?>"<?php if($this->config->item('allow_speech_search')):?> x-webkit-speech<?php endif;?>>
          <span class="input-group-btn" style="width:auto;">
            <button class="btn btn-default btn-sm" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            <ul class="dropdown-menu dropdown-menu-arrow list-group dropdown-search">
              <a href="#" class="list-group-item">Cras justo odio</a>
              <a href="#" class="list-group-item">Cras justo odio</a>
              <a href="#" class="list-group-item">Cras justo odio</a>
            </ul>
          </span>
        </div>
      </form>
      <?php endif;?>

      <?php if($this->vinc_auth->logged()):?>
            <li class="border-bottom hidden-xs">
                <a href="<?php echo $this->vinc_auth->userURL($this->vinc_auth->_('login'));?>">
                  <img src="<?php echo $this->vinc_auth->getAvatar();?>" id="topbar-avatar" alt="<?php echo $this->vinc_auth->_('login');?>" class="img-circle profile-image">
                  <?php if($this->config->item('max_login_length') > 0):?>
                  <?php echo strlen($this->vinc_auth->_('login')) > $this->config->item('max_login_length') ? substr($this->vinc_auth->_('login'), 0, $this->config->item('max_login_length')) . '...' : $this->vinc_auth->_('login');?>
                  <?php else: echo $this->vinc_auth->_('login'); endif;?>
                </a>
            </li>
            <li class="border-bottom hidden-xs<?php if($this->uri->segment(1) == 'post' && $this->uri->segment(2) == 'upload'):?> active<?php endif;?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('topbar_upload');?>"><a href="<?php echo site_url('post/upload');?>"><i class="fa fa-cloud-upload"></i></a></li>
            <li class="border-bottom hidden-xs<?php if($this->uri->segment(1) == 'favs'):?> active<?php endif;?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('topbar_favs');?>"><a href="<?php echo site_url('favs');?>"><i class="fa fa-star"></i></a></li>

            <?php if($this->config->item('allow_messages')):?>
            <li class="border-bottom hidden-xs<?php if($this->uri->segment(1) == 'im'):?> active<?php endif;?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('topbar_messages');?>"><a href="<?php echo site_url('im');?>"><i class="fa fa-envelope"></i> <span class="badge" style="position: absolute;font-size:10px;background-color: #D9534F;padding: 3px 4px;top: 7px;left: 23px;">7</span></a></li>
            <?php endif;?>

            <li class="border-bottom hidden-xs dropdown<?php if($this->uri->segment(1) == 'notifications'):?> active<?php endif;?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('topbar_not');?>">
              <a href="<?php echo site_url('notifications');?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <span class="badge" style="position: absolute;font-size:10px;background-color: #D9534F;padding: 3px 4px;top: 7px;left: 23px;">4</span></a>
              <ul class="dropdown-menu dropdown-menu-arrow list-group">
                <li class="list-group-item">Cras justo odio</li>
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
                <li class="list-group-item">Porta ac consectetur ac</li>
                <li class="list-group-item">Vestibulum at eros</li>
              </ul>
            </li>

            <li class="border-bottom hidden-xs dropdown<?php if($this->uri->segment(1) == 'settings'):?> active<?php endif;?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('topbar_settings');?>">
              <a href="<?php echo site_url('settings/account');?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo site_url('settings/account');?>"><?php echo $this->lang->line('topbar_settings_account');?></a></li>
                <li><a href="<?php echo site_url('settings/notifications');?>"><?php echo $this->lang->line('topbar_settings_not');?></a></li>
                <li><a href="<?php echo site_url('settings/avatar');?>"><?php echo $this->lang->line('topbar_settings_avatar');?></a></li>
                <li><a href="<?php echo site_url('settings/cover');?>"><?php echo $this->lang->line('topbar_settings_cover');?></a></li>
                <?php if($this->config->item('allow_change_password')):?><li><a href="<?php echo site_url('settings/password');?>"><?php echo $this->lang->line('topbar_settings_password');?></a></li><?php endif;?>
                <?php if($this->config->item('allow_social_profiles')):?><li><a href="<?php echo site_url('settings/social');?>"><?php echo $this->lang->line('topbar_settings_social');?></a></li><?php endif;?>
                <?php if(!$this->vinc_auth->_('confirmed') && $this->config->item('signup_confirm_email')):?><li><a href="<?php echo site_url('settings/activate');?>"><?php echo $this->lang->line('topbar_settings_activate');?></a></li><?php endif;?>
              </ul>
            </li>

            <li class="border-bottom hidden-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('topbar_logout');?>"><a href="<?php echo site_url('auth/logout/' . $this->vinc_auth->_('token'));?>/"><i class="fa fa-power-off"></i></a></li>

            <li class="hidden-md hidden-lg hidden-sm">
                <a href="<?php echo $this->vinc_auth->userURL($this->vinc_auth->_('login'));?>">
                  <img src="<?php echo $this->vinc_auth->getAvatar();?>" alt="<?php echo $this->vinc_auth->_('login');?>" class="img-circle profile-image">
                  <?php echo $this->vinc_auth->_('login');?>
                </a>
            </li>
            <li class="dropdown hidden-md hidden-lg hidden-sm">
              <a href="<?php echo site_url('settings/account');?>" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> <?php echo $this->lang->line('topbar_settings');?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo site_url('settings/account');?>"><?php echo $this->lang->line('topbar_settings_account');?></a></li>
                <li><a href="<?php echo site_url('settings/notifications');?>"><?php echo $this->lang->line('topbar_settings_not');?></a></li>
                <li><a href="<?php echo site_url('settings/avatar');?>"><?php echo $this->lang->line('topbar_settings_avatar');?></a></li>
                <li><a href="<?php echo site_url('settings/cover');?>"><?php echo $this->lang->line('topbar_settings_cover');?></a></li>
                <?php if($this->config->item('allow_change_password')):?><li><a href="<?php echo site_url('settings/password');?>"><?php echo $this->lang->line('topbar_settings_password');?></a></li><?php endif;?>
                <?php if($this->config->item('allow_social_profiles')):?><li><a href="<?php echo site_url('settings/social');?>"><?php echo $this->lang->line('topbar_settings_social');?></a></li><?php endif;?>
                <?php if(!$this->vinc_auth->_('confirmed') && $this->config->item('signup_confirm_email')):?><li><a href="<?php echo site_url('settings/activate');?>"><?php echo $this->lang->line('topbar_settings_activate');?></a></li><?php endif;?>
              </ul>
            </li>
            <li class="hidden-md hidden-lg hidden-sm"><a href="<?php echo site_url('auth/logout/' . $this->vinc_auth->_('token'));?>"><span class="glyphicon glyphicon-off"></span> <?php echo $this->lang->line('topbar_logout');?></a></li>
      <?php else:?>
            <li class="border-bottom hidden-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('topbar_upload');?>"><a href="<?php echo site_url('auth/login');?>"><i class="fa fa-cloud-upload"></i></a></li>
            <li class="hidden-md hidden-lg hidden-sm"><a href="<?php echo site_url('auth/login');?>"><i class="fa fa-cloud-upload"></i> <?php echo $this->lang->line('topbar_upload');?></a></li>

            <?php if($this->config->item('allow_change_language_menu')):?>
            <?php $languages_html = '';
                  foreach ($this->config->item('languages') as $key => $value):?>
                <?php $languages_html .= '<li><a href="' . site_url('language/set/' . $key) . '" onclick="changeLanguage(\'' . $key . '\'); return false;" style="font-size:13px;"><img style="border:0.8pt solid #ddd;" src="' . site_url('public/images/' . $key . '-icon.png') . '"/> ' . $value['native'] . '</a></li>';?>
            <?php endforeach;?>

            <li class="dropdown border-bottom hidden-xs">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img style="border:0.8pt solid #ddd;" src="<?php echo site_url('public/images/' . $this->application->_('language') . '-icon.png');?>"/></a>
              <ul class="dropdown-menu">
                  <?php echo $languages_html;?>
              </ul>
            </li>

            <li class="dropdown hidden-md hidden-lg hidden-sm">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i> <?php echo $this->application->_('native');?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <?php echo $languages_html;?>
              </ul>
            </li>
            <?php endif;?>

            <?php if($this->uri->segment(2) != 'login'):?>
              <li class="hidden-md hidden-lg hidden-sm"><a href="<?php echo site_url('auth/login');?>"><?php echo $this->lang->line('signin');?></a></li>
              <li class="border-bottom hidden-xs"><a href="<?php echo site_url('auth/login');?>"><?php echo $this->lang->line('signin');?></a></li>
            <?php endif;?>

            <?php if($this->uri->segment(2) != 'signup'):?>
            <li class="hidden-md hidden-lg hidden-sm"><a href="<?php echo site_url('auth/signup');?>"><?php echo $this->lang->line('signup');?></a></li>
              <li class="border-bottom hidden-xs"><a href="<?php echo site_url('auth/signup');?>"><?php echo $this->lang->line('signup');?></a></li>
            <?php endif;?>
      <?php endif;?>
      </ul>
    </div>
  </div>
</nav>
<div id="wrapper">
<?php if($this->config->item('show_js_required')):?>
<noscript>
    <div class="alert alert-info" style="font-size:13px;border-right: 0;border-left: 0;border-radius:0;height:auto;padding:12px;margin:0;"><?php echo $this->lang->line('topbar_js_message');?></div>
</noscript>
<?php endif;?>
  <?php if($this->config->item('show_confirm_message') && $this->uri->segment(2) !== 'confirm' && $this->vinc_auth->logged() && !$this->vinc_auth->_('confirmed') && $this->config->item('signup_confirm_email')):?>
<div class="alert alert-warning" style="font-size:13px;border-right: 0;border-left: 0;border-radius:0;height:auto;padding:12px;margin:0;"><?php printf($this->lang->line('topbar_confirm_message'), site_url('settings/activate'));?></div>
<?php endif;?>
<div class="container" style="padding-top:20px;">