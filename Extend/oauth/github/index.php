<?php 
//Github登录
require( '../../../wp-load.php');
$jinsom_social_login_tab=jinsom_get_option('jinsom_social_login_tab');
$client_id=$jinsom_social_login_tab['jinsom_login_github_id'];
$client_secret=$jinsom_social_login_tab['jinsom_login_github_secret'];
if(isset($_GET['code'])){
$code=$_GET['code'];
$response=wp_remote_get('https://github.com/login/oauth/access_token?client_id='.$client_id.'&client_secret='.$client_secret.'&code='.$code);
// print_r($response);
if(is_array($response)&&!is_wp_error($response)&&$response['response']['code']=='200'){
$body=$response['body'];
$body_arr=explode("&",$body);
$access_token_arr=explode("=",$body_arr[0]);
$access_token=$access_token_arr[1];
$info_body=jinsom_github_login_sendRequest('https://api.github.com/user',[],[
"Accept: application/json",
"User-Agent:LightSNS",
"Authorization:token {$access_token}"
]);

// echo $info_body;
if($info_body){
$data=json_decode($info_body,true);
// print_r($data);

$nickname=$data['login'];
$avatar=$data['avatar_url']; 
$openid=$data['node_id'];

$appid_data=$wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='github_openid' AND meta_value='$openid' limit 1");
if(is_user_logged_in()){//绑定

if($appid_data){
wp_die('该Github帐号已经绑定了本站其他帐号，请使用该Github帐号登录本站，前往个人主页解绑该Github帐号，然后再重新尝试！<br>3秒后自动跳转首页....<meta http-equiv="Refresh" content="3; url='.home_url().'"/>','提示');
}else{
$user_id=$current_user->ID;
update_user_meta($user_id,"github_openid",$openid);
update_user_meta($user_id,"github_avatar",$avatar);


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


update_user_meta($user_id,"github_avatar",$avatar);
wp_set_auth_cookie($user_id,true);

if(isset($_COOKIE['login_back'])){
wp_redirect($_COOKIE['login_back']);
}else{
wp_redirect(home_url());
}

}else{//新帐号===注册
setcookie('openid',$openid,time()+300,'/');
setcookie('unionid','',time()+300,'/');
setcookie('reg_type','github',time()+300,'/');
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
wp_die('获取access_token失败！','提示');
}
}else{
wp_die('不存在code值！','提示');
}



function jinsom_github_login_sendRequest($url, $data = [], $headers = []){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
if (!empty($data)) {
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
}
$response = curl_exec($ch) ? curl_multi_getcontent($ch) : '';
curl_close($ch);
return $response;
}
