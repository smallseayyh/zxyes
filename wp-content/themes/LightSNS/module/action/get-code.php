<?php
//获取各种验证码
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
require( 'sms.php' );
$jinsom_sms_style=jinsom_get_option('jinsom_sms_style');
$type=strip_tags($_POST['type']);

if((jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("code",jinsom_get_option('jinsom_machine_verify_use_for')))&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])){
if(!jinsom_machine_verify($_POST['ticket'],$_POST['randstr'])){
$data_arr['code']=0;
$data_arr['msg']='安全验证失败！请重新进行操作！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

$jinsom_site_name=jinsom_get_option('jinsom_site_name');//网站名称

//忘记密码  获取邮件验证码
if($type=='pass-email'){
$user_id=$_POST['user_id'];
$user_info=get_userdata($user_id);
$mail=$user_info->user_email;
$ip = $_SERVER['REMOTE_ADDR'];
if(!jinsom_code_often()){//不频繁获取
$code=jinsom_random(8);
if(email_exists($mail)){
jinsom_send_email($mail,$jinsom_site_name.'-验证码','你的验证码是：'.$code.'，如非本人操作，请忽略！');
global $wpdb;
$table_name = $wpdb->prefix . 'jin_code';
$time=current_time('mysql');
$wpdb->query( " DELETE FROM $table_name WHERE code_main='$mail'; " );
$wpdb->query( "INSERT INTO $table_name (code_main,code_number,code_ip,code_type,code_time) VALUES ('$mail','$code','$ip','mail','$time')" );
$data_arr['code']=1;
$data_arr['msg']='验证码已经发送到你邮箱！';
}else{
$data_arr['code']=0;
$data_arr['msg']='数据异常！mail';
}
}else{
$data_arr['code']=0;
$data_arr['msg']='两分钟只能获取一次验证码！';
}

header('content-type:application/json');
echo json_encode($data_arr);
}

//忘记密码  获取手机验证码
if($type=='pass-phone'){
if(jinsom_get_option('jinsom_sms_style')!='close'){
$user_id=(int)$_POST['user_id'];
$phone=get_user_meta($user_id,'phone',true);
$ip = $_SERVER['REMOTE_ADDR'];
if(!jinsom_code_often()){//不频繁获取
if(jinsom_phone_exists($phone)){

if($jinsom_sms_style=='ali'){
$status=jinsom_alisms($phone);
if($status->Code=='OK'){
$data_arr['code']=1;
$data_arr['msg']='验证码已发送到你的手机！';  
}else{
$data_arr['code']=0;
if($status->Code=='isv.MOBILE_NUMBER_ILLEGAL'){
$data_arr['msg']='手机号不合法！';
}else{
$data_arr['msg']=$status->Message.'('.$status->Code.')';
}
}
}else{//腾讯
$status=jinsom_tentsms($phone);
if($status->errmsg=='OK'){
$data_arr['code']=1;
$data_arr['msg']='验证码已发送到你的手机！';  
}else{
$data_arr['code']=0;
$data_arr['msg']=$status->errmsg;
}
}



}else{
$data_arr['code']=0;
$data_arr['msg']='数据异常！phone';
}
}else{
$data_arr['code']=0;
$data_arr['msg']='两分钟只能获取一次验证码！';
}
}else{
$data_arr['code']=0;
$data_arr['msg']='网站未配置短信功能-登录模块-短信配置！';    
}

header('content-type:application/json');
echo json_encode($data_arr);
}










//====================注册获取邮件验证码================
if($type=='reg-email'){
$ip = $_SERVER['REMOTE_ADDR'];
if(!jinsom_code_often()){//不频繁获取
$code=jinsom_random(8);
$mail=$_POST['mail'];
if(jinsom_get_option('jinsom_email_style')=='close'){
$data_arr['code']=0;
$data_arr['msg']='获取失败！网站未开启邮件服务！';
}else if( email_exists($mail)){
$data_arr['code']=0;
$data_arr['msg']='该邮箱已经被使用！';
}else if(!is_email($mail)){
$data_arr['code']=0;
$data_arr['msg']='邮箱格式错误！';
}else{
jinsom_send_email($mail,$jinsom_site_name.'-验证码','你的验证码是：'.$code.'，如非本人操作，请忽略！');
global $wpdb;
$table_name = $wpdb->prefix . 'jin_code';
$time=current_time('mysql');
$wpdb->query( " DELETE FROM $table_name WHERE code_main='$mail';" );
$wpdb->query( "INSERT INTO $table_name (code_main,code_number,code_ip,code_type,code_time) VALUES ('$mail','$code','$ip','mail','$time')" );
$data_arr['code']=1;
$data_arr['msg']='验证码已发送到你的邮箱！';
}
}else{
$data_arr['code']=0;
$data_arr['msg']='两分钟只能获取一次验证码！';
}

header('content-type:application/json');
echo json_encode($data_arr);
}



//====================注册获取手机验证码|修改手机号===============
if($type=='reg-phone'||$type=='phone-login'){

if(jinsom_get_option('jinsom_sms_style')!='close'){
$phone=(int)$_POST['phone'];


if($type!='phone-login'){
if(jinsom_phone_exists($phone)){
$data_arr['code']=0;
$data_arr['msg']='手机号码已经被使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else{//手机号登录
if(!jinsom_phone_exists($phone)){
$data_arr['code']=0;
$data_arr['msg']='该手机号还没有注册！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

if(!jinsom_code_often()){//不频繁获取
	
if($jinsom_sms_style=='ali'){
$status=jinsom_alisms($phone);
if($status->Code=='OK'){
$data_arr['code']=1;
$data_arr['msg']='验证码已发送到你的手机！';  
}else{
$data_arr['code']=0;
if($status->Code=='isv.MOBILE_NUMBER_ILLEGAL'){
$data_arr['msg']='手机号不合法！';
}else{
$data_arr['msg']=$status->Message.'('.$status->Code.')';
}
}
}else{//腾讯
$status=jinsom_tentsms($phone);
if($status->errmsg=='OK'){
$data_arr['code']=1;
$data_arr['msg']='验证码已发送到你的手机！';  
}else{
$data_arr['code']=0;
$data_arr['msg']=$status->errmsg;
}
}

}else{
$data_arr['code']=0;
$data_arr['msg']='两分钟只能获取一次验证码！';
}


}else{
$data_arr['code']=0;
$data_arr['msg']='网站未配置短信功能-登录模块-短信配置！';    
}
header('content-type:application/json');
echo json_encode($data_arr);
}


