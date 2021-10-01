<?php 
//修改密码
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
if(jinsom_is_admin($current_user->ID)){
$user_id=$_POST['user_id'];
}else{
$user_id=$current_user->ID;	
}

if(jinsom_is_black($current_user->ID)&&!jinsom_is_admin($current_user->ID)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

if(isset($_POST['pass1'])&&isset($_POST['pass2'])){

$pass1=$_POST['pass1'];
$pass2=$_POST['pass2'];
if($pass1==$pass2){
$password_min = jinsom_get_option('jinsom_reg_password_min');
$password_max = jinsom_get_option('jinsom_reg_password_max');
if(strlen($pass2) >$password_max||strlen($pass2)<$password_min){
$data_arr['code']=0;
$data_arr['msg']='密码长度为'.$password_min.'-'.$password_max.'字符';
}else{
$data = array();
$data['ID'] = $user_id;
$data['user_pass'] = $pass2;
wp_update_user( $data );
$data_arr['code']=1;
$data_arr['msg']='密码已经修改成功！';
}

}else{
$data_arr['code']=0;
$data_arr['msg']='两次输入的密码不一致！';
}
}else{
$data_arr['code']=0;
$data_arr['msg']='非法请求！';		
}
header('content-type:application/json');
echo json_encode($data_arr);