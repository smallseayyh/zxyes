<?php
//领取宝箱任务奖励
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$task_id=strip_tags($_POST['task_id']);
$jinsom_task_treasure_add=jinsom_get_option('jinsom_task_treasure_add'); 
$task_times=(int)get_user_meta($user_id,'task_times',true);//用户累计完成任务总数


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

if($jinsom_task_treasure_add){
foreach ($jinsom_task_treasure_add as $data) {
if($task_id==$data['id']){
$task_number=$data['number'];//任务数
$task_name=$data['name'];
if($task_times>=$task_number){
if(!jinsom_is_task($user_id,$task_id)){

$reward_add=$data['reward_add'];
if($reward_add){//如果有奖励
foreach ($reward_add as $reward) {
$reward_type=$reward['reward_type'];
$reward_number=abs($reward['reward_number']);	

if($reward_type=='credit'){
jinsom_update_credit($user_id,$reward_number,'add','task','宝箱任务奖励',1,'');  
}else if($reward_type=='exp'){
jinsom_update_exp($user_id,$reward_number,'add','宝箱任务奖励');
}else if($reward_type=='vip'){
jinsom_update_user_vip_day($user_id,$reward_number);
}else if($reward_type=='vip_number'){
jinsom_update_user_vip_number($user_id,$reward_number);
}else if($reward_type=='charm'){
jinsom_update_user_charm($user_id,$reward_number);
}else if($reward_type=='sign'){
jinsom_update_user_sign_number($user_id,$reward_number);
}else if($reward_type=='nickname'){
jinsom_update_user_nickname_card($user_id,$reward_number);
}else if($reward_type=='honor'){//头衔
jinsom_add_honor($user_id,$reward['reward_honor']);
}


}
}

//写入数据库
global $wpdb;
$table_name = $wpdb->prefix.'jin_task';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,task_id,type,time) VALUES ('$user_id','$task_id','base','$time')" );


//记录实时动态
$table_name_now=$wpdb->prefix.'jin_now';
$wpdb->query( "INSERT INTO $table_name_now (user_id,post_id,type,do_time,remark) VALUES ('$user_id','0','task-treasure','$time','$task_name')");


$data_arr['code']=1;
$data_arr['msg']='奖励领取成功！';


}else{
$data_arr['code']=0;
$data_arr['msg']='你已经领取过该宝箱奖励了！';
}
}else{
$data_arr['code']=0;
$data_arr['msg']='需要累计完成'.$task_number.'个任务才能领取！';
}
}
}
}else{
$data_arr['code']=0;
$data_arr['msg']='请在后台配置宝箱任务的数据！';	
}






header('content-type:application/json');
echo json_encode($data_arr);