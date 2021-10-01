<?php
//关注
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

$user_id=$current_user->ID;
$author_id=(int)$_POST['author_id'];
$time=current_time('mysql');


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



if($author_id){

if(!get_user_meta($author_id,'nickname',true)){
$data_arr['code']=0;
$data_arr['msg']='用户不存在！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$status_a = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$author_id' AND follow_user_id='$user_id' ");
$status_b = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' AND follow_user_id='$author_id' ");
if($status_b){
foreach ($status_b as $status_bs) {
$follow_status_b=$status_bs->follow_status;
}    
}else{
$follow_status_b=0;
}
if($status_a){//数据库有记录
foreach ($status_a as $status_as) {
$follow_status_a=$status_as->follow_status;
}
if($follow_status_a==0){

if(jinsom_is_blacklist($author_id,$user_id)){
$data_arr['code']=0;
$data_arr['msg']='操作失败！对方已经将你加入黑名单！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if($follow_status_b==0){
$wpdb->query("UPDATE $table_name SET follow_status = '1',follow_time='$time' WHERE user_id='$author_id' AND follow_user_id='$user_id'"); 
$data_arr['code']=2;
$data_arr['msg']='关注成功！';
}else{
$wpdb->query("UPDATE $table_name SET follow_status = '2',follow_time='$time' WHERE user_id='$author_id' AND follow_user_id='$user_id'");
$wpdb->query("UPDATE $table_name SET follow_status = '2' WHERE user_id='$user_id' AND follow_user_id='$author_id' ");
$data_arr['code']=3;
$data_arr['msg']='相互关注成功！';
}


}else if($follow_status_a==1){
$wpdb->query("UPDATE $table_name SET follow_status = '0' WHERE user_id='$author_id' AND follow_user_id='$user_id'");
$data_arr['code']=1;
$data_arr['msg']='已经取消关注！';
}else{//==2
$wpdb->query("UPDATE $table_name SET follow_status = '0' WHERE user_id='$author_id' AND follow_user_id='$user_id'");
$wpdb->query("UPDATE $table_name SET follow_status = '1' WHERE user_id='$user_id' AND follow_user_id='$author_id' ");
$data_arr['code']=1;
$data_arr['msg']='已经取消关注！';
}



}else{//数据库没有记录

if(jinsom_is_blacklist($author_id,$user_id)){
$data_arr['code']=0;
$data_arr['msg']='操作失败！对方已经将你加入黑名单！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if($follow_status_b==0){
$wpdb->query( "INSERT INTO $table_name (user_id,follow_user_id,follow_status,follow_time) VALUES ('$author_id', '$user_id', '1', '$time')" );
$data_arr['code']=2;
$data_arr['msg']='关注成功！';
}else{
$wpdb->query( "INSERT INTO $table_name (user_id,follow_user_id,follow_status,follow_time) VALUES ('$author_id', '$user_id', '2', '$time')" );
$wpdb->query("UPDATE $table_name SET follow_status = '2' WHERE user_id='$user_id' AND follow_user_id='$author_id' ");
$data_arr['code']=3;
$data_arr['msg']='相互关注成功！';
}
//只提醒一次，防止多次刷关注
jinsom_add_tips($author_id,$user_id,0,'follow',"关注了你",'关注了你');

//记录当天关注次数
$follow_times=(int)get_user_meta($user_id,'follow_times',true);
update_user_meta($user_id,'follow_times',($follow_times+1));

//记录今日关注次数
$today_follow=(int)get_option('today_follow');
update_option('today_follow',$today_follow+1);


//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$user_id',0,'follow','$time','$author_id')");

}



}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';
}
header('content-type:application/json');
echo json_encode($data_arr);