<?php
//判断是否已经收藏
require( '../../../../../wp-load.php');
$user_id=$current_user->ID;

//判断是否登录
if(!is_user_logged_in()) { 
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


$url=strip_tags($_POST['url']);

if(jinsom_is_collect($user_id,'img','',$url)){
$data_arr['code']=1;
$data_arr['msg']='已经收藏';
}else{
$data_arr['code']=0;
$data_arr['msg']='还未收藏';
}



header('content-type:application/json');
echo json_encode($data_arr);	