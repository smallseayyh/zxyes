<?php
//电脑端微信扫码登录
require( '../../../../wp-load.php');
$jinsom_social_login_tab=jinsom_get_option('jinsom_social_login_tab');
$client_id=$jinsom_social_login_tab['jinsom_login_wechat_mp_key'];
$client_secret=$jinsom_social_login_tab['jinsom_login_wechat_mp_secret'];
global $wpdb;

if(isset($_GET['code'])){
$code=$_GET['code'];
$response=wp_remote_get('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$client_id.'&secret='.$client_secret.'&code='.$code.'&grant_type=authorization_code&redirect_uri='.home_url('/Extend/oauth/wechat/mp/index.php'));
if(is_array($response)&&!is_wp_error($response)&&$response['response']['code']=='200'){
$access_token=$response['body'];
$data=json_decode($access_token,true);
if($data['openid']){

$openid=$data['openid'];
$unionid=$data['unionid'];

$response=wp_remote_get('https://api.weixin.qq.com/sns/userinfo?access_token='.$data['access_token'].'&openid='.$data['openid']);
if(is_array($response)&&!is_wp_error($response)&&$response['response']['code']=='200'){

if($unionid){
$union_data=$wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='wechat_unionid' AND meta_value='$unionid' limit 1");
}else{
$union_data=0;	
}

$body=$response['body'];
$data=json_decode($body,true);
// print_r($data);
$nickname=$data['nickname'];
$avatar=$data['headimgurl'];
if(!strstr($avatar,'https')){//不是https地址替换一下
$avatar=str_replace("http","https",$avatar);
}
$gender=$data['sex'];
if($gender==1){$gender='男生';}else if($gender==2){$gender='女生';}else{$gender='保密';}

$appid_data=$wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='weixin_uid' AND meta_value='$openid' limit 1");
if(is_user_logged_in()){//绑定

if($union_data&&$appid_data){
wp_die('该微信帐号已经绑定了本站其他帐号，请使用该微信帐号登录本站，前往个人主页解绑该微信帐号，然后再重新尝试！<br>3秒后自动跳转首页....<meta http-equiv="Refresh" content="3; url='.home_url().'"/>','提示');
}else{
$user_id=$current_user->ID;
update_user_meta($user_id,"weixin_uid",$openid);
update_user_meta($user_id,"wechat_avatar",$avatar);
update_user_meta($user_id,"avatar_type",'wechat');
update_user_meta($user_id,"gender",$gender);//性别
if($unionid){
update_user_meta($user_id,"wechat_unionid",$unionid);	
}

wp_die('绑定成功！<br>3秒后自动跳转首页....<meta http-equiv="Refresh" content="3; url='.home_url().'"/>','提示');
}

}else{//注册||登录

if($union_data||$appid_data){//登录

//获取用户ID
if($union_data){
foreach ($union_data as $data){
$user_id=$data->user_id;
}
}else{
foreach ($appid_data as $data){
$user_id=$data->user_id;
}
}

//判断是否风险用户
if(get_user_meta($user_id,'user_power',true)==4){
if(get_user_meta($user_id,'danger_reason',true)){
wp_die('你的帐号【'.get_user_meta($user_id,'nickname',true).'】已被限制登录，请联系管理员！<br>'.get_user_meta($user_id,'danger_reason',true).'<br>8秒后自动跳转首页....<meta http-equiv="Refresh" content="8; url='.home_url().'"/>','提示');
}else{
wp_die('你的帐号【'.get_user_meta($user_id,'nickname',true).'】已被限制登录，请联系管理员！<br>8秒后自动跳转首页....<meta http-equiv="Refresh" content="8; url='.home_url().'"/>','提示');	
}
exit;
}


update_user_meta($user_id,"wechat_avatar",$avatar);
update_user_meta($user_id,"gender",$gender);//性别
if($unionid){
update_user_meta($user_id,"wechat_unionid",$unionid);	
}
wp_set_auth_cookie($user_id,true);

if(isset($_COOKIE['login_back'])){
wp_redirect($_COOKIE['login_back']);
}else{
wp_redirect(home_url());
}

}else{//新帐号===注册
setcookie('openid',$openid,time()+300,'/');
setcookie('unionid',$unionid,time()+300,'/');
setcookie('reg_type','wechat_mp',time()+300,'/');
setcookie('avatar',$avatar,time()+300,'/');
setcookie('nickname',$nickname,time()+300,'/');
setcookie('gender',$gender,time()+300,'/');
wp_redirect(home_url('/Extend/oauth/index.php'));
}


}

}else{
wp_die('获取用户信息失败！','提示');
}
}else{
wp_die('获取openid失败！','提示');
}
}else{
wp_die('获取access_token失败！','提示');
}
}else{
wp_die('不存在code值！','提示');
}

