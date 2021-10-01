<?php
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;

if (!is_user_logged_in()){
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if(wp_is_mobile()){
jinsom_set_notice();//提醒消息
}


$table_name = $wpdb->prefix . 'jin_message';
$status=$wpdb->query("UPDATE $table_name SET status = 1 WHERE user_id='$user_id';");//聊天消息
if($status){
$data_arr['code']=1;
$data_arr['msg']='消息已清除！';	
}else{
$data_arr['code']=1;
$data_arr['msg']='暂无未读消息！';	
}


header('content-type:application/json');
echo json_encode($data_arr);