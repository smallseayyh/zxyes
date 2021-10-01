<?php 
require( '../../../../../wp-load.php' );
//验证密码动态
$user_id = $current_user->ID;

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(isset($_POST['post_id'])&&isset($_POST['password'])){
$post_id = $_POST['post_id'];
$post_type=get_post_meta($post_id,'post_type',true);
$password=$_POST['password'];
$post_password=get_post_meta($post_id,'post_password',true);
if($password==$post_password){
$status=jinsom_add_password_content($user_id,$post_id);
if($status){
$data_arr['code']=1;
// $data_arr['post_url']=jinsom_mobile_post_url($post_id);
$data_arr['post_type']=$post_type;
$data_arr['hide_content']=convert_smilies(wpautop(jinsom_autolink(get_post_meta($post_id,'pay_cnt',true))));//隐藏内容
$data_arr['msg']='密码正确！';	

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$post_id','password-post','$time')");

}else{
$data_arr['code']=0;
$data_arr['msg']='无法查看！[1000002]';	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='密码错误！';
}

}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';
}
header('content-type:application/json');
echo json_encode($data_arr);