<script type="text/javascript">
page = 1;
function goPage(number) {
    var offset = "100px";
    var offset1 = (number > page) ? "-="+offset : "+="+offset;
    var offset2 = (number > page) ? "+="+offset : "-="+offset;
    $('div[id^="user-profile-page"]:visible').stop(true, true).animate({marginLeft: offset1, marginRight: offset2, opacity: 0}, "normal", function(){
        $(this).hide();

        $("div#user-profile-page"+number).css({marginLeft: offset2, marginRight: offset1, opacity: 0}).show().animate({marginLeft: 0, marginRight: 0, opacity: 1}, "normal");
    });
}
</script>
<div class="row">
    <div class="col-md-3 hidden-md hidden-lg">
        <div class="well user-profile-left">
            <?php if($is_my_profile):?>
            <a href="<?php echo site_url('settings/cover');?>" class="btn btn-sm btn-default user-edit-btn" style=""><i class="fa fa-edit"></i></a>
            <?php else:?>
                <?php if($this->vinc_auth->logged() && isset($user['already_flagged']) && intval($user['already_flagged']) > 0):?>
                <a href="#" class="btn btn-sm btn-primary flag-btn" onclick="return false;" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('user_flag_tooltip');?>"><i class="fa fa-flag"></i></a>
                <?php else:?>
                <a href="#" class="btn btn-sm btn-primary flag-disabled-btn" onclick="return false;" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('user_flag_tooltip');?>"><i class="fa fa-flag"></i></a>
                <div class="btn-group flag-btn">
                    <a href="#" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flag"></i></a>
                    <ul class="dropdown-menu pull-right flag-dropdown" role="menu">
                      <?php for($i = 1; $i < 6; $i++):?>
                          <li><a href="#" onclick="UserProfile.flag(<?php echo $user['id'];?>, <?php echo $i;?>); return false;"><?php echo $this->lang->line('user_flag_' . $i);?></a></li>
                      <?php endfor;?>
                      <li class="divider"></li>
                      <li><a href="#" onclick="return false;"><?php echo $this->lang->line('close');?></a></li>
                    </ul>
                </div>
                <?php endif;?>
            <?php endif;?>
            <div class="user-profile-cover" style="background-image: url('<?php echo $this->vinc_auth->getUserCover($user['photo_cover']);?>');">
                <a href="<?php echo $this->vinc_auth->userURL($user['login']);?>" class="user-profile-avatar"><img src="<?php echo $this->vinc_auth->getUserAvatar($user['photo_avatar']);?>" class="img-circle user-profile-avatar-img"></a>
                <?php if(!$is_my_profile && $this->vinc_auth->logged()):?>
                    <?php if(isset($user['is_follower']) && intval($user['is_follower']) > 0):?>
                    <a href="#" class="btn btn-danger btn-sm unfollow-btn" onclick="UserProfile.unfollow(<?php echo $user['id'];?>); return false;"><span class="glyphicon glyphicon-remove"></span> | <?php echo $this->lang->line('user_unfollow');?></a>
                    <a href="#" class="btn btn-success btn-sm follow-btn" onclick="UserProfile.follow(<?php echo $user['id'];?>); return false;" style="display:none;"><span class="glyphicon glyphicon-ok"></span> | <?php echo $this->lang->line('user_follow');?></a>
                    <?php else:?>
                    <a href="#" class="btn btn-danger btn-sm unfollow-btn" onclick="UserProfile.unfollow(<?php echo $user['id'];?>); return false;" style="display:none;"><span class="glyphicon glyphicon-remove"></span> | <?php echo $this->lang->line('user_unfollow');?></a>
                    <a href="#" class="btn btn-success btn-sm follow-btn" onclick="UserProfile.follow(<?php echo $user['id'];?>); return false;"><span class="glyphicon glyphicon-ok"></span> | <?php echo $this->lang->line('user_follow');?></a>
                    <?php endif;?>
                <?php endif;?>
            </div>
            <div class="user-profile-bottom">
                <a href="<?php echo $this->vinc_auth->userURL($user['login']);?>" class="user-profile-login"><?php echo (isset($user['login'][15])) ? substr($user['login'], 0, 15) . '...' : $user['login'];?></a>
                <ul class="list-unstyled user-profile-list">
                    <li><b><span class="glyphicon glyphicon-star"></span> <?php echo $this->lang->line('user_rating')?>: 
                        <span class="user-rating"><?php echo $user['rating'];?></span>
                    </b></li>
                    <?php if(!is_null($user['location']) && !empty($user['location'])):?>
                    <div class="horizontal-divider"></div>
                    <li><span class="glyphicon glyphicon-map-marker"></span> <?php echo $this->lang->line('user_location')?>: 
                        <b class="user-location"><?php echo $user['location'];?></b>
                    </li>
                    <?php endif;?>
                    <?php if($user['show_born_date'] && sizeof($born_date = explode('.', $user['born_date'])) > 2):?>
                    <div class="horizontal-divider"></div>
                    <li><span class="glyphicon glyphicon-calendar"></span> <?php echo $this->lang->line('user_date')?>:
                        <b class="user-born-date">
                            <?php echo str_ireplace(array(':day', ':month', ':year'), array($born_date[0], $this->lang->line('user_month_' . $born_date[1]), $born_date[2]), $this->lang->line('user_born_format'));?>
                        </b>
                    </li>
                    <?php endif;?>
                    <?php if($user['show_email']):?>
                    <div class="horizontal-divider"></div>
                    <li><span class="glyphicon glyphicon-envelope"></span> Email: 
                        <b><a href="mailto:<?php echo $user['email'];?>" class="user-email">
                            <?php echo (isset($user['email'][31])) ? substr($user['email'], 0, 31) . '...' : $user['email'];?>
                        </a></b>
                    </li>
                    <?php endif;?>
                    <?php if(!is_null($user['website']) && !empty($user['website'])):?>
                    <div class="horizontal-divider"></div>
                    <li><span class="glyphicon glyphicon-globe"></span> <?php echo $this->lang->line('user_homepage')?>: 
                        <b><a href="http://<?php echo $user['website'];?>" class="user-website" target="_blank">
                            <?php echo (isset($user['website'][31])) ? substr($user['website'], 0, 31) . '...' : $user['website'];?> <span class="glyphicon glyphicon-new-window" style="font-size:11px;"></span>
                        </a></b>
                    </li>
                    <?php endif;?>
                </ul>
                <?php if($this->config->item('allow_social_profiles')):?>
                <div class="horizontal-divider" style="margin:15px 0 15px 0;"></div>
                <div class="profile-social-links">
                <?php foreach ($this->config->item('profile_social_networks') as $key => $value):?>
                        <?php if(!is_null($s = $user['network_' . $key]) && !empty($s)):?>
                        <a href="http://<?php echo $value . $user['network_' . $key];?>" target="_blank" class="btn btn-sm btn-<?php echo $key;?> user-social-btn"><i class="fa fa-<?php echo $key;?>"></i></a>
                <?php endif;endforeach;?>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>

    <style type="text/css">
        span.user-top-login {
            font-family: inherit;
            font-weight: 500;
            line-height: 1.1;
            color: inherit;
            font-size:18px;
            margin:4px;
        }
        span.user-top-helper {
            font-weight: 400;
            color: #999;
            font-size:13px;
        }
        a.user-followers-link {
            color: #666;
        }
    </style>