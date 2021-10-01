<?php
//登录
require( '../../../../../wp-load.php' );

if((jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("login",jinsom_get_option('jinsom_machine_verify_use_for')))&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])){
if(!jinsom_machine_verify($_POST['ticket'],$_POST['randstr'])){
$data_arr['code']=0;
$data_arr['msg']=__('安全验证失败！请重新进行操作！','jinsom');	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

if(is_user_logged_in()){
$data_arr['code']=1;
$data_arr['msg']=__('登录成功！欢迎回来！','jinsom');	
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(!isset($_POST['phone'])){//密码登录

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
$data_arr['msg']=__('登录失败！帐号或密码错误！','jinsom');   
}else{  
$data_arr['code']=1;
$data_arr['msg']=__('登录成功！欢迎回来！','jinsom');   
}

}else{
$data_arr['code']=0;
if(get_user_meta($user_id,'danger_reason',true)){
$data_arr['msg']=__('你的帐号已被限制登录，请联系管理员！<br>'.get_user_meta($user_id,'danger_reason',true),'jinsom');
}else{
$data_arr['msg']=__('你的帐号已被限制登录，请联系管理员！','jinsom');	
}


}

}else{//手机号登录


if(!jinsom_is_login_type('phone')){
$data_arr['code']=0;
$data_arr['msg']=__('网站未开启该功能！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

if(jinsom_get_option('jinsom_sms_style')=='close'){
$data_arr['code']=0;
$data_arr['msg']=__('网站未配置短信功能，请后台配置后再使用！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}



$phone=(int)$_POST['phone'];
$code=(int)$_POST['code'];

if($phone==''||$code==''){
$data_arr['code']=0;
$data_arr['msg']=__('手机号或验证码不能为空！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


if(!preg_match("/^1[3456789]{1}\d{9}$/",$phone)){
$data_arr['code']=0;
$data_arr['msg']=__('手机号格式错误！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


global $wpdb;
$table_name=$wpdb->prefix.'jin_code';
$check_code=$wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE code_number='$code' and code_main='$phone'");
if($check_code){

$user_query=new WP_User_Query(array( 
'meta_key' => 'phone',
'count_total'=>false,
'meta_value'   =>$phone,
'number' =>1
));

if(!empty($user_query->results)){//存在这个手机号
foreach ($user_query->results as $user){
$user_id=$user->ID;
wp_set_auth_cookie($user_id,true);
$data_arr['code']=1;
$data_arr['msg']=__('登录成功！欢迎回来！','jinsom'); 
}
}else{
$data_arr['code']=0;
$data_arr['msg']=__('该手机号还没有进行注册！','jinsom');	
}

}else{
$data_arr['code']=0;
$data_arr['msg']=__('验证码错误！','jinsom');	
}



}



header('content-type:application/json');
echo json_encode($data_arr);