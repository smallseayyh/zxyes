<?php
//关注话题
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;

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



if(isset($_POST['topic_id'])){
$topic_id=$_POST['topic_id'];
if($topic_id){
$time=current_time('mysql');
if(jinsom_is_topic_like($user_id,$topic_id)){
jinsom_delete_topic_like($user_id,$topic_id);
$data_arr['code']=2;
$data_arr['msg']='已经取消关注！';
}else{
jinsom_add_topic_like($user_id,$topic_id);	
$data_arr['code']=1;
$data_arr['msg']='关注成功！';
}

}else{
$data_arr['code']=0;
$data_arr['msg']='关注失败！';	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';
}

header('content-type:application/json');
echo json_encode($data_arr);