<?php 
require( '../../wp-load.php');
if(is_user_logged_in()){
wp_redirect(home_url());
exit();
}

$type=strip_tags($_POST['type']);
$reg_type=strip_tags($_COOKIE['reg_type']);
$unionid=strip_tags($_COOKIE['unionid']);
$client_id=strip_tags($_COOKIE['openid']);
$nickname=strip_tags($_COOKIE['nickname']);
$avatar=strip_tags($_COOKIE['avatar']);
$gender=strip_tags($_COOKIE['gender']);

if(!$type||!$reg_type||!$client_id||!$avatar||!$nickname){
jinsom_delete_login_cookie();
$data_arr['code']=0;
$data_arr['msg']='请求失败！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

if($reg_type=='qq'){
$unionid_meta_key='qq_unionid';
$client_meta_key='qq_openid';
}else if($reg_type=='weibo'){
$unionid_meta_key='';
$client_meta_key='weibo_uid';
}else if($reg_type=='github'){
$unionid_meta_key='';
$client_meta_key='github_openid';	
}else if($reg_type=='alipay'){
$unionid_meta_key='';
$client_meta_key='alipay_openid';	
}else if($reg_type=='wechat_code'){
$unionid_meta_key='wechat_unionid';
$client_meta_key='weixin_pc_uid';
}else if($reg_type=='wechat_mp'){
$unionid_meta_key='wechat_unionid';
$client_meta_key='weixin_uid';
}

global $wpdb;

if($type=='add'){//新创建

if(!jinsom_is_reg_type($reg_type)){
$data_arr['code']=0;
$data_arr['msg']='创建失败！网站未开启该注册方式！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if($unionid){
$union_data=$wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='$unionid_meta_key' AND meta_value='$unionid' limit 1");
if($union_data){
jinsom_delete_login_cookie();
$data_arr['code']=0;
$data_arr['msg']='创建失败！已存在相同的unionid！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

$appid_data=$wpdb->get_var("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='$client_meta_key' AND meta_value='$client_id' limit 1");
if($appid_data){
jinsom_delete_login_cookie();
$data_arr['code']=0;
$data_arr['msg']='创建失败！已存在相同的client_id！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$nickname=str_replace(' ','',$nickname);//去掉空格
$nickname=jinsom_filter_emoji($nickname);//过滤emoji
$name_max=jinsom_get_option('jinsom_reg_name_max');
$nickname=mb_substr($nickname,0,$name_max,'utf-8');
if(!$nickname){
$nickname='用户_'.rand(0,9999);
}else{
if(jinsom_nickname_exists($nickname)){
$nickname=$nickname.'_'.rand(0,999);
}
}

$userdata=array(
'user_login'=> 'social_'.time().rand(100,999),
'user_pass'=>wp_generate_password($length=12,$include_standard_special_chars=false),
'nickname'=> $nickname
);
$user_id=wp_insert_user($userdata);


jinsom_bind_login_meta('add',$user_id,$reg_type,$avatar,$gender,$client_id,$unionid);//写入meta数据
update_user_meta($user_id,'nickname_card',1);//社交登录的默认赠送一张改名卡

wp_set_auth_cookie($user_id,true);
$data_arr['code']=1;
$data_arr['msg']='创建成功！';
jinsom_delete_login_cookie();
}





if($type=='bind'){//绑定


$username=$_POST['username'];
$password=$_POST['password'];

if($username==''||$password==''){
$data_arr['code']=0;
$data_arr['msg']=__('帐号或密码不能为空！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

if(preg_match("/^1[3456789]{1}\d{9}$/",$username)){//如果是手机号
$user_query = new WP_User_Query( array ( 
'meta_key' => 'phone',
'count_total'=>false,
'meta_value'   => $username,
'number' =>1
));

if (!empty($user_query->results)){//存在这个手机号
foreach ($user_query->results as $user){
$username=$user->user_login;
}
}
}

$user_id=jinsom_get_author_id($username);//根据用户名获取用户id

if(get_user_meta($user_id,'user_power',true)!=4){//风险检测

$login_data=array();   
$login_data['user_login']=$username;   
$login_data['user_password']=$password;  
$login_data['remember']=true;

$user_verify=wp_signon($login_data,is_ssl());  
if(is_wp_error($user_verify)){   
$data_arr['code']=0;
$data_arr['msg']=__('绑定失败！帐号或密码错误！','jinsom');   
}else{  

jinsom_bind_login_meta('bind',$user_id,$reg_type,$avatar,$gender,$client_id,$unionid);//写入meta数据
$data_arr['code']=1;
$data_arr['msg']='绑定成功！';  
jinsom_delete_login_cookie();//删除cookie  
}

}else{
$data_arr['code']=0;
if(get_user_meta($user_id,'danger_reason',true)){
$data_arr['msg']=__('你的帐号已被限制登录，请联系管理员！<br>'.get_user_meta($user_id,'danger_reason',true),'jinsom');
}else{
$data_arr['msg']=__('你的帐号已被限制登录，请联系管理员！','jinsom');	
}

}


}



function jinsom_delete_login_cookie(){
setcookie('openid','',time()-300);
setcookie('unionid','',time()-300);
setcookie('reg_type','',time()-300);
setcookie('avatar','',time()-300);
setcookie('nickname','',time()-300);
setcookie('gender','',time()-300);
}

function jinsom_bind_login_meta($type,$user_id,$reg_type,$avatar,$gender,$client_id,$unionid){
if($reg_type=='qq'){

if($type=='bind'){
if(get_user_meta($user_id,"qq_openid",true)||get_user_meta($user_id,"qq_unionid",true)){
wp_clear_auth_cookie();
$data_arr['code']=0;
$data_arr['msg']='该帐号已经被绑定了，请换一个帐号尝试！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
update_user_meta($user_id,'reg_type','qq');
}

update_user_meta($user_id,"qq_openid",$client_id);
if($unionid){
update_user_meta($user_id,"qq_unionid",$unionid);
}
update_user_meta($user_id,"qq_avatar",$avatar);
update_user_meta($user_id,"gender",$gender);
update_user_meta($user_id,"avatar_type",'qq');


}else if($reg_type=='weibo'){//微博

if($type=='bind'){
if(get_user_meta($user_id,"weibo_uid",true)){
wp_clear_auth_cookie();
$data_arr['code']=0;
$data_arr['msg']='该帐号已经被绑定了，请换一个帐号尝试！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
update_user_meta($user_id,'reg_type','weibo');
}

update_user_meta($user_id,"weibo_uid",$client_id);
update_user_meta($user_id,"weibo_avatar",$avatar);
update_user_meta($user_id ,"gender",$gender);
update_user_meta($user_id,"avatar_type",'weibo');


}else if($reg_type=='github'){//github
if($type=='bind'){
if(get_user_meta($user_id,"github_openid",true)){
wp_clear_auth_cookie();
$data_arr['code']=0;
$data_arr['msg']='该帐号已经被绑定了，请换一个帐号尝试！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
update_user_meta($user_id,'reg_type','github');	
}

update_user_meta($user_id,"github_openid",$client_id);
update_user_meta($user_id,"github_avatar",$avatar);
// update_user_meta($user_id ,"gender",$gender);
update_user_meta($user_id,"avatar_type",'github');


}else if($reg_type=='alipay'){//支付宝
if($type=='bind'){
if(get_user_meta($user_id,"alipay_openid",true)){
wp_clear_auth_cookie();
$data_arr['code']=0;
$data_arr['msg']='该帐号已经被绑定了，请换一个帐号尝试！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
update_user_meta($user_id,'reg_type','alipay');
}

update_user_meta($user_id,"alipay_openid",$client_id);
update_user_meta($user_id,"alipay_avatar",$avatar);
update_user_meta($user_id,"gender",$gender);
update_user_meta($user_id,"avatar_type",'alipay');
	

}else if($reg_type=='wechat_mp'){//微信公众号
if($type=='bind'){
if(get_user_meta($user_id,"weixin_uid",true)){
wp_clear_auth_cookie();
$data_arr['code']=0;
$data_arr['msg']='该帐号已经被绑定了，请换一个帐号尝试！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
update_user_meta($user_id,'reg_type','wechat-mp');
}

update_user_meta($user_id,"weixin_uid",$client_id);
if($unionid){
update_user_meta($user_id,"wechat_unionid",$unionid);
}
update_user_meta($user_id,"wechat_avatar",$avatar);
update_user_meta($user_id ,"gender",$gender);
update_user_meta($user_id,"avatar_type",'wechat');	

}else if($reg_type=='wechat_code'){//微信电脑端扫码
if($type=='bind'){
if(get_user_meta($user_id,"weixin_pc_uid",true)){
wp_clear_auth_cookie();
$data_arr['code']=0;
$data_arr['msg']='该帐号已经被绑定了，请换一个帐号尝试！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
update_user_meta($user_id,'reg_type','wechat-code');
}

update_user_meta($user_id,"weixin_pc_uid",$client_id);
if($unionid){
update_user_meta($user_id,"wechat_unionid",$unionid);
}
update_user_meta($user_id,"wechat_avatar",$avatar);
update_user_meta($user_id ,"gender",$gender);
update_user_meta($user_id,"avatar_type",'wechat');
	
}

}


header('content-type:application/json');
echo json_encode($data_arr);