<?php
//增加下载次数
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$post_id=(int)$_POST['post_id'];

//判断是否登录
if (!is_user_logged_in()){ 
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


$download_times=(int)get_post_meta($post_id,'download_times',true);
update_post_meta($post_id,'download_times',$download_times+1);
$data_arr['code']=1;

header('content-type:application/json');
echo json_encode($data_arr);