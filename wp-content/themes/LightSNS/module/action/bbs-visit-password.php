<?php
require( '../../../../../wp-load.php' );
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


//访问密码论坛
if(isset($_POST['visit'])){
$user_id=$current_user->ID;
$bbs_id=$_POST['bbs_id'];
$pass=$_POST['pass'];
$bbs_visit_pass=get_term_meta($bbs_id,'bbs_visit_pass',true);
if($pass==$bbs_visit_pass){
//密码正确
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_visit_pass'; 
$wpdb->query( "INSERT INTO $table_name (bbs_id,user_id) VALUES ('$bbs_id','$user_id')" );

$data_arr['code']=1;
$data_arr['msg']='密码正确！';
}else{
$data_arr['code']=0;
$data_arr['msg']='密码错误！';
}

}

//删除访问密码
if(isset($_POST['delete'])){
if(jinsom_is_admin($user_id)){
$bbs_id=$_POST['bbs_id'];
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_visit_pass'; 
if($wpdb->query(" DELETE FROM $table_name WHERE bbs_id='$bbs_id';")){
$data_arr['code']=1;
$data_arr['msg']='删除成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='删除失败！';
}
}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';	
}
}


header('content-type:application/json');
echo json_encode($data_arr);