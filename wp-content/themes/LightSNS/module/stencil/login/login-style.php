<?php
//弹窗选择登录方式
require( '../../../../../../wp-load.php');
$jinsom_login_add=jinsom_get_option('jinsom_login_add');
echo '<div class="jinsom-login-type clear">';
if($jinsom_login_add){
foreach($jinsom_login_add as $data){
if(!$data['in_mobile']){
$type=$data['jinsom_login_add_type'];
$icon=$data['icon'];
$color=$data['color'];
if($type=='username'){
$login_url="javascript:jinsom_login_form('密码登录','login-password',350)";
$default_icon='<i class="jinsom-icon jinsom-qixin-qunzu"></i>';
}else if($type=='phone'){
$login_url="javascript:jinsom_login_form('手机号登录','login-phone',350)";
$default_icon='<i class="jinsom-icon jinsom-shoujihao"></i>';	
}else if($type=='qq'){
$login_url=jinsom_oauth_url('qq');
$default_icon='<i class="jinsom-icon jinsom-qq"></i>';	
}else if($type=='weibo'){
$login_url=jinsom_oauth_url('weibo');
$default_icon='<i class="jinsom-icon jinsom-weibo"></i>';	
}else if($type=='wechat_code'){//扫码关注公众号登录
$login_url=jinsom_oauth_url('wechat_code');
$default_icon='<i class="jinsom-icon jinsom-weixin"></i>';	
}else if($type=='wechat_follow'){//微信电脑端扫码登录
$login_url=jinsom_oauth_url('wechat_follow');
$default_icon='<i class="jinsom-icon jinsom-weixin"></i>';	
}else if($type=='github'){
$login_url=jinsom_oauth_url('github');
$default_icon='<i class="jinsom-icon jinsom-huaban88"></i>';	
}else if($type=='alipay'){
$login_url=jinsom_oauth_url('alipay');
$default_icon='<i class="jinsom-icon jinsom-payIcon-aliPay"></i>';	
}else if($type=='password'){
$login_url='javascript:jinsom_get_password_one_form()';
$default_icon='<i class="jinsom-icon jinsom-wenhao"></i>';	
}else if($type=='custom'){
$login_url=do_shortcode($data['custom']);
$default_icon='<i class="jinsom-icon jinsom-qixin-qunzu"></i>';	
}
if(!$icon){$icon=$default_icon;}
if($type!='wechat_mp'){

if($type=='qq'||$type=='weibo'||$type=='wechat_code'||$type=='wechat_follow'||$type=='github'||$type=='alipay'){
$onclick='onclick="jinsom_login_back_url()"';
}else{
$onclick='';
}

echo '<li class="'.$type.' opacity"><a href="'.$login_url.'" '.$onclick.'><span style="color:'.$color.'">'.$icon.'</span><p>'.$data['name'].'</p></a></li>';  
}
}
}
}else{
echo jinsom_empty('请在后台-登录注册-基本设置-添加登录选项<br>默认登录地址：'.home_url().'/wp-login.php?abc=123');
}

echo '</div>';
