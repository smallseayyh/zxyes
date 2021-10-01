<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_user_login', array(
'title'       => 'LightSNS_登录模块/签到',
'classname'   => 'jinsom-widget-user-login',
'description' => '侧栏用户登录注册小工具',
'fields'      => array(

array(
'id' => 'social',
'type' => 'switcher',
'default' => false,
'title' => '第三方登录',
'subtitle' => '需要先开启了QQ、微博、微信登录',
) ,


)
));


if(!function_exists('jinsom_widget_user_login')){
function jinsom_widget_user_login($args,$instance){
echo $args['before_widget'];

global $current_user;
$user_id=$current_user->ID;
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
$jinsom_machine_verify_appid=jinsom_get_option('jinsom_machine_verify_appid');
?>
        

<?php if(is_user_logged_in()){?>
<div class="jinsom-sidebar-user-info">
<div class="bg" style="background-image: url(<?php echo jinsom_member_bg($user_id,'small_img');?>);">
<div class="avatarimg">
<a href="<?php echo jinsom_userlink($user_id);?>">
<?php echo jinsom_avatar($user_id,'100',avatar_type($user_id) );?>
<?php echo jinsom_verify($user_id);?>
</a>
</div>    
</div>

<div class="info">
<span class="name"><?php echo jinsom_nickname_link($user_id);?></span>
<?php echo jinsom_sex($user_id); ?>
<?php echo jinsom_vip($user_id);?>
<?php echo jinsom_honor($user_id);?>
</div>

<?php
$user_exp=(int)get_user_meta($user_id,'exp',true);
$max_exp=jinsom_lv_current_max($user_id);
$percent_exp=($user_exp/$max_exp)*100;
if($percent_exp>100){$percent_exp=100;}
?>
<div class="lv">
<div class="title"><span><?php _e('等级','jinsom');?>：<?php echo strip_tags(jinsom_lv($user_id));?></span><span class="lv-number"><?php echo $user_exp;?>/<?php echo $max_exp;?></span></div>
<div class="bar"><span style="width:<?php echo $percent_exp;?>%;"></span></div>
</div>

<!-- 统计 -->
<div class="number">
<li>
<a href="javascript:;">
<strong><?php echo jinsom_following_count($user_id); ?></strong>
<span><?php _e('关注','jinsom');?></span>
</a>
</li>

<li>
<a href="javascript:;">
<strong><?php echo jinsom_follower_count($user_id); ?></strong>
<span><?php _e('粉丝','jinsom');?></span>
</a>
</li>

<li>
<a href="javascript:;">
<strong><?php echo jinsom_count_post($user_id,'all'); ?></strong>
<span><?php _e('喜欢','jinsom');?></span>
</a>
</li>

<li>
<a href="javascript:;">
<strong><?php echo count_user_posts($user_id,'post');?></strong>
<span><?php _e('内容','jinsom');?></span>
</a>
</li>
</div>

<!-- 签到 -->
<?php 
$sign_day= (int)get_user_meta($user_id,'sign_c',true);
if(!jinsom_is_sign($user_id,date('Y-m-d',time()))){?>

<?php if($jinsom_machine_verify_on_off&&in_array("sign",$jinsom_machine_verify_use_for)&&!jinsom_is_admin($user_id)){?>
<div class="sign opacity" id="sign-1"><?php _e('点击签到','jinsom');?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('sign-1'),'<?php echo $jinsom_machine_verify_appid;?>',function(res){
if(res.ret === 0){jinsom_sign(res.ticket,res.randstr,document.getElementById('sign-1'));}
});
</script>
<?php }else{?>
<div class="sign opacity" onclick="jinsom_sign('','',this)"><?php _e('点击签到','jinsom');?></div>
<?php }?>


<?php }else{?>
<div class="sign had">
<a href="<?php echo jinsom_get_option('jinsom_sign_page_url');?>" target="_blank">
<span><?php _e('今日已签到','jinsom');?><m><?php echo sprintf(__( '累计%s天','jinsom'),$sign_day);?></m></span>
</a>
</div>
<?php } ?>
</div>

<?php }else{ //没有登录的情况?>
<div class="jinsom-sidebar-login">
<p class="a"><input type="text" placeholder="<?php echo jinsom_get_option('jinsom_login_placeholder');?>" tabindex="1"  id="jinsom-sidebar-username"></p>
<p class="b"><input type="password" tabindex="2"  id="jinsom-sidebar-password" placeholder="<?php _e('请输入密码','jinsom');?>"></p>

<?php if($jinsom_machine_verify_on_off&&in_array("login",$jinsom_machine_verify_use_for)){?>
<div class="jinsom-sidebar-login-btn opacity" id="login-1"><?php _e('登录','jinsom');?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('login-1'),'<?php echo $jinsom_machine_verify_appid;?>',function(res){
if(res.ret === 0){jinsom_sidebar_login(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<div class="jinsom-sidebar-login-btn opacity" onclick="jinsom_sidebar_login('','');"><?php _e('登录','jinsom');?></div>
<?php }?>	

<span onclick="jinsom_get_password_one_form()">?</span>
<div class="jinsom-sidebar-reg-btn opacity"  onclick="jinsom_login_form('注册帐号','reg-style',400)"><?php _e('注册','jinsom');?></div>
<div class="social clear">
<?php
if(jinsom_is_login_type('phone')){
echo '<li class="phone opacity"><a href=\'javascript:jinsom_login_form("手机号登录","login-phone",350)\' class="phone" rel="nofollow"><i class="jinsom-icon jinsom-shoujihao"></i></a></li>';
}
if(jinsom_is_login_type('qq')){
echo '<li class="qq opacity"><a href="'.jinsom_oauth_url('qq').'" onclick="jinsom_login_back_url()" class="qq" rel="nofollow"><i class="jinsom-icon jinsom-qq"></i></a></li>';
}
if(jinsom_is_login_type('weibo')){
echo '<li class="weibo opacity"><a href="'.jinsom_oauth_url('weibo').'" onclick="jinsom_login_back_url()" class="weibo" rel="nofollow"><i class="jinsom-icon jinsom-weibo"></i></a></li>';	
}
if(jinsom_is_login_type('wechat_code')){
echo '<li class="wechat_code opacity"><a href="'.jinsom_oauth_url('wechat_code').'" onclick="jinsom_login_back_url()" class="wechat" rel="nofollow"><i class="jinsom-icon jinsom-weixin"></i></a></li>';	
}
if(jinsom_is_login_type('github')){
echo '<li class="github opacity"><a href="'.jinsom_oauth_url('github').'" onclick="jinsom_login_back_url()" class="github" rel="nofollow"><i class="jinsom-icon jinsom-icon jinsom-huaban88"></i></a></li>';	
}

?>
</div>
</div>
<?php }


echo $args['after_widget'];
}
}


