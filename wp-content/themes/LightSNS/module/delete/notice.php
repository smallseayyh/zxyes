<?php
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
//清空所有的提醒消息
$user_id=$current_user->ID;

if (is_user_logged_in()) {
global $wpdb;
$table_name = $wpdb->prefix . 'jin_notice';
$status=$wpdb->query( " DELETE FROM $table_name WHERE my_id='$user_id'; " );
if($status){
$data_arr['code']=1;
$data_arr['msg']='清空成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='清空失败！你没有任何要清空的消息！';
}

}else{
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
}
header('content-type:application/json');
echo json_encode($data_arr);