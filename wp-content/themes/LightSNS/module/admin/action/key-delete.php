<?php 
require( '../../../../../../wp-load.php' );
//卡密删除


//判断是否登录
if (!is_user_logged_in()){ 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否管理员
if (!current_user_can('level_10')){ 
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


$type=$_POST['type'];
$status=$_POST['status'];
global $wpdb;
$table_name = $wpdb->prefix.'jin_key';
$date=date('Y-m-d');

if($type=='all'){
if($status==3){
$check=$wpdb->query( "DELETE FROM $table_name where expiry<'$date';" );//删除过期的
}else{
$check=$wpdb->query( "DELETE FROM $table_name where status='$status';" );
}
}else{
if($status==3){
$check=$wpdb->query( "DELETE FROM $table_name where type='$type' and expiry<'$date';" );//删除过期的
}else{
$check=$wpdb->query( "DELETE FROM $table_name where type='$type' and status='$status';" );
}
}


if($check){
$data_arr['code']=1;
$data_arr['msg']='已经删除！';
}else{
$data_arr['code']=0;
$data_arr['msg']='删除失败！';	
}
header('content-type:application/json');
echo json_encode($data_arr);
