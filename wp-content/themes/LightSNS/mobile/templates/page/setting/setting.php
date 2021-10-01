<?php 
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$author_id=$_GET['author_id'];	
}else{
$author_id=$user_id;
}
$theme_url=get_template_directory_uri();
$user_info = get_userdata($author_id);
$verify_add=jinsom_get_option('jinsom_verify_add');

if($user_info->verify){
$verify=jinsom_verify_type($author_id);
}else{
$verify=__('普通用户','jinsom');		
}


$jinsom_qq_avatar=get_user_meta($author_id,'qq_avatar',true);
$jinsom_weibo_avatar=get_user_meta($author_id,'weibo_avatar',true);
$jinsom_wechat_avatar=get_user_meta($author_id,'wechat_avatar',true);
$jinsom_github_avatar=get_user_meta($author_id,'github_avatar',true);
$jinsom_alipay_avatar=get_user_meta($author_id,'alipay_avatar',true);
?>
<div data-page="setting" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('资料设置','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-setting-content" data="<?php echo $author_id;?>">


<div class="jinsom-setting-box">

<li class="avatarimg" onclick="jinsom_upload_avatar_menu(this,<?php echo $author_id;?>)">
<a href="#" class="link">
<span class="title"><?php _e('头像','jinsom');?></span>	
<span class="value"><?php echo jinsom_avatar($author_id,'80',avatar_type($author_id));?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li>
<a href="#" class="link">
<span class="title"><?php _e('帐号','jinsom');?></span>	
<span class="value"><?php echo $user_info->user_login;?></span>
</a>
</li>

<li onclick="jinsom_update_nickname_form(<?php echo $author_id;?>,this)">
<a href="#" class="link">
<span class="title"><?php _e('昵称','jinsom');?></span>	
<span class="value"><?php echo $user_info->nickname;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="code" onclick="jinsom_show_user_code(<?php echo $author_id;?>)">
<a href="#" class="link">
<span class="title"><?php _e('二维码','jinsom');?></span>	
<span class="value"><i class="jinsom-icon jinsom-erweima"></i></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li>
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting-more.php?author_id=<?php echo $author_id;?>" class="link">
<span class="title"><?php _e('更多','jinsom');?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>


</div>



<div class="jinsom-setting-box">

<?php if(jinsom_get_option('jinsom_sms_style')!='close'){?>
<li class="phone">
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting-phone.php?author_id=<?php echo $author_id;?>" class="link">
<span class="title"><?php _e('手机号','jinsom');?></span>	
<span class="value"><?php echo $user_info->phone;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }?>

<?php if(jinsom_get_option('jinsom_email_style')!='close'){?>
<li class="email">
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting-email.php?author_id=<?php echo $author_id;?>" class="link">
<span class="title"><?php _e('邮箱','jinsom');?></span>	
<span class="value"><?php echo $user_info->user_email;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }?>

<li>
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting-password.php?author_id=<?php echo $author_id;?>" class="link">
<span class="title"><?php _e('修改密码','jinsom');?></span>	
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li>
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting-question.php?author_id=<?php echo $author_id;?>" class="link">
<span class="title"><?php _e('安全问题','jinsom');?></span>	
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
</div>


<div class="jinsom-setting-box">


<?php if(jinsom_is_login_type('wechat_mp')||jinsom_is_login_type('wechat_code')){?>
<?php if(get_user_meta($author_id,'weixin_uid',true)||get_user_meta($author_id,'weixin_pc_uid',true)){?>
<li>
<a href="#" class="link" onclick="jinsom_social_login_off('wechat',<?php echo $author_id;?>,this)">
<span class="title"><?php _e('微信登录','jinsom');?></span>	
<span class="b"><n><?php _e('点击解绑','jinsom');?></n><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }else{?>
<?php if(jinsom_is_login_type('wechat_mp')&&is_wechat()){?>
<li>
<a href="<?php echo jinsom_oauth_url('wechat_mp');?>">
<span class="title"><?php _e('微信登录','jinsom');?></span>	
<span class="a"><?php _e('未绑定','jinsom');?><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }else if(jinsom_is_login_type('wechat_code')&&!is_wechat()){?>
<li>
<a href="<?php echo jinsom_oauth_url('wechat_code');?>">
<span class="title"><?php _e('微信登录','jinsom');?></span>	
<span class="a"><?php _e('未绑定','jinsom');?><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }?>

<?php }?>
<?php }?>


<?php if(jinsom_is_login_type('qq')){?>
<?php if($jinsom_qq_avatar){?>
<li>
<a href="#" class="link" onclick="jinsom_social_login_off('qq',<?php echo $author_id;?>,this)">
<span class="title"><?php _e('QQ登录','jinsom');?></span>	
<span class="b"><n><?php _e('点击解绑','jinsom');?></n><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }else{?>
<li>
<a href="<?php echo jinsom_oauth_url('qq');?>">
<span class="title"><?php _e('QQ登录','jinsom');?></span>	
<span class="a"><?php _e('未绑定','jinsom');?><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }?>
<?php }?>


<?php if(jinsom_is_login_type('weibo')){?>
<?php if($jinsom_weibo_avatar){?>
<li>
<a href="#" class="link" onclick="jinsom_social_login_off('weibo',<?php echo $author_id;?>,this)">
<span class="title"><?php _e('微博登录','jinsom');?></span>	
<span class="b"><n><?php _e('点击解绑','jinsom');?></n><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }else{?>
<li>
<a href="<?php echo jinsom_oauth_url('weibo');?>">
<span class="title"><?php _e('微博登录','jinsom');?></span>	
<span class="a"><?php _e('未绑定','jinsom');?><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }?>
<?php }?>

<?php if(jinsom_is_login_type('github')){?>
<?php if($jinsom_github_avatar){?>
<li>
<a href="#" class="link" onclick="jinsom_social_login_off('github',<?php echo $author_id;?>,this)">
<span class="title"><?php _e('Github登录','jinsom');?></span>	
<span class="b"><n><?php _e('点击解绑','jinsom');?></n><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }else{?>
<li>
<a href="<?php echo jinsom_oauth_url('github');?>">
<span class="title"><?php _e('Github登录','jinsom');?></span>	
<span class="a"><?php _e('未绑定','jinsom');?><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }?>
<?php }?>


</div>



<?php 
if(jinsom_is_admin($user_id)){
if($user_info->user_power==2){
$user_power=__('网站管理','jinsom');	
}else if($user_info->user_power==3){
$user_power=__('巡查员','jinsom');	
}else if($user_info->user_power==5){
$user_power=__('审核员','jinsom');	
}else if($user_info->user_power==4){
$user_power=__('风险用户(禁止登录)','jinsom');	
}else{
$user_power=__('正常用户','jinsom');		
}
?>
<div class="jinsom-setting-box">

<?php if($user_id!=$author_id){?>

<?php if(current_user_can('level_10')){?>
<li class="user_power">
<a href="#" class="link">
<select>
<option value ="1" <?php if($user_info->user_power==1) echo 'selected = "selected"'; ?>><?php _e('正常用户','jinsom');?></option>
<option value ="2" <?php if($user_info->user_power==2) echo 'selected = "selected"'; ?>><?php _e('网站管理','jinsom');?></option>
<option value ="3" <?php if($user_info->user_power==3) echo 'selected = "selected"'; ?>><?php _e('巡查员','jinsom');?></option>
<option value ="5" <?php if($user_info->user_power==5) echo 'selected = "selected"'; ?>><?php _e('审核员','jinsom');?></option>
<option value ="4" <?php if($user_info->user_power==4) echo 'selected = "selected"'; ?>><?php _e('风险账户--[不能登录]','jinsom');?></option>
</select>
<span class="title"><?php _e('用户组','jinsom');?></span>	
<span class="value"><?php echo $user_power;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }?>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'danger_reason',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('封号原因','jinsom');?></span>	
<span class="value"><?php echo $user_info->danger_reason;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="blacklist">
<input type="date" id="jinsom-setting-time" value="<?php echo $user_info->blacklist_time;?>">
<a href="#" class="link">
<span class="title"><?php _e('黑名单','jinsom');?></span>	
<span class="value"><?php echo $user_info->blacklist_time;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'blacklist_reason',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('黑名单原因','jinsom');?></span>	
<span class="value"><?php echo $user_info->blacklist_reason;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<?php }?>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'credit',this,'admin')">
<a href="#" class="link">
<span class="title"><?php echo jinsom_get_option('jinsom_credit_name');?></span>	
<span class="value"><?php echo (int)$user_info->credit;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'user_honor',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('头衔','jinsom');?></span>	
<span class="value"><?php echo $user_info->user_honor;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'exp',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('经验','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->exp;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'sign_c',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('累计签到','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->sign_c;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'sign_card',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('补签卡','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->sign_card;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'nickname_card',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('改名卡','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->nickname_card;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="vip-time">
<input type="date" id="jinsom-setting-time" value="<?php echo $user_info->vip_time;?>">
<a href="#" class="link">
<span class="title"><?php _e('VIP到期时间','jinsom');?></span>	
<span class="value"><?php echo $user_info->vip_time;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'vip_number',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('VIP成长值','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->vip_number;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'charm',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('魅力值','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->charm;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>


<li class="verify">
<a href="#" class="link">
<select>
<option value ="0" data="<?php _e('普通用户','jinsom');?>" <?php if($user_info->verify==0) echo 'selected = "selected"'; ?>><?php _e('普通用户','jinsom');?></option>
<option value ="1" data="<?php _e('个人认证','jinsom');?>" <?php if($user_info->verify==1) echo 'selected = "selected"'; ?>><?php _e('个人认证','jinsom');?></option>
<option value ="2" data="<?php _e('企业认证','jinsom');?>" <?php if($user_info->verify==2) echo 'selected = "selected"'; ?>><?php _e('企业认证','jinsom');?></option>
<option value ="3" data="<?php _e('女神认证','jinsom');?>" <?php if($user_info->verify==3) echo 'selected = "selected"'; ?>><?php _e('女神认证','jinsom');?></option>
<option value ="4" data="<?php _e('达人认证','jinsom');?>" <?php if($user_info->verify==4) echo 'selected = "selected"'; ?>><?php _e('达人认证','jinsom');?></option>
<?php 
if($verify_add){
$i=5;
foreach ($verify_add as $data) {
if($user_info->verify==$i){
$selected='selected = "selected"';
}else{
$selected='';	
}
echo '<option value ="'.$i.'" data="'.$data['name'].'" '.$selected.'>'.$data['name'].'</option>';
$i++;
}
}
?>
</select>
<span class="title"><?php _e('认证类型','jinsom');?></span>	
<span class="value"><?php echo $verify;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="verify-info" onclick="jinsom_update_profile(<?php echo $author_id;?>,'verify_info',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('认证说明','jinsom');?></span>	
<span class="value"><?php  echo $user_info->verify_info;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

</div>

<div class="jinsom-setting-box">

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'today_invite_number',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('今日推广人数','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->today_invite_number;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'invite_number',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('总推广人数','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->invite_number;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'publish_bbs_times',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('今日发帖数','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->publish_bbs_times;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'comment_bbs_times',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('今日回帖数','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->comment_bbs_times;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'publish_post_times',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('今日发动态数','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->publish_post_times;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li onclick="jinsom_update_profile(<?php echo $author_id;?>,'comment_post_times',this,'admin')">
<a href="#" class="link">
<span class="title"><?php _e('今日评论动态数','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->comment_post_times;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>


</div>


<div class="jinsom-setting-box">

<li>
<a href="#" class="link">
<span class="title"><?php _e('注册时间','jinsom');?></span>	
<span class="value"><?php echo jinsom_timeago($user_info->user_registered);?></span>
</a>
</li>

<li>
<a href="#" class="link">
<span class="title"><?php _e('注册类型','jinsom');?></span>	
<span class="value"><?php echo jinsom_get_reg_type($author_id);?></span>
</a>
</li>

<li>
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting-ip.php?ip=<?php echo $user_info->latest_ip;?>" class="link">
<span class="title"><?php _e('最后IP','jinsom');?></span>	
<span class="value"><?php echo $user_info->latest_ip;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li>
<a href="#" class="link">
<span class="title"><?php _e('最后在线','jinsom');?></span>	
<span class="value"><?php echo jinsom_timeago($user_info->latest_login);?> (<?php echo jinsom_get_online_type($user_id);?>)</span>
</a>
</li>
</div>
<?php }?>


<div class="jinsom-setting-login-out" onclick="jinsom_login_out()"><?php _e('退出登录','jinsom');?></div>


</div>
</div>        