<?php
require( '../../../wp-load.php');
$jinsom_social_login_tab=jinsom_get_option('jinsom_social_login_tab');
$client_id=$jinsom_social_login_tab['jinsom_qq_login_appid'];
$client_secret=$jinsom_social_login_tab['jinsom_qq_login_appkey'];
$is_unionid=$jinsom_social_login_tab['jinsom_qq_login_unionid_on_off'];;//是否获取联盟ID
global $wpdb;

if(isset($_GET['code'])){
$code=$_GET['code'];
$response=wp_remote_get('https://graph.qq.com/oauth2.0/token?client_id='.$client_id.'&client_secret='.$client_secret.'&code='.$code.'&grant_type=authorization_code&redirect_uri='.home_url('/Extend/oauth/qq/index.php'));
if(is_array($response)&&!is_wp_error($response)&&$response['response']['code']=='200'){
$access_token=$response['body'];

// print_r($body);
$response=wp_remote_get('https://graph.qq.com/oauth2.0/me?'.$access_token.'&unionid='.$is_unionid.'&fmt=json');
if(is_array($response)&&!is_wp_error($response)&&$response['response']['code']=='200'){
$body=$response['body'];
// print_r($body);
$data=json_decode($body,true);
$openid=$data['openid'];
if($is_unionid){
$unionid=$data['unionid'];
$union_data=$wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='qq_unionid' AND meta_value='$unionid' limit 1");
}else{
$unionid=0;
$union_data=0;	
}

$response=wp_remote_get('https://graph.qq.com/user/get_user_info?'.$access_token.'&oauth_consumer_key='.$client_id.'&openid='.$openid);
if(is_array($response)&&!is_wp_error($response)&&$response['response']['code']=='200'){
$body=$response['body'];
$data=json_decode($body,true);
// print_r($data);
$nickname=$data['nickname'];
$avatar=$data['figureurl_qq_2'];
if(!strstr($avatar,'https')){//不是https地址替换一下
$avatar=str_replace("http","https",$avatar);
}
$gender=$data['gender'];
if($gender=='男'){$gender='男生';}else if($gender=='女'){$gender='女生';}else{$gender='保密';}

$appid_data=$wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='qq_openid' AND meta_value='$openid' limit 1");
if(is_user_logged_in()){//绑定

if($union_data||$appid_data){
wp_die('该QQ帐号已经绑定了本站其他帐号，请使用该QQ帐号登录本站，前往个人主页解绑该QQ帐号，然后再重新尝试！<br>3秒后自动跳转首页....<meta http-equiv="Refresh" content="3; url='.home_url().'"/>','提示');
}else{
$user_id=$current_user->ID;
update_user_meta($user_id,"qq_openid",$openid);
update_user_meta($user_id,"qq_avatar",$avatar);
update_user_meta($user_id,"gender",$gender);//性别
if($is_unionid){
update_user_meta($user_id,"qq_unionid",$unionid);	
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


update_user_meta($user_id,"qq_avatar",$avatar);
update_user_meta($user_id,"gender",$gender);//性别
if($is_unionid){
update_user_meta($user_id,"qq_unionid",$unionid);	
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
setcookie('reg_type','qq',time()+300,'/');
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

