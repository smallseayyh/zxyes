<?php
//忘记密码 第一步提交
require( '../../../../../wp-load.php' );

if(isset($_POST['type'])&&$_POST['type']==1){
$username=strip_tags($_POST['username']);

if(!$username){
$data_arr['code']=0;
$data_arr['msg']='请输入手机号/邮箱！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

if(!is_email($username)){//手机号
if(jinsom_phone_exists($username)){//输入的是手机号
$data_arr['code']=1;
$data_arr['user_id']=jinsom_get_user_id_for_phone($username);//用户id
$data_arr['msg']='存在对应的手机号';
}else{
$data_arr['code']=0;
$data_arr['msg']='你输入的手机号/邮箱不存在！';
}

}else{//输入的是邮箱
if(email_exists($username)){
$data_arr['code']=1;
$data_arr['user_id']=jinsom_get_user_id_for_mail($username);//用户id
$data_arr['msg']='存在对应的邮箱';
}else{
$data_arr['code']=0;
$data_arr['msg']='你输入的手机号/邮箱不存在！';
}
}
header('content-type:application/json');
echo json_encode($data_arr);
}

//最后验证  验证码或者答案
if(isset($_POST['code'])||isset($_POST['password'])){
$ip=$_SERVER['REMOTE_ADDR'];
$style=strip_tags($_POST['style']);
$code=strip_tags($_POST['code']);
$password=strip_tags($_POST['password']);

if($code==''||$password==''){
$data_arr['code']=0;
$data_arr['msg']='验证码或新密码不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

$password_min=jinsom_get_option('jinsom_reg_password_min');
$password_max=jinsom_get_option('jinsom_reg_password_max');
$pass_num=mb_strlen($password,'utf-8');
if($pass_num <=$password_max && $pass_num >= $password_min){//判断密码长度

if($style=='email'){//邮箱找回
global $wpdb;
$table_name=$wpdb->prefix.'jin_code';
$check_code=$wpdb->get_results("SELECT code_main FROM $table_name WHERE code_ip='$ip' and code_number='$code';"); 
if(!$check_code){
$data_arr['code']=0;
$data_arr['msg']='验证码不正确！';  
}else{
foreach ($check_code as $data){
$email=$data->code_main;
$user_id=jinsom_get_user_id_for_mail($email);
}

$data = array();
$data['ID'] = $user_id;
$data['user_pass'] = $password;
wp_update_user( $data );
$data_arr['code']=1;
$data_arr['msg']='密码已经修改成功！'; 	
$wpdb->query(" DELETE FROM $table_name WHERE code_number='$code';");
}


}else if($style=='phone'){//手机找回
global $wpdb;
$table_name=$wpdb->prefix.'jin_code';
$check_code=$wpdb->get_results("SELECT code_main FROM $table_name WHERE code_ip='$ip' and code_number='$code';");
if(!$check_code){
$data_arr['code']=0;
$data_arr['msg']='验证码不正确！';  
}else{
foreach ($check_code as $data){
$phone=$data->code_main;
$user_id=jinsom_get_user_id_for_phone($phone);
}

$data = array();
$data['ID'] = $user_id;
$data['user_pass'] = $password;
wp_update_user( $data );
$data_arr['code']=1;
$data_arr['msg']='密码已经修改成功！'; 
$wpdb->query(" DELETE FROM $table_name WHERE code_number='$code';");	
}



}else if($style=='question'){//密保
$user_id=(int)$_POST['user_id'];
$user_info=get_userdata($user_id);
if($user_info->answer!=$code){
$data_arr['code']=0;
$data_arr['msg']='答案不正确！';  
}else{
$data = array();
$data['ID'] = $user_id;
$data['user_pass'] = $password;
wp_update_user( $data );
$data_arr['code']=1;
$data_arr['msg']='密码已经修改成功！'; 
}

}

}else{
$data_arr['code']=0;
$data_arr['msg']='密码长度为'.$password_min.'-'.$password_max.'字符';
}

header('content-type:application/json');
echo json_encode($data_arr);
}
