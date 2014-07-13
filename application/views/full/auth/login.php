<link href="/public/templates/full/css/animated.min.css" rel="stylesheet">
<style type="text/css">
body {
  position: static;
  display: block;
  width: 100%;
  height: auto;
  min-height: 600px;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  margin: 0px auto -60px 0px;
  padding: 0px 0px 60px 0px;
  background-image: url('/public/templates/full/images/background_<?php echo rand(1, 15);?>.jpg');
}
</style>
<script type="text/javascript">
$(function() {
    $('#login-username').focus();
});
</script>
        <div id="content">
            <div class="header-image">
                <div class="container">
                    <div class="auth animated bounceInLeft">
                        <div class="auth-top">
                            <h3><?php echo $this->lang->line('login_title');?></h3>
                            <p><?php echo $this->lang->line('signup_use_social');?></p>
                        </div>

                        <div class="auth-inner">
                            <?php if($this->config->item('login_social')):?>

                            <?php if($this->config->item('login_social_small')):?>
                            <script src="//ulogin.ru/js/ulogin.js"></script>
                            <div id="uLogin" data-ulogin="display=buttons;optional=first_name,last_name;redirect_uri=<?php echo urlencode(site_url('auth/social'))?>;receiver=<?php echo urlencode(site_url('public/xd_custom.html'))?>">
                                <div class="btn-group btn-group-sm btn-group-justified" style="max-width:300px;">
                                    <?php foreach($this->config->item('signup_services') as $soc):?>
                                    <?php if(in_array($soc, $this->config->item('signup_allowed_services'))):?>
                                        <a href="#" onclick="return false;" class="btn btn-<?php echo $soc;?>" style="width:auto;position:relative;" data-uloginbutton="<?php echo $soc;?>">
                                            <i class="fa fa-<?php echo $soc;?>"></i>
                                        </a>
                                    <?php endif; endforeach;?>
                                </div>
                            </div>
                            <hr/>

                            <?php else:?>
                            <script src="//ulogin.ru/js/ulogin.js"></script>
                            <div id="uLogin" data-ulogin="display=buttons;optional=first_name,last_name;redirect_uri=<?php echo urlencode(site_url('auth/social'))?>;receiver=<?php echo urlencode(site_url('public/xd_custom.html'))?>">
                                <div style="max-width:300px;">
                                    <?php foreach($this->config->item('signup_services') as $soc):?>
                                    <?php if(in_array($soc, $this->config->item('signup_allowed_services'))):?>
                                        <a href="#" onclick="return false;" class="btn btn-<?php echo $soc;?> btn-block" data-uloginbutton="<?php echo $soc;?>">
                                            <i class="fa fa-<?php echo $soc;?>"> | <?php echo $this->lang->line('signup_connect_' . $soc);?></i>
                                        </a>
                                    <?php endif; endforeach;?>
                                </div>
                            </div>
                            <hr>
                            <?php endif; endif;?>

                            <form role="form" method="POST" id="login-form" action="/auth/login/">
                                <?php if(isset($data['error_login'])):?>
                                    <div class="alert alert-danger" id="login-error" style="text-align:center;"><?php echo $data['error_login'];?></div>
                                <?php elseif(isset($data['error_captcha'])):?>
                                    <div class="alert alert-danger" id="login-error" style="text-align:center;"><?php echo $data['error_captcha'];?></div>
                                <?php elseif(isset($data['warning'])):?>
                                    <div class="alert alert-warning" id="login-error" style="text-align:center;"><?php echo $data['warning'];?></div>
                                <?php endif;?>

                                <div class="form-group <?php if(isset($data['error_login'])):?>has-error<?php endif;?>">
                                    <label for="login-username">
                                    <?php if($this->config->item('login_email_username')):?>
                                        <?php echo $this->lang->line('login_email_username');?>
                                    <?php elseif($this->config->item('login_email')):?>
                                        <?php echo $this->lang->line('login_email');?>
                                    <?php else:?>
                                        <?php echo $this->lang->line('login_username');?>
                                    <?php endif;?>
                                    </label>
                                    <input type="text" name="login" class="form-control" id="login-username" autocomplete="off" value="<?php echo $this->input->post('login');?>" placeholder="<?php echo $this->lang->line('login_username');?>">
                                </div>

                                <div class="form-group <?php if(isset($data['error_login'])):?>has-error<?php endif;?>">
                                    <label for="login-password"><?php echo $this->lang->line('login_password');?></label>
                                    <input type="password" name="password" class="form-control" id="login-password" placeholder="<?php echo $this->lang->line('login_password');?>">
                                </div>

                                <?php if($this->config->item('login_captcha')):?>

                                <?php if($this->config->item('login_recaptcha')):?>
                                    <script type="text/javascript">
                                        var RecaptchaOptions = {
                                            theme : 'custom',
                                            custom_theme_widget: 'recaptcha_widget',
                                        };
                                    </script>
                                    <div id="recaptcha_widget" style="display:none">
                                        <a id="recaptcha_image" onclick="Recaptcha.reload();" class="thumbnail captcha" style="height:auto !important;width: auto !important;margin-bottom:0;"></a>
                                        <span class="help-block" style="text-align:right;font-size:10px;"><?php echo $this->lang->line('signup_click_to_reload');?></span>
                                        <div id="signup-captcha" class="form-group <?php if(isset($data['error_captcha'])):?>has-error<?php endif;?>">
                                            <label for="recaptcha_response_field"><?php echo $this->lang->line('signup_captcha');?></label>
                                            <input type="text" class="form-control" autocomplete="off" id="recaptcha_response_field" name="recaptcha_response_field" placeholder="<?php echo $this->lang->line('signup_captcha');?>">
                                        </div>
                                    </div>

                                    <script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=<?php echo $this->config->item('recaptcha_public');?>"></script>
                                    <noscript>
                                    <iframe src="http://www.google.com/recaptcha/api/noscript?k=<?php echo $this->config->item('recaptcha_public');?>" height="300" width="500"></iframe>
                                    <br>
                                    <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                                    <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
                                    </noscript>

                                <?php elseif(isset($data['captcha'])):?>
                                    <a id="signup-captcha-image" class="thumbnail captcha" style="margin-bottom:0;"><?php echo $data['captcha'];?></a>
                                    <span class="help-block" style="text-align:right;font-size:10px;"><?php echo $this->lang->line('signup_click_to_reload');?></span>
                                    <div id="signup-captcha" class="form-group <?php if(isset($data['error_captcha'])):?>has-error<?php endif;?>">
                                        <label for="captcha"><?php echo $this->lang->line('signup_captcha');?></label>
                                        <input type="text" autocomplete="off" class="form-control" id="captcha" name="recaptcha_response_field" placeholder="<?php echo $this->lang->line('signup_captcha');?>">
                                    </div>
                                <?php endif; endif;?>

                                <div class="checkbox" style="padding-left:0;">
                                    <label>
                                        <input type="checkbox" name="remember"> <span style="margin-left:2px;"><?php echo $this->lang->line('login_remember');?></span>
                                    </label>
                                </div>
                                <button style="outline:0;" id="login-button" class="btn btn-block btn-primary" type="submit"><?php echo $this->lang->line('login_button');?></button>
                            </form>
                        </div>
                        <div class="auth-bottom">
                            <a href="<?php echo site_url('auth/recover');?>"><i class="fa fa-question-circle"></i> <?php echo $this->lang->line('login_forgot');?></a>
                            <a href="<?php echo site_url('auth/signup');?>" class="pull-right hidden-xs"><?php echo $this->lang->line('login_get_account');?> <i class="fa fa-plus-circle"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>