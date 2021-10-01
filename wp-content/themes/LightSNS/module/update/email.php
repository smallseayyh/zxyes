<?php
//修改用户邮箱
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$user_id = $_POST['author_id'];	
}

//判断是否黑名单
if(jinsom_is_black($user_id)&&!jinsom_is_admin($current_user->ID)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}

$code=$_POST['code'];//验证码


if(isset($_POST['code'])||isset($_POST['mail'])){
$mail = $_POST['mail'];
if(jinsom_is_admin($current_user->ID)||jinsom_get_option('jinsom_email_style')=='close'){
$user_info = get_userdata($user_id);
$old_mail=$user_info->user_email;

if($old_mail==$mail){//如果填的邮箱是当前的邮箱 则不变
$data_arr['code']=1;
$data_arr['msg']='绑定成功！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

if( email_exists($mail)){
$data_arr['code']=0;
$data_arr['msg']='该邮箱已经被绑定了！';
}elseif(!is_email($mail)){
$data_arr['code']=0;
$data_arr['msg']='邮箱格式错误！';
}else{
$data = array();
$data['ID'] = $user_id;
$data['user_email'] = $mail;
wp_update_user($data);
$data_arr['code']=1;
$data_arr['msg']='绑定成功！';
}


}else{//判断是否管理员||是否开启了邮箱配置

if(email_exists($mail)){
$data_arr['code']=0;
$data_arr['msg']='该邮箱已经被绑定了！';
}elseif(!is_email($mail)){
$data_arr['code']=0;
$data_arr['msg']='邮箱格式错误！';
}else{
global $wpdb;
$table_name = $wpdb->prefix . 'jin_code';
$check_code= $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE code_number='$code' and code_main='$mail'"); 
if($check_code>0){
$data = array();
$data['ID'] = $user_id;
$data['user_email'] = $mail;
wp_update_user( $data );
$data_arr['code']=1;
$data_arr['msg']='绑定成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='验证码错误！';
}

}

}//判断是否管理员结束
header('content-type:application/json');
echo json_encode($data_arr);

}

