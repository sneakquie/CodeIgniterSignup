<link href="/public/templates/full/css/animated.min.css" rel="stylesheet">
<style type="text/css">
body {
  position: static;
  display: block;
  width: 100%;
  height: 100%;
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
    $('#signup-login').focus();
});
</script>
        <div id="content">
            <div class="header-image">
                <div class="container">
                    <div class="auth animated bounceInLeft">
                        <div class="auth-top">
                            <h3><?php echo $this->lang->line('signup_title');?></h3>
                            <p><?php echo $this->lang->line('signup_use_social');?></p>
                        </div>

                        <div class="auth-inner">
                            <?php if($this->config->item('signup_social')):?>

                            <?php if(isset($data['social'])):?>
                            <div class="btn-group signup-social-logged">
                                <a href="<?php echo $data['social']['profile'];?>" target="blank" class="btn btn-<?php echo $data['social']['network'];?>">
                                    <i class="fa fa-<?php echo $data['social']['network'];?>"></i> |
                                    <?php if(isset($data['social']['last_name'], $data['social']['first_name'])):?>
                                        <?php echo $data['social']['first_name'] . ' ' . $data['social']['last_name'];?>
                                    <?php elseif(isset($data['social']['last_name'])):?>
                                        <?php echo $data['social']['last_name'];?>
                                    <?php elseif(isset($data['social']['first_name'])):?>
                                        <?php echo $data['social']['first_name'];?>
                                    <?php else:?>
                                        <?php echo $this->lang->line('signup_connected_with') . $this->lang->line($data['social']['network']);?>
                                    <?php endif;?>
                                </a>
                                <a href="<?php echo site_url('auth/unlock')?>" class="btn btn-<?php echo $data['social']['network'];?>">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>

                            <?php elseif($this->config->item('signup_social_small')):?>
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
                                            <i class="fa fa-<?php echo $soc;?>"> | <?php echo $this->lang->line('signup_connect_with') . $this->lang->line($soc);?></i>
                                        </a>
                                    <?php endif; endforeach;?>
                                </div>
                            </div>
                            <hr>
                            <?php endif; endif;?>

                            <form role="form" method="POST" id="signup-form" action="<?php echo site_url('auth/signup');?>">

                                <div class="form-group <?php if(isset($data['errors']['login'])):?>has-error<?php endif;?>">
                                    <label for="signup-login"><?php echo $this->lang->line('signup_login');?></label>
                                    <input type="text" name="login" class="form-control" id="signup-login" autocomplete="off" value="<?php echo $this->input->post('login');?>" placeholder="<?php echo $this->lang->line('signup_login');?>">
                                    <?php if(isset($data['errors']['login'])):?>
                                        <span class="help-block error" id="signup-login-error"><?php echo $data['errors']['login'];?></span>
                                    <?php endif;?>
                                </div>

                                <div class="form-group <?php if(isset($data['errors']['email'])):?>has-error<?php endif;?>">
                                    <label for="signup-email"><?php echo $this->lang->line('signup_email');?></label>
                                    <input type="text" name="email" class="form-control" id="signup-email" autocomplete="off" value="<?php echo $this->input->post('email');?>" placeholder="<?php echo $this->lang->line('signup_email');?>">
                                    <?php if(isset($data['errors']['email'])):?>
                                        <span class="help-block error" id="signup-email-error"><?php echo $data['errors']['email'];?></span>
                                    <?php endif;?>
                                </div>

                                <?php if($this->config->item('signup_invite')):?>
                                <div class="form-group <?php if(isset($data['errors']['invite'])):?>has-error<?php endif;?>">
                                    <label for="signup-invite">
                                        <?php echo $this->lang->line('signup_invite');?>
                                        <?php if(!$this->config->item('signup_invite_required')):?>
                                        <span class="help-block" id="signup-invite-not-required" style="display:inline;color:#A7A4A4;font-weight:normal;"><?php echo $this->lang->line('signup_invite_not_required');?></span>
                                        <?php endif;?>
                                    </label>
                                    <input type="text" name="invite" class="form-control" id="signup-invite" autocomplete="off" value="<?php echo $this->input->post('invite');?>" placeholder="<?php echo $this->lang->line('signup_invite');?>">
                                    <?php if(isset($data['errors']['invite'])):?>
                                        <span class="help-block error" id="signup-invite-error"><?php echo $data['errors']['invite'];?></span>
                                    <?php endif;?>
                                </div>
                                <?php endif;?>

                                <?php if(!isset($data['social'])):?>
                                <div class="form-group <?php if(isset($data['errors']['password'])):?>has-error<?php endif;?>">
                                    <label for="signup-password"><?php echo $this->lang->line('signup_password');?></label>
                                    <div class="input-group">
                                        <input type="password" autocomplete="off" name="password" class="form-control" id="signup-password" placeholder="<?php echo $this->lang->line('signup_password');?>">
                                        <span class="input-group-btn">
                                            <a href="#" tabIndex="-1" style="outline:0;" onclick="return false;" class="btn btn-default" id="signup-show-password">
                                                <i class="fa fa-eye-slash"></i>
                                            </a>
                                        </span>
                                    </div>
                                    <?php if(isset($data['errors']['password'])):?>
                                        <span class="help-block error" id="signup-password-error"><?php echo $data['errors']['password'];?></span>
                                    <?php endif;?>
                                </div>

                                <?php if($this->config->item('signup_repeat_password')):?>
                                <div class="form-group <?php if(isset($data['errors']['password_repeat'])):?>has-error<?php endif;?>">
                                    <label for="signup-password-repeat"><?php echo $this->lang->line('signup_password_repeat');?></label>
                                    <input type="password" autocomplete="off" name="password_repeat" class="form-control" id="signup-password-repeat" placeholder="<?php echo $this->lang->line('signup_password_repeat');?>">
                                    <?php if(isset($data['errors']['password_repeat'])):?>
                                        <span class="help-block error" id="signup-password-repeat-error"><?php echo $data['errors']['password_repeat'];?></span>
                                    <?php endif;?>
                                </div>
                                <?php endif; endif;?>

                                <?php if($this->config->item('signup_captcha')):?>

                                <?php if($this->config->item('signup_recaptcha')):?>
                                    <script type="text/javascript">
                                        var RecaptchaOptions = {
                                            theme : 'custom',
                                            custom_theme_widget: 'recaptcha_widget',
                                        };
                                    </script>
                                    <div id="recaptcha_widget" style="display:none">
                                        <a id="recaptcha_image" onclick="Recaptcha.reload();" class="thumbnail captcha" style="height:auto !important;width: auto !important;margin-bottom:0;"></a>
                                        <span class="help-block" style="text-align:right;font-size:10px;"><?php echo $this->lang->line('signup_click_to_reload');?></span>
                                        <div id="signup-captcha" class="form-group <?php if(isset($data['errors']['captcha'])):?>has-error<?php endif;?>">
                                            <label for="recaptcha_response_field"><?php echo $this->lang->line('signup_captcha');?></label>
                                            <input type="text" autocomplete="off" class="form-control" id="recaptcha_response_field" name="recaptcha_response_field" placeholder="<?php echo $this->lang->line('signup_captcha');?>">
                                            <?php if(isset($data['errors']['captcha'])):?>
                                                <span class="help-block error" id="signup-captcha-error"><?php echo $data['errors']['captcha'];?></span>
                                            <?php endif;?>
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
                                    <div id="signup-captcha" class="form-group <?php if(isset($data['errors']['captcha'])):?>has-error<?php endif;?>">
                                        <label for="captcha"><?php echo $this->lang->line('signup_captcha');?></label>
                                        <input type="text" autocomplete="off" class="form-control" id="captcha" name="recaptcha_response_field" placeholder="<?php echo $this->lang->line('signup_captcha');?>">
                                        <?php if(isset($data['errors']['captcha'])):?>
                                            <span class="help-block error" id="signup-captcha-error"><?php echo $data['errors']['captcha'];?></span>
                                        <?php endif;?>
                                    </div>
                                <?php endif; endif;?>

                                <?php if($this->config->item('signup_terms')):?>
                                <div class="checkbox" style="padding-left:0;">
                                    <label>
                                        <input type="checkbox" name="agree_terms" id="signup-terms"> <span style="margin-left:2px;"><?php echo $this->lang->line('signup_i_agree');?> <a href="<?php echo site_url($this->config->item('signup_terms_link'));?>"><?php echo $this->lang->line('signup_with_terms');?></a></span>
                                        <?php if(isset($data['errors']['terms'])):?>
                                            <span class="help-block error" id="signup-terms-error"><?php echo $data['errors']['terms'];?></span>
                                        <?php endif;?>
                                    </label>
                                </div>
                                <?php endif;?>
                                <button style="outline:0;" id="signup-button" class="btn btn-block btn-success" type="submit"><?php echo $this->lang->line('signup_button');?></button>
                            </form>
                        </div>
                        <div class="auth-bottom">
                            <a href="<?php echo site_url('auth/login');?>"><i class="fa fa-question-circle"></i> <?php echo $this->lang->line('signup_already_have');?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>