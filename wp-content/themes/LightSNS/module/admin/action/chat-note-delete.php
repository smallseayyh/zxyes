<?php 
require( '../../../../../../wp-load.php' );
//聊天记录删除


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
$id=$_POST['id'];
global $wpdb;

if($type==1){
$table_name = $wpdb->prefix.'jin_message';
}else{
$table_name = $wpdb->prefix.'jin_message_group';
}


$check=$wpdb->query( "DELETE FROM $table_name where ID='$id';" );

if($check){
$data_arr['code']=1;
$data_arr['msg']='已经删除！';
}else{
$data_arr['code']=0;
$data_arr['msg']='删除失败！';	
}
header('content-type:application/json');
echo json_encode($data_arr);
