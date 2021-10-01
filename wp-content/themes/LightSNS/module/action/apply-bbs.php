<?php
//论坛申请
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id = $current_user->ID;
$title=strip_tags($_POST['title']);
$reason=strip_tags($_POST['reason']);
$title_number=mb_strlen($title,'utf-8');
$reason_number=mb_strlen($reason,'utf-8');
$bbs_name=jinsom_get_option('jinsom_bbs_name');

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//申请权限
$power = jinsom_get_option('jinsom_apply_bbs_power');	
$apply_bbs_exp = jinsom_get_option('jinsom_apply_bbs_power_exp');
if($power=='vip'){//VIP用户
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='会员用户才有权限申请！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='verify'){//认证用户
$user_verify=get_user_meta($user_id,'verify',true);
if(!$user_verify&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='认证用户才有权限申请！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($power=='honor'){//头衔用户
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor==''&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='拥有头衔的用户才有权限申请！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($power=='exp'){//指定经验
$user_exp=jinsom_get_user_exp($user_id);//当前用户经验
if($user_exp<$apply_bbs_exp&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='需要经验值达到'.$apply_bbs_exp.'，才有权限申请！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}



if(trim($title)==''){ 
$data_arr['code']=0;
$data_arr['msg']='请输入你需要申请的'.$bbs_name.'名称！';
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

if($title_number>20){
$data_arr['code']=0;
$data_arr['msg']=$bbs_name.'名称不能超过20字！';
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


//判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(current_user_can('level_10')){
$data_arr['code']=0;
$data_arr['msg']='你是最牛逼那个人了！还申请毛线啊！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_note';
$number = $wpdb->get_var("SELECT COUNT(*) FROM $table_name where user_id='$user_id' and type ='bbs' and status='0'");
if($number){
$data_arr['code']=0;
$data_arr['msg']='你还有申请没有审核通过了！请耐心等待！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

$time=current_time('mysql');
$status=$wpdb->query("INSERT INTO $table_name (user_id,title,type,note_type,status,content,time) VALUES ('$user_id','$title','bbs','$admin_name','0','$reason','$time')");
if($status){

jinsom_im_tips(1,'提醒：你网站有用户申请'.$bbs_name.'，请登录后台查看！<br>申请用户：'.jinsom_nickname($user_id).'<br>'.$bbs_name.'名称：'.$title);//提醒管理员


$data_arr['code']=1;
$data_arr['msg']='已提交申请！请留意消息通知！';
}else{
$data_arr['code']=0;
$data_arr['msg']='申请失败！';
}


header('content-type:application/json');
echo json_encode($data_arr);