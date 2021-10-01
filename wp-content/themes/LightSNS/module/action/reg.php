<?php
//注册
require( '../../../../../wp-load.php' );

//安全验证
if((jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("reg",jinsom_get_option('jinsom_machine_verify_use_for')))&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])){
if(!jinsom_machine_verify($_POST['ticket'],$_POST['randstr'])){
$data_arr['code']=0;
$data_arr['msg']='安全验证失败！请重新进行操作！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

$name_min=jinsom_get_option('jinsom_reg_name_min');
$name_max=jinsom_get_option('jinsom_reg_name_max');
$password_min=jinsom_get_option('jinsom_reg_password_min');
$password_max=jinsom_get_option('jinsom_reg_password_max');


if(!$name_min){$name_min=2;}
if(!$name_max){$name_max=12;}
if(!$password_min){$password_min=6;}
if(!$password_max){$password_max=20;}

$type=strip_tags($_POST['type']);
$username=$_POST['username'];//昵称
$password=$_POST['password']; 


if($username==''||$password==''){
$data_arr['code']=0;
$data_arr['msg']=__('帐号或密码不能为空！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

$name_num=mb_strlen($username,'utf-8');
$pass_num=mb_strlen($password,'utf-8');

if(strpos($username," ")){
$data_arr['code']=0;//注册失败
$data_arr['msg']='昵称不能含有空格！';  
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}


if(!validate_username($username)){
$data_arr['code']=0;
$data_arr['msg']='昵称格式不合法！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}

if($name_num > $name_max||$name_num < $name_min){
$data_arr['code']=0;
$data_arr['msg']='昵称长度为'.$name_min.'-'.$name_max.'字符';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}


if($type!='simple'&&$type!='reg-invite'){
if(jinsom_nickname_exists($username)){
$data_arr['code']=0;
$data_arr['msg']='该昵称已经被使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}
}else{//简单注册||邀请码注册
if(username_exists($username)){
$data_arr['code']=0;
$data_arr['msg']='该账号已经被使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}	
}



if($pass_num >$password_max||$pass_num<$password_min){
$data_arr['code']=0;
$data_arr['msg']='密码长度为'.$password_min.'-'.$password_max.'字符';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}

//简单注册
if($type=='simple'){
if(jinsom_is_reg_type('simple')){

if(jinsom_nickname_exists($username)){//如果存在昵称
$nickname=$username.'_'.rand(100,9999);
}else{
$nickname=$username;
}

$user_id=wp_create_user(rand(1000000,9999999),$password,0);
jinsom_update_user_login($user_id,$username);//更新实际用户名
if($user_id){
update_user_meta($user_id,'avatar_type','default');
update_user_meta($user_id,'reg_type','simple');
update_user_meta($user_id,'nickname',$nickname);
wp_update_user(array('ID'=>$user_id));
wp_set_auth_cookie($user_id,true);//登录
jinsom_im_tips($user_id,'你的登录用户名是：'.$username.'，'.__('为了你账户的安全，请尽快绑定邮箱/手机号，绑定之后可使用邮箱/手机号进行登录你的帐号！','jinsom'));
$data_arr['code']=1;//注册成功
$data_arr['msg']='注册成功！欢迎加入！';
}
}else{
$data_arr['code']=0;
$data_arr['msg']='网站未开启该注册方式！'; 
}
}

//邮箱注册
if($type=='email'){
if(jinsom_is_reg_type('email')){
$mail=$_POST['mail'];
if(!$mail){
$data_arr['code']=0;
$data_arr['msg']='邮箱不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);	
}

if(jinsom_get_option('jinsom_email_style')!='close'&&jinsom_get_option('jinsom_reg_email_code_on_off')){//开启邮箱验证码的情况
$code=(int)$_POST['code'];
global $wpdb;
$table_name = $wpdb->prefix . 'jin_code';
$check_code= $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE code_main='$mail' and code_number='$code';");    
if(!$check_code){
$data_arr['code']=0;//注册失败
$data_arr['msg']='验证码不正确！';  
header('content-type:application/json');
echo json_encode($data_arr);
exit();  
}
}

if(email_exists($mail)){
$data_arr['code']=0;
$data_arr['msg']='该邮箱已经被注册！';
}else if(!is_email($mail)){
$data_arr['code']=0;
$data_arr['msg']='邮箱格式错误！';
}else{
$rand_name='email_'.rand(100000000,999999999);//先随机生成用户名
$user_id=wp_create_user($rand_name,$password,$mail);
if($user_id){

update_user_meta($user_id, 'avatar_type','default');
update_user_meta($user_id,'reg_type','email');
update_user_meta($user_id,'nickname',$username);

wp_update_user(array('ID' => $user_id));
wp_set_auth_cookie($user_id,true);//登录
$data_arr['code']=1;//注册成功
$data_arr['msg']='注册成功！欢迎加入！';

}else{
$data_arr['code']=0;//注册失败
$data_arr['msg']='注册失败！请联系管理员！错误指令：mail';
}   
}
}else{
$data_arr['code']=0;
$data_arr['msg']='网站未开启该注册方式！'; 
}

}


//手机号注册
if($type=='phone'){
if(jinsom_is_reg_type('phone')){
$phone=$_POST['phone']; 
$code=(int)$_POST['code'];

if(!$phone){
$data_arr['code']=0;
$data_arr['msg']='手机号不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);	
exit();
}

if(jinsom_get_option('jinsom_sms_style')!='close'){//开启了手机短信
global $wpdb;
$table_name = $wpdb->prefix . 'jin_code';
$check_code= $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE code_number='$code' and code_main='$phone'");
}else{
$check_code=1;	
}

if($check_code){//验证码正确

if(jinsom_get_option('jinsom_sms_style')=='close'){
if(!preg_match("/^1[3456789]{1}\d{9}$/",$phone)){
$data_arr['code']=0;
$data_arr['msg']='手机号码格式错误！';
header('content-type:application/json');
echo json_encode($data_arr);	
exit();
}
}

$rand_name='phone_'.rand(100000000,999999999);//先随机生成用户名
$user_id = wp_create_user($rand_name,$password,$phone);
if($user_id){

update_user_meta($user_id, 'avatar_type','default');
update_user_meta($user_id,'reg_type','phone');
update_user_meta($user_id, 'phone',$phone);
update_user_meta($user_id,'nickname',$username);

wp_update_user(array('ID' => $user_id));
wp_set_auth_cookie($user_id,true);//登录


$data_arr['code']=1;//注册成功
$data_arr['msg']='注册成功！欢迎加入！';

}else{
$data_arr['code']=0;//注册失败
$data_arr['msg']='注册失败！请联系管理员！错误指令：phone';
}       


}else{
$data_arr['code']=0;//注册失败
$data_arr['msg']='你输入的验证码有误！';    
}
}else{
$data_arr['code']=0;
$data_arr['msg']='网站未开启该注册方式！';    
}

}





//邀请码注册
if($type=='reg-invite'){
if(jinsom_is_reg_type('invite')){
$code=$_POST['code']; 

if(!$code){
$data_arr['code']=0;
$data_arr['msg']='邀请码不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);	
}

//判断邀请码是否有效
global $wpdb;
$table_name = $wpdb->prefix . 'jin_invite_code';
$check = $wpdb->get_results("SELECT * FROM $table_name WHERE code = '$code' ;");
if($check){
foreach ($check as $checks) {
$status=$checks->status;
}
if($status==0){

if(jinsom_nickname_exists($username)){//如果存在昵称
$nickname=$username.'_'.rand(100,9999);
}else{
$nickname=$username;
}

$user_id=wp_create_user(rand(1000000,9999999),$password,0);
jinsom_update_user_login($user_id,$username);//更新实际用户名
if($user_id){

update_user_meta($user_id,'reg_type','invite');
update_user_meta($user_id,'avatar_type','default');
update_user_meta($user_id,'nickname',$nickname);

wp_update_user(array('ID' => $user_id));
wp_set_auth_cookie($user_id,true);//登录


//更新邀请码的状态
$time=current_time('mysql');
$ip=$_SERVER['REMOTE_ADDR'];
$wpdb->query( "UPDATE $table_name SET status = 1,use_user_id='$user_id',use_time='$time',remark='$ip' WHERE  code = '$code';" );

jinsom_im_tips($user_id,'你的登录用户名是：'.$username.'，'.__('为了你账户的安全，请尽快绑定邮箱/手机号，绑定之后可使用邮箱/手机号进行登录你的帐号！','jinsom'));
$data_arr['code']=1;//注册成功
$data_arr['msg']='注册成功！欢迎加入！';
}else{
$data_arr['code']=0;//注册失败
$data_arr['msg']='注册失败！请联系管理员！错误指令：invite';
}  




}else{
$data_arr['code']=0;
$data_arr['msg']='你输入的邀请码已经被使用！'; 
}

}else{
$data_arr['code']=0;
$data_arr['msg']='你输入的邀请码不存在！'; 
}

}else{
$data_arr['code']=0;
$data_arr['msg']='网站未开启该注册方式！';
}


}




header('content-type:application/json');
echo json_encode($data_arr);