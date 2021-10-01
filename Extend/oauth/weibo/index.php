<?php
//微博登录
require( '../../../wp-load.php');
$jinsom_social_login_tab=jinsom_get_option('jinsom_social_login_tab');
$client_id=$jinsom_social_login_tab['jinsom_login_weibo_key'];
$client_secret=$jinsom_social_login_tab['jinsom_login_weibo_secret'];

if(isset($_GET['code'])){
$code=$_GET['code'];
$post_data=array(
'client_id'=>$client_id,
'client_secret'=>$client_secret,
'grant_type'=>'authorization_code',
'redirect_uri'=>home_url('/Extend/oauth/weibo/index.php'),
'code' => $code
);
$response=wp_remote_post('https://api.weibo.com/oauth2/access_token',array('method'=>'POST','body'=>$post_data));

if(is_array($response)&&!is_wp_error($response)&&$response['response']['code']=='200'){
$data=json_decode($response['body'],true);
$access_token=$data['access_token'];
$openid=$data['uid'];

// print_r($response['body']);
$response=wp_remote_get('https://api.weibo.com/2/users/show.json?uid='.$openid.'&access_token='.$access_token);
if(is_array($response)&&!is_wp_error($response)&&$response['response']['code']=='200'){
$body=$response['body'];
// print_r($body);
$data=json_decode($body,true);

$nickname=$data['screen_name'];
$avatar=$data['avatar_large'];
$gender=$data['gender'];
$description=$data['description'];
if($data['gender']=='f'){$gender='女生';}else if($data['gender']=='m'){$gender='男生';}else{$gender='保密';}


$appid_data=$wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='weibo_uid' AND meta_value='$openid' limit 1");
if(is_user_logged_in()){//绑定

if($appid_data){
wp_die('该微博已经绑定了本站其他帐号，请使用该微博登录本站，前往个人主页解绑该微博，然后再重新尝试！<br>3秒后自动跳转首页....<meta http-equiv="Refresh" content="3; url='.home_url().'"/>','提示');
}else{
$user_id=$current_user->ID;
update_user_meta($user_id,"weibo_uid",$openid);
update_user_meta($user_id,"weibo_avatar",$avatar);
update_user_meta($user_id,"gender",$gender);//性别


wp_die('绑定成功！<br>3秒后自动跳转首页....<meta http-equiv="Refresh" content="3; url='.home_url().'"/>','提示');
}

}else{//注册||登录

if($appid_data){//登录

//获取用户ID
foreach ($appid_data as $data){
$user_id=$data->user_id;
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


update_user_meta($user_id,"weibo_avatar",$avatar);
update_user_meta($user_id,"gender",$gender);//性别
update_user_meta($user_id,"description",$description);//个人说明
wp_set_auth_cookie($user_id,true);

if(isset($_COOKIE['login_back'])){
wp_redirect($_COOKIE['login_back']);
}else{
wp_redirect(home_url());
}

}else{//新帐号===注册
setcookie('openid',$openid,time()+300,'/');
setcookie('unionid','',time()+300,'/');
setcookie('reg_type','weibo',time()+300,'/');
setcookie('avatar',$avatar,time()+300,'/');
setcookie('nickname',$nickname,time()+300,'/');
setcookie('gender',$gender,time()+300,'/');
wp_redirect(home_url('/Extend/oauth/index.php'));
}


}



}else{
wp_die('获取微博用户信息失败！','提示');
}
}else{
wp_die('获取access_token失败！','提示');
}
}else{
wp_die('不存在code值！','提示');
}

