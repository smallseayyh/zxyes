<?php
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$user_id = $_POST['user_id'];	
}

$phone_on_off = jinsom_get_option('jinsom_sms_style');//是否开启手机号



//判断是否黑名单
if(jinsom_is_black($user_id)&&!jinsom_is_admin($current_user->ID)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}


$code = $_POST['code'];
if(!$code&&$phone_on_off!='close'&&!jinsom_is_admin($current_user->ID)){
$data_arr['code']=0;
$data_arr['msg']='验证码不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 	
}


//===============================修改用户手机号=======================
if(isset($_POST['code'])||isset($_POST['phone'])){
$phone = $_POST['phone'];

if(jinsom_is_admin($current_user->ID)||$phone_on_off=='close'){//管理员修改无需验证验证码

$old_phone=get_user_meta($user_id,'phone',true);
if($old_phone==$phone){//如果填的手机号是当前的手机号 则不变
$data_arr['code']=1;
$data_arr['msg']='绑定成功！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


if(preg_match("/^1[3456789]{1}\d{9}$/",$phone)){
if(!jinsom_phone_exists($phone)){
update_user_meta($user_id,'phone',$phone);//管理员更新当前主页的用户
$data_arr['code']=1;
$data_arr['msg']='绑定成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='该手机号已经被绑定了！';
}

}else{
$data_arr['code']=0;
$data_arr['msg']='手机号码格式错误！';
}

}else{//不是管理员的情况


if(preg_match("/^1[3456789]{1}\d{9}$/",$phone)){
if(!jinsom_phone_exists($phone)){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_code';
$check_code= $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE code_number='$code' and code_main='$phone'");
if($check_code){
update_user_meta($user_id,'phone',$phone);//当前自己的id
$data_arr['code']=1;
$data_arr['msg']='绑定成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='验证码错误！';
}

}else{
$data_arr['code']=0;
$data_arr['msg']='该手机号已经被绑定了！';
}

}else{
$data_arr['code']=0;
$data_arr['msg']='手机号码格式错误！';
}

}//判断是否管理员结束
header('content-type:application/json');
echo json_encode($data_arr);
}



