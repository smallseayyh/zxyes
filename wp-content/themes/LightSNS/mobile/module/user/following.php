<?php 
//获取我关注的用户数据
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$follow_data = $wpdb->get_results("SELECT user_id FROM $table_name WHERE  follow_user_id=$user_id and follow_status !=0 limit 50;");
if($follow_data){
$data_arr['code']=1;
$data_arr['data']=array();
foreach ($follow_data as $data){
$follow_arr=array();
$follow_user_id=$data->user_id;
$follow_arr['name']=jinsom_nickname($follow_user_id);
$follow_arr['nickname']=get_user_meta($follow_user_id,'nickname',true);
$follow_arr['avatar']=jinsom_avatar($follow_user_id,'40',avatar_type($follow_user_id));
$follow_arr['verify']=jinsom_verify($follow_user_id);
$follow_arr['vip']=jinsom_vip($follow_user_id);
array_push($data_arr['data'],$follow_arr);
}
}else{
$data_arr['code']=0;
}

header('content-type:application/json');
echo json_encode($data_arr);