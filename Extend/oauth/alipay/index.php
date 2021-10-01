<?php
require( '../../../wp-load.php');

//如果不是支付宝内浏览器
// echo strpos($_SERVER['HTTP_USER_AGENT'],'Alipay');
// if(strpos($_SERVER['HTTP_USER_AGENT'],'Alipay') == false){
// header("Location:alipays://platformapi/startapp?appId=2021002112625189&url=encodeURIComponent(https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=2021002112625189&scope=auth_user&redirect_uri=https://q.jinsom.cn/LightSNS-Extend/login/alipay/index.php&state=2143423)");
// exit();
// }

$jinsom_social_login_tab=jinsom_get_option('jinsom_social_login_tab');
$app_id=$jinsom_social_login_tab['jinsom_login_alipay_appid'];
$rsaKey=$jinsom_social_login_tab['jinsom_login_alipay_rsaKey'];//应用私钥


if(isset($_GET['auth_code'])){
$code=$_GET['auth_code'];

$params=array(
'app_id'     => $app_id,
'method'     => 'alipay.system.oauth.token',
'charset'    => 'UTF-8',
'sign_type'  => 'RSA2',
'timestamp'  => date("Y-m-d H:i:s"),
'version'    => '1.0',
'grant_type' => 'authorization_code',
'code'       => $code,
);
$params['sign']=jinsom_alipay_signature($params,$rsaKey);
$response=jinsom_alipay_login_post('https://openapi.alipay.com/gateway.do', $params);
$data=json_decode($response,true);
// print_r($data);
if($data){
$access_token=$data['alipay_system_oauth_token_response']['access_token'];
$alipay_openid=$data['alipay_system_oauth_token_response']['user_id'];
$_params =array(
'app_id'     => $app_id,
'method'     => 'alipay.user.info.share',
'charset'    => 'UTF-8',
'sign_type'  => 'RSA2',
'timestamp'  => date("Y-m-d H:i:s"),
'version'    => '1.0',
'auth_token' => $access_token,
);

$_params['sign']=jinsom_alipay_signature($_params,$rsaKey);
$response_info=jinsom_alipay_login_post('https://openapi.alipay.com/gateway.do',$_params);
$response_info=iconv('gb2312','utf-8',$response_info);
$data_arr=json_decode($response_info,true);
if($data_arr){
$nickname=$data_arr['alipay_user_info_share_response']['nick_name'];
$avatar=$data_arr['alipay_user_info_share_response']['avatar']; 
$gender=$data_arr['alipay_user_info_share_response']['gender'];
if($gender=='m'){$gender='男生';}else if($gender=='f'){$gender='女生';}else{$gender='保密';}


$appid_data=$wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='alipay_openid' AND meta_value='$alipay_openid' limit 1");
if(is_user_logged_in()){//绑定

if($appid_data){
wp_die('该支付宝帐号已经绑定了本站其他帐号，请使用该支付宝帐号登录本站，前往个人主页解绑该支付宝帐号，然后再重新尝试！<br>3秒后自动跳转首页....<meta http-equiv="Refresh" content="3; url='.home_url().'"/>','提示');
}else{
$user_id=$current_user->ID;
update_user_meta($user_id,"alipay_openid",$alipay_openid);
update_user_meta($user_id,"alipay_avatar",$avatar);


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


update_user_meta($user_id,"alipay_avatar",$avatar);
wp_set_auth_cookie($user_id,true);

if(isset($_COOKIE['login_back'])){
wp_redirect($_COOKIE['login_back']);
}else{
wp_redirect(home_url());
}

}else{//新帐号===注册
setcookie('openid',$alipay_openid,time()+300,'/');
setcookie('unionid','',time()+300,'/');
setcookie('reg_type','alipay',time()+300,'/');
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




//验签过程
function jinsom_alipay_signature($data=[],$rsaKey){
ksort($data);
// $str = Str::buildParams($data);
$except=['sign'];
$param_str = '';
foreach ($data as $k => $v) {
if (in_array($k, $except)) {
continue;
}
$param_str .= $k . '=';
$param_str .= $v;
$param_str .= '&';
}
$str = rtrim($param_str, '&');

$rsaKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
wordwrap($rsaKey, 64, "\n", true) .
"\n-----END RSA PRIVATE KEY-----";
$res=openssl_get_privatekey($rsaKey);
if ($res !== false) {
$sign = '';
openssl_sign($str, $sign, $res, OPENSSL_ALGO_SHA256);
openssl_free_key($res);
return base64_encode($sign);
}
echo '支付宝RSA私钥不正确！';
}


function jinsom_alipay_login_post($url,$post_data) {
$postdata = http_build_query($post_data);
$options = array(
'http' => array(
'method' => 'POST',
'header' => 'Content-type:application/x-www-form-urlencoded',
'content' => $postdata,
'timeout' => 15 * 60 // 超时时间（单位:s）
)
);
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
return $result;
}