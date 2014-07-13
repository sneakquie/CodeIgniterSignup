<div class="col-md-9">
    <?php if(!isset($user['followings']) || sizeof($user['followings']) == 0):?>
	<div class="panel panel-default">
            <div class="panel-heading"><?php echo $this->lang->line('user_followings');?>
                <a href="<?php echo $this->vinc_auth->userURL($user['login']);?>" class="btn btn-xs btn-default pull-right">
                    <i class="fa fa-sign-out"></i> <?php echo $this->lang->line('user_back');?>
                </a>
            </div>
            <div class="panel-content">
                <div class="alert alert-info" style="font-size:13px;margin-bottom:0;border-radius:0;-moz-border-radius:0;-webkit-border-radius:0;"><?php echo $this->lang->line('user_no_followings');?></div>
            </div>
    </div>
    <?php else:?>
    <div class="panel panel-primary">
        <div class="panel-heading"><?php echo $this->lang->line('user_followings');?> (<?php echo $user['followings_number'];?>)
            <a href="<?php echo $this->vinc_auth->userURL($user['login']);?>" class="btn btn-xs btn-default pull-right">
                <i class="fa fa-sign-out"></i> <?php echo $this->lang->line('user_back');?>
            </a>
        </div>
    </div>
    <div class="follow-user-block">
		<?php foreach ($user['followings'] as $value):?>

        <div class="media follow-user">
            <a class="pull-left follow-user-blocklink" target="_blank" href="<?php echo $this->vinc_auth->userURL($value['login']);?>">
                <img class="media-object img-rounded" src="<?php echo $this->vinc_auth->getUserAvatar($value['photo_avatar']);?>">
            </a>
            <div class="media-body">
                 <h4 class="media-heading">
                    <a href="<?php echo $this->vinc_auth->userURL($value['login']);?>"><?php echo $value['login'];?></a>
                    <?php if(!is_null($value['real_name']) && !empty($value['real_name'])):?>
                        <small>(<?php echo $value['real_name'];?>)</small>
                    <?php endif;?>
                    <?php if($this->config->item('allow_verified') && $value['verified']):?>
                        <span class="label label-success verified-badge" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('user_verified');?>"><span class="glyphicon glyphicon-ok"></span></span>
                    <?php endif;?>
                </h4>
                <span class="glyphicon glyphicon-star"></span> <?php echo $this->lang->line('user_rating')?>: <b><?php echo $value['rating'];?></b>

                <?php if($this->config->item('allow_website') && !is_null($value['website']) && !empty($value['website'])):?>
                    <span class="divider">/</span>
                    <span class="glyphicon glyphicon-globe"></span> <?php echo $this->lang->line('user_homepage')?>: 
                    <a href="http://<?php echo $value['website'];?>" target="_blank">
                        <b><?php echo $value['website'];?></b>
                         <span class="glyphicon glyphicon-new-window" style="font-size:11px;"></span>
                    </a>
                <?php endif;?>
                <?php if(!is_null($value['location']) && !empty($value['location'])):?>
                    <span class="divider">/</span>
                    <span class="glyphicon glyphicon-map-marker"></span> <?php echo $this->lang->line('user_location')?>: 
                    <b><?php echo $value['location'];?></b>
                <?php endif;?>
            </div>
        </div>

        <?php endforeach;?>

        <?php if($user['followings_number'] > $this->config->item('followings_number')):?>
            <div class="span7 text-center" style="padding-top:20px;">
                <img src="<?php echo site_url('/public/templates/full/images/loader_1.gif');?>" id="follow-user-loader" style="display:none;"/>
                <button class="btn btn-primary btn-sm" id="follow-user-load" type="button"><?php echo $this->lang->line('load_more');?></button>
            </div>
            <script type="text/javascript">
                $(function() {
                    followingsOffset = <?php echo $this->config->item('followings_number');?>;
                    $('body').on('click', '#follow-user-load', function(e) {
                        e.preventDefault();
                        UserProfile.loadFollowings(<?php echo $user['id'];?>, followingsOffset);
                    });
                });
            </script>
        <?php endif;?>
    </div>
    <?php endif;?>
</div>