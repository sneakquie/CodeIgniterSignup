<div class="col-md-9">
    <div class="panel panel-default" id="user-profile-page1">
        <div class="panel-heading">

        <span class="user-top-login">
            <?php echo $user['login'];?>
        </span>
        <span class="user-top-helper hidden-xs hidden-sm">
            <?php if($is_my_profile):echo $this->lang->line('user_my_profile');else:echo $this->lang->line('user_profile');endif;?>
        </span>
        
        <?php if($this->config->item('allow_verified') && $user['verified']):?>
            <span class="label label-success verified-badge" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('user_verified');?>"><span class="glyphicon glyphicon-ok"></span></span>
        <?php endif;?>

        <?php if($is_my_profile):?>
            <a href="<?php echo site_url('settings/account');?>" class="btn btn-xs btn-default pull-right">
                <i class="fa fa-edit"></i> <?php echo $this->lang->line('user_edit_short');?>
            </a>
            <span class="text-muted pull-right" style="font-size:13px;margin-right:15px;">Online</span>
        <?php else:?>
            <?php if($this->vinc_auth->logged() && $this->vinc_auth->_('group_edit_users')):?>
                <button type="button" onclick="goPage(2); return false;" class="btn btn-xs btn-default pull-right">
                    <i class="fa fa-edit"></i> <?php echo $this->lang->line('user_edit_short');?>
                </button>
            <?php endif;?>

            <?php if($this->config->item('last_login_show') == 1 || ($this->config->item('last_login_show') == 2 && $user['show_last_login'] == true)):?>
            <span class="text-muted pull-right" style="font-size:13px;margin-right:15px;">
                <?php if($user['last_activity'] + 900 >= $_SERVER['REQUEST_TIME']):?>
                    Online
                <?php else:?>
                    <?php if(intval($user['gender']) < 2):echo $this->lang->line('user_last_seen_m');else:echo $this->lang->line('user_last_seen_f');endif;?>
                    <?php echo ' ' . native_date($user['last_activity'], $this->config->item('last_seen_show_yesterday'), $this->config->item('last_seen_show_full'));?>
                <?php endif;?>
            </span>
            <?php endif;?>
        <?php endif;?>
        </div>

        <div class="panel-body">

            <dl class="dl-horizontal" style="font-size:13px;">
                <dd>
                    <span style="font-weight: 400;color: #999;font-size:24px;line-height:2;">
                        <?php if(!is_null($user['real_name']) && !empty($user['real_name'])):echo $user['real_name'];else:echo $user['login'];endif;?>
                    </span>
                </dd>

                <?php if($this->config->item('allow_about') && !is_null($user['about']) && !empty($user['about'])):?>
                    <dt>
                        <?php echo $this->lang->line('user_about_me');?>
                    </dt>
                    <dd>
                        <?php echo $user['about'];?>
                    </dd>
                <?php endif;?>

                <?php if($this->config->item('allow_notes') && $this->vinc_auth->logged() && !$is_my_profile):?>
                    <dt>
                        <?php echo $this->lang->line('user_note');?>
                    </dt>
                    <dd>
                        <span id="user-note-link" style="color: #008000;cursor: pointer;border-bottom: 1px dashed;" onclick="UserProfile.showNoteForm(); return false;">
                            <?php if(!is_null($user['note']) && !empty($user['note'])):?>
                                <?php echo $user['note'];?>
                            <?php else:?>
                                <?php echo $this->lang->line('user_note_info');?>
                            <?php endif;?>
                        </span>

                        <form class="form-horizontal" id="user-note-form" method="POST" action="" style="display:none;" role="form">
                            <div class="form-group">
                                <div class="col-sm-9">
                                <input type="text" autocomplete="off" style="height:35px;" id="user-note-input" class="form-control" placeholder="<?php echo $this->lang->line('user_note_info');?>"
                                <?php if(!is_null($user['note']) && !empty($user['note'])):?> value="<?php echo $user['note'];?>"<?php endif;?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-sm btn-success" onclick="UserProfile.saveNote(<?php echo $user['id'];?>); return false;"><span class="glyphicon glyphicon-ok"></span> <?php echo $this->lang->line('settings_save');?></button>
                                    <span onclick="UserProfile.closeNoteForm(); return false;" style="margin-left:10px;border-bottom:1px dashed #999; color:#999;cursor:pointer;"><?php echo $this->lang->line('cancel');?></span>
                                </div>
                            </div>
                        </form>
                    </dd>
                <?php endif;?>

                <?php if($this->config->item('show_signup_date')):?>
                    <dt>
                        <?php echo $this->lang->line('user_registered');?>
                    </dt>
                    <dd>
                        <?php echo native_date($user['signup_date'], $this->config->item('user_registered_show_yesterday'), $this->config->item('user_registered_show_full'));
                        if(is_array($user['inviter']) && isset($user['inviter']['login'])):?>
                            <?php echo ' ' . $this->lang->line('user_registered_by');?>
                            <a href="<?php echo $this->vinc_auth->userURL($user['inviter']['login']);?>" target="_blank">
                                <?php echo $user['inviter']['login'];?>
                            </a>
                        <?php endif;?>
                    </dd>
                <?php endif;?>

                <?php if($this->config->item('show_invited_users') && isset($user['invited_users']) && sizeof($user['invited_users']) > 0 || $invited_users = array()):?>
                    <dt>
                        <?php echo $this->lang->line('user_invited');?>
                    </dt>
                    <dd>
                        <?php foreach($user['invited_users'] as $value):?>
                            <?php $invited_users[] = '<a href="' . $this->vinc_auth->userURL($value['login']) . '" target="_blank">' . $value['login'] . '</a>';?>
                        <?php endforeach;?>
                        <?php echo implode(', ', $invited_users);?>
                    </dd>
                <?php endif;?>

                <?php if( isset($user['followers'])
                       && is_array($user['followers'])
                       && sizeof($user['followers']) > 0):?>

                    <dt><a href="<?php echo $this->vinc_auth->userURL($user['login'] . '/followers');?>" class="user-followers-link"><?php echo $this->lang->line('user_followers');?> (<?php echo $user['followers_number'];?>):</a></dt>
                    <dd>
                    <?php foreach ($user['followers'] as $value):?>
                        <a href="<?php echo $this->vinc_auth->userURL($value['login']);?>">
                            <img src="<?php echo $this->vinc_auth->getUserAvatar($value['photo_avatar']);?>" class="img-rounded" style="border:1px dashed #bbb;height:30px;"/>
                        </a>
                    <?php endforeach;?>
                    </dd>
                <?php endif;?>

                <?php if( isset($user['followings'])
                       && is_array($user['followings'])
                       && sizeof($user['followings']) > 0):?>

                    <dt><a href="<?php echo $this->vinc_auth->userURL($user['login'] . '/followings');?>" class="user-followers-link"><?php echo $this->lang->line('user_followings');?> (<?php echo $user['followings_number'];?>):</a></dt>
                    <dd>
                    <?php foreach ($user['followings'] as $value):?>
                        <a href="<?php echo $this->vinc_auth->userURL($value['login']);?>">
                            <img src="<?php echo $this->vinc_auth->getUserAvatar($value['photo_avatar']);?>" class="img-rounded" style="border:1px dashed #bbb;height:30px;"/>
                        </a>
                    <?php endforeach;?>
                    </dd>
                <?php endif;?>

                <?php if( !$is_my_profile
                       && (($this->config->item('allow_contact_email')
                       && $user['allow_email'])
                       || ($this->config->item('allow_messages')
                       && $this->vinc_auth->logged()))):?>
                    
                    <dd>
                        <?php if($this->config->item('allow_messages') && $this->vinc_auth->logged()):?>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                            <span class="glyphicon glyphicon-envelope"></span> <?php echo $this->lang->line('user_send_message');?>
                        </button>
                        <?php endif;?>
                        
                        <?php if($user['allow_email'] && $this->config->item('allow_contact_email')):?>
                        <button type="button" class="btn btn-default btn-sm" onclick="UserProfile.showContactForm(); return false;">
                            <span class="glyphicon glyphicon-envelope"></span> <?php echo $this->lang->line('user_send_email');?>
                        </button>
                        
                        <form class="form-horizontal" id="user-contact-form" method="POST" action="" style="display:none;margin-top:10px;" role="form">
                            <div class="form-group">
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="user-contact-textarea" style="max-width:100%;min-height:100px;"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9">
                                
                                <button type="submit" class="btn btn-sm btn-success" onclick="UserProfile.sendEmail(<?php echo $user['id'];?>); return false;"><span class="glyphicon glyphicon-send"></span> Отправить</button>
                                <span onclick="UserProfile.closeContactForm(); return false;" style="margin-left:10px;border-bottom:1px dashed #999; color:#999;cursor:pointer;">отмена</span>
                                
                                </div>
                            </div>
                        </form>
                        <?php endif;?>
                    </dd>
                <?php endif;?>
            </dl>
        </div>
        </div>

        <?php if($this->vinc_auth->logged() && !$is_my_profile && $this->vinc_auth->_('group_edit_users')):?>
        <div class="panel panel-default" id="user-profile-page2" style="display:none;">
            <div class="panel-heading">
                <span class="user-top-login">
                    <?php echo $user['login'];?>
                </span>
                <span class="user-top-helper hidden-xs hidden-sm">
                    (редактирование)
                </span>
                <a href="<?php echo site_url('settings/account');?>" onclick="goPage(1); return false;" class="btn btn-xs btn-default pull-right">
                    <i class="fa fa-sign-out"></i> <?php echo $this->lang->line('user_back');?>
                </a>
            </div>
            <div class="panel-body">
            <form class="form-horizontal" method="POST" action="" role="form">

                <div class="form-group">
                    <label for="user-edit-day" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_born');?>:</label>
                    <div class="col-sm-9">
                    <?php !isset($born_date) && ($born_date = explode('.', $user['born_date']));?>
                    <select id="user-edit-day" class="form-control selecter-cover selecter-s-10 selecter-inline">
                        <?php for($i = 1; $i <= 31; ++$i):?>
                            <option value="<?php echo $i;?>"<?php if(isset($born_date[0]) && $i == $born_date[0]):?> selected<?php endif;?>><?php echo $i;?></option>
                        <?php endfor;?>
                    </select>

                    <select id="user-edit-month" class="form-control selecter-cover selecter-s-20 selecter-inline">
                        <?php for($i = 1; $i <= 12; ++$i):?>
                            <option value="<?php echo $i;?>"<?php if(isset($born_date[1]) && $i == $born_date[1]):?> selected<?php endif;?>><?php echo $this->lang->line('settings_month_' . $i);?></option>
                        <?php endfor;?>
                    </select>

                    <select id="user-edit-year" class="form-control selecter-cover selecter-s-20 selecter-inline">
                        <?php for($i = date('Y'); $i >= 1900; --$i):?>
                            <option value="<?php echo $i;?>"<?php if(isset($born_date[2]) && $i == $born_date[2]):?> selected<?php endif;?>><?php echo $i;?></option>
                        <?php endfor;?>
                    </select>
                            
                    </div>
                </div>

                <hr/>

                <div class="form-group">
                    <label for="user-edit-gender" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_gender');?>:</label>
                    <div class="col-sm-9">

                    <select id="user-edit-gender" class="form-control selecter-cover selecter-s-20 selecter-inline">
                        <?php if($user['gender'] == 0):?>
                        <option value="0" selected><?php echo $this->lang->line('settings_gender_0');?></option>
                        <option value="1"><?php echo $this->lang->line('settings_gender_1');?></option>
                        <option value="2"><?php echo $this->lang->line('settings_gender_2');?></option>
                        <?php elseif($user['gender'] == 1):?>
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

                <div class="form-group">
                    <label for="user-edit-email" class="col-sm-3 control-label">Email:</label>
                    <div class="col-sm-9">

                    <input type="text" autocomplete="off" id="user-edit-email" class="form-control" placeholder="Email" value="<?php echo $user['email'];?>">

                    </div>
                </div>

                <div class="form-group">
                    <label for="user-edit-login" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_login');?>:</label>
                    <div class="col-sm-9">

                    <input type="text" autocomplete="off" id="user-edit-login" class="form-control" placeholder="<?php echo $this->lang->line('settings_login');?>" value="<?php echo $user['login'];?>">

                    </div>
                </div>

                <hr/>

                <div class="form-group">
                    <label for="user-edit-location" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_location');?>:</label>
                    <div class="col-sm-9">

                    <input type="text" autocomplete="off" id="user-edit-location" class="form-control" placeholder="<?php echo $this->lang->line('settings_location');?>" value="<?php echo $user['location'];?>">

                    </div>
                </div>


                <div class="form-group">
                    <label for="user-edit-name" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_name');?>:</label>
                    <div class="col-sm-9">

                    <input type="text" autocomplete="off" id="user-edit-name" class="form-control" placeholder="<?php echo $this->lang->line('settings_name');?>" value="<?php echo $user['real_name'];?>">

                    </div>
                </div>


                <?php if($this->config->item('allow_about')):?>
                <hr/>
                <div class="form-group">
                    <label for="user-edit-about" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_about');?>:</label>
                    <div class="col-sm-9">

                    <textarea class="form-control" style="min-height:80px;" placeholder="<?php echo $this->lang->line('settings_about');?>" id="user-edit-about"><?php echo $user['about'];?></textarea>

                    </div>
                </div>
                <?php endif;?>


                <?php if($this->config->item('allow_website')):?>
                <hr/>

                <div class="form-group">
                    <label for="user-edit-website" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_website');?>:</label>
                    <div class="col-sm-9">

                    <div class="input-group">
                      <span class="input-group-addon">http://</span>
                      <input type="text" autocomplete="off" id="user-edit-website" class="form-control" placeholder="<?php echo $this->lang->line('settings_website');?>" value="<?php echo $user['website'];?>">
                    </div>

                    </div>
                </div>
                <?php endif;?>

                <hr/>

                <div class="form-group">
                    <label for="user-edit-avatar" class="col-sm-3 control-label"><?php echo $this->lang->line('topbar_settings_avatar');?>:</label>
                    <div class="col-sm-9">

                    <input type="text" autocomplete="off" id="user-edit-avatar" class="form-control" placeholder="<?php echo $this->lang->line('topbar_settings_avatar');?>" value="<?php echo $user['photo_avatar'];?>">
                    <p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('user_edit_avatar_help');?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="user-edit-cover" class="col-sm-3 control-label"><?php echo $this->lang->line('topbar_settings_cover');?>:</label>
                    <div class="col-sm-9">

                    <input type="text" autocomplete="off" id="user-edit-cover" class="form-control" placeholder="<?php echo $this->lang->line('topbar_settings_cover');?>" value="<?php echo $user['photo_cover'];?>">
                    <p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('user_edit_avatar_help');?></p>
                    </div>
                </div>

                <hr/>

                <div class="form-group">
                    <label for="user-edit-verified" class="col-sm-3 control-label"><?php echo $this->lang->line('user_edit_verified');?>:</label>
                    <div class="col-sm-9">

                    <select id="user-edit-verified" class="form-control selecter-cover selecter-s-10 selecter-inline">
                        <?php if($user['verified']):?>
                        <option value="1" selected><?php echo $this->lang->line('yes');?></option>
                        <option value="0"><?php echo $this->lang->line('no');?></option>
                        <?php else:?>
                        <option value="1"><?php echo $this->lang->line('yes');?></option>
                        <option value="0" selected><?php echo $this->lang->line('no');?></option>
                        <?php endif;?>
                    </select>
                    <p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('user_edit_verified_help');?></p>
                    </div>
                </div>

                <hr/>

                <div class="form-group">
                    <label for="user-edit-password" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_new_password');?>:</label>
                    <div class="col-sm-9">

                    <input type="password" autocomplete="off" id="user-edit-password" class="form-control" placeholder="<?php echo $this->lang->line('settings_new_password');?>">
                    <p class="help-block" style="font-size:13px;"><?php echo $this->lang->line('user_edit_password_help');?></p>
                    </div>
                </div>

                <hr/>
            
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-10">
                    
                    <button type="submit" id="user-edit-save" data-usereditid="<?php echo $user['id'];?>" class="btn btn-success"><span class="glyphicon glyphicon-ok-circle"></span> <?php echo $this->lang->line('settings_save');?></button>
                    
                    </div>
                </div>
            </form>
            </div>
        </div>
    <?php endif;?>
</div>