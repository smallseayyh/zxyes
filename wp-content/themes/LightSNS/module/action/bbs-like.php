<?php
//关注论坛
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;
$bbs_id=(int)$_POST['bbs_id'];

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


//帖子权限===//权限判断
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);

if (is_user_logged_in()) {
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}

$bbs_visit_power=get_term_meta($bbs_id,'bbs_visit_power',true);
if($bbs_visit_power==1){
if(!is_vip($user_id)&&!$is_bbs_admin){
$power=0;
}else{
$power=1;
}
}else if($bbs_visit_power==2){
$user_verify=get_user_meta($user_id, 'verify', true );
if(!$user_verify&&!$is_bbs_admin){
$power=0;
}else{
$power=1;
}
}else if($bbs_visit_power==3){
if(!$is_bbs_admin){
$power=0;
}else{
$power=1;
}
}else if($bbs_visit_power==4){
$user_honor=get_user_meta($user_id,'user_honor',true);
if(!$user_honor&&!$is_bbs_admin){
$power=0;
}else{
$power=1;
}
}else if($bbs_visit_power==5){
//先判断用户是否已经输入密码
if(!jinsom_is_bbs_visit_pass($bbs_id,$user_id)&&!$is_bbs_admin){
$power=0;
}else{
$power=1;
}
}else if($bbs_visit_power==6){//满足经验的用户
$bbs_visit_exp=(int)get_term_meta($bbs_id,'bbs_visit_exp',true);
$current_user_lv=jinsom_get_user_exp($user_id);//当前用户经验
if($current_user_lv<$bbs_visit_exp&&!$is_bbs_admin){
$power=0;
}else{
$power=1;
}
}else if($bbs_visit_power==7){//指定用户
$bbs_visit_user=get_term_meta($bbs_id,'bbs_visit_user',true);
$bbs_visit_user_arr=explode(",",$bbs_visit_user);
if(!in_array($user_id,$bbs_visit_user_arr)&&!$is_bbs_admin){
$power=0;
}else{
$power=1;
}
}else{//有权限的情况
$power=1;
} //有权限的情况

if(!$power){
$data_arr['code']=0;
$data_arr['msg']='你没有权限关注！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}



if(isset($_POST['bbs_id'])){
if($bbs_id){
$time=current_time('mysql');
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message_group';
if(jinsom_is_bbs_like($user_id,$bbs_id)){
jinsom_delete_bbs_like($user_id,$bbs_id);
$wpdb->query( "INSERT INTO $table_name (bbs_id,user_id,type,content,msg_time,status) VALUES ('$bbs_id','$user_id',3,'退出了群聊','$time',1)" );	
$data_arr['code']=2;
$data_arr['msg']='已经取消关注！';
}else{
jinsom_add_bbs_like($user_id,$bbs_id);	
$wpdb->query( "DELETE FROM $table_name WHERE bbs_id='$bbs_id' and user_id='$user_id' and type IN(2,3);" );
$wpdb->query( "INSERT INTO $table_name (bbs_id,user_id,type,content,msg_time,status) VALUES ('$bbs_id','$user_id',2,'加入了群聊','$time',1)" );
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