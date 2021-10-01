<?php
//版主申请
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id = $current_user->ID;
$bbs_id=(int)$_POST['bbs_id'];
$type=strip_tags($_POST['type']);
$reason=strip_tags($_POST['reason']);
$reason_number=mb_strlen($reason,'utf-8');

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if(trim($reason)==''){ 
$data_arr['code']=0;
$data_arr['msg']='请输入你申请的原因！';
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

if($reason_number>200){
$data_arr['code']=0;
$data_arr['msg']='申请原因不能超过200字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

if(jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='真不要脸！你已经是管理团队了！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if($type=='a'){
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_name=get_term_meta($bbs_id,'admin_a_name',true);
$admin_a_arr=explode(",",$admin_a);
if(in_array($user_id,$admin_a_arr)){
$data_arr['code']=0;
$data_arr['msg']='你已经是'.$admin_name.'了！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}

if($type=='b'){
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_name=get_term_meta($bbs_id,'admin_b_name',true);
$admin_b_arr=explode(",",$admin_b);
if(in_array($user_id,$admin_b_arr)){
$data_arr['code']=0;
$data_arr['msg']='你已经是'.$admin_name.'了！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}

global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_note';
$number = $wpdb->get_var("SELECT COUNT(*) FROM $table_name where user_id='$user_id' and type ='bbs_admin' and bbs_id ='$bbs_id' and status='0'");
if($number){
$data_arr['code']=0;
$data_arr['msg']='你还有申请没有审核通过了！请耐心等待！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

$time=current_time('mysql');
$status=$wpdb->query("INSERT INTO $table_name (user_id,bbs_id,type,note_type,status,content,time) VALUES ('$user_id','$bbs_id','bbs_admin','$type','0','$reason','$time')");
if($status){

jinsom_im_tips(1,'提醒：你网站有用户申请版主，请登录后台查看！<br>申请用户：'.jinsom_nickname($user_id).'<br>所属'.jinsom_get_option('jinsom_bbs_name').'：'.get_category($bbs_id)->name);//提醒管理员

$data_arr['code']=1;
$data_arr['msg']='已提交申请！请留意消息通知！';
}else{
$data_arr['code']=0;
$data_arr['msg']='申请失败！';
}


header('content-type:application/json');
echo json_encode($data_arr);