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
    $('#recover-password').focus();
});
</script>
        <div id="content">
            <div class="header-image">
                <div class="container">
                    <div class="auth animated bounceInLeft">
                        <div class="auth-top">
                            <h3><?php echo $this->lang->line('recover_title');?></h3>
                        </div>

                        <div class="auth-inner">
                            <?php if(isset($data['error'])):?>
                                <div class="alert alert-warning" id="recover-error" style="text-align:center;margin:0;"><?php echo $data['error'];?></div>
                            <?php elseif(isset($data['warning'])):?>
                                <div class="alert alert-warning" id="recover-error" style="text-align:center;margin:0;"><?php echo $data['warning'];?></div>
                            <?php elseif(isset($data['success'])):?>
                                <div class="alert alert-success" id="recover-error" style="text-align:center;margin:0;"><?php echo $data['success'];?></div>
                            <?php else:?>
                            <form role="form" method="POST" id="recover-form" action="<?php echo current_url();?>">
                                <?php if(isset($data['error_password'])):?>
                                    <div class="alert alert-danger" id="recover-error" style="text-align:center;"><?php echo $data['error_password'];?></div>
                                <?php elseif(isset($data['error_repeat'])):?>
                                    <div class="alert alert-danger" id="recover-error" style="text-align:center;"><?php echo $data['error_repeat'];?></div>
                                <?php endif;?>

                                <div class="form-group <?php if(isset($data['error_password'])):?>has-error<?php endif;?>">
                                    <label for="recover-password"><?php echo $this->lang->line('signup_password');?></label>
                                    <div class="input-group">
                                        <input type="password" autocomplete="off" name="password" class="form-control" id="recover-password" placeholder="<?php echo $this->lang->line('signup_password');?>">
                                        <span class="input-group-btn">
                                            <a href="#" tabIndex="-1" style="outline:0;" onclick="return false;" class="btn btn-default" id="recover-show-password">
                                                <i class="fa fa-eye-slash"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group <?php if(isset($data['error_repeat'])):?>has-error<?php endif;?>">
                                    <label for="recover-password-repeat"><?php echo $this->lang->line('signup_password_repeat');?></label>
                                    <input type="password" name="repeat" class="form-control" id="recover-password-repeat" autocomplete="off" placeholder="<?php echo $this->lang->line('signup_password_repeat');?>">
                                </div>

                                <button style="outline:0;" id="recover-button-last" class="btn btn-block btn-warning" type="submit"><?php echo $this->lang->line('recover_button');?></button>
                            </form>
                            <?php endif;?>
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