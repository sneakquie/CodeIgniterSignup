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
        <div id="content">
            <div class="header-image">
                <div class="container">
                    <div class="auth">
                        <div class="auth-top">
                            <h3><?php echo $this->lang->line('signup_title');?></h3>
                        </div>

                        <div class="auth-inner">
                            <div class="alert alert-info" style="text-align:center;margin:0;"><?php echo $this->lang->line('signup_disabled');?></div>
                        </div>

                        <div class="auth-bottom">
                            <a href="<?php echo site_url('auth/login');?>"><i class="fa fa-question-circle"></i> <?php echo $this->lang->line('signup_already_have');?></a>
                            <a href="<?php echo site_url();?>" class="pull-right hidden-xs"><?php echo $this->lang->line('confirm_on_main');?> <i class="fa fa-home"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>