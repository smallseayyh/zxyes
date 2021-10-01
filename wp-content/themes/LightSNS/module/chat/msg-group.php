<?php 
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;
$bbs_id=esc_sql($_POST['bbs_id']);

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//自动风控
if(!get_user_meta($user_id,'latest_ip',true)){
update_user_meta($user_id,'user_power',4);
delete_user_meta($user_id,'session_tokens');
$data_arr['code']=0;
$data_arr['msg']='系统检测到你的账户异常，已经将你的账户变为风险账号，请你联系管理员进行解封！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


$group_im=get_term_meta($bbs_id,'bbs_group_im',true);
if(!$group_im){
$data_arr['code']=0;
$data_arr['msg']='该'.jinsom_get_option('jinsom_bbs_name').'还没有开启群聊功能！';
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

//需要绑定手机号才能使用
$bind_phone_use_for=jinsom_get_option('jinsom_bind_phone_use_for');
if($bind_phone_use_for&&in_array("chat",$bind_phone_use_for)&&!current_user_can('level_10')){
if(!get_user_meta($user_id,'phone',true)){
$data_arr['code']=2;
$data_arr['user_id']=$user_id;
$data_arr['msg']='你需要绑定手机号码才能进行操作！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}
}

//需要绑定邮箱才能使用
$bind_email_use_for=jinsom_get_option('jinsom_bind_email_use_for');
if($bind_email_use_for&&in_array("chat",$bind_email_use_for)&&!current_user_can('level_10')){
$user_info=get_userdata($user_id);
if(!$user_info->user_email){
$data_arr['code']=4;
$data_arr['user_id']=$user_id;
$data_arr['msg']='你需要绑定邮箱才能进行操作！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}
}

//发送消息上限拦截
$send_msg_times=(int)get_user_meta($user_id,'send_msg_times',true);
if(is_vip($user_id)){
$jinsom_chat_limit = (int)jinsom_get_option('jinsom_chat_limit_vip');
}else{
$jinsom_chat_limit = (int)jinsom_get_option('jinsom_chat_limit');	
}
if($send_msg_times>=$jinsom_chat_limit&&!jinsom_is_admin($user_id)){
$data_arr['code']=3;
$data_arr['msg']='你当天发送的消息已经超过上限，开通VIP可提升上限';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$power=jinsom_get_option('jinsom_im_power');
if($power=='vip'){//VIP用户
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限会员用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='verify'){//认证用户
$user_verify=get_user_meta($user_id,'verify',true);
if(!$user_verify&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限认证用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='honor'){//头衔用户
$user_honor=get_user_meta($user_id,'user_honor',true);
if(!$user_honor&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限拥有头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='admin'){//管理团队
if(!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限管理团队使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='exp'){//指定经验
$user_exp=jinsom_get_user_exp($user_id);//当前用户经验
$im_exp = jinsom_get_option('jinsom_im_power_exps');
if($user_exp<$im_exp&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限经验值大于'.$im_exp.'的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='honor_arr'){//指定头衔
if(!jinsom_is_admin($user_id)){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$honor_arr=jinsom_get_option('jinsom_im_power_honor_arr');
$publish_power_honor_arr=explode(",",$honor_arr);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($publish_power_honor_arr,$user_honor_arr)){	
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}else if($power=='verify_arr'){//指定认证类型
if(!jinsom_is_admin($user_id)){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$verify_arr=jinsom_get_option('jinsom_im_power_verify_arr');
$publish_power_verify_arr=explode(",",$verify_arr);
if(!in_array($user_verify_type,$publish_power_verify_arr)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定认证类型的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定认证类型的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}


if(isset($_POST['content'])&&isset($_POST['bbs_id'])){
$content=$_POST['content'];
$content_number=mb_strlen($content,'utf-8');

$content_max=jinsom_get_option('jinsom_im_content_max');
if($content_number>$content_max){
$data_arr['code']=0;
$data_arr['msg']='聊天内容不能超过'.$content_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();			
}

$content=esc_sql(htmlentities($content,ENT_QUOTES,'UTF-8'));

//敏感词过滤
if(jinsom_get_option('jinsom_baidu_filter_on_off')&&in_array("chat",jinsom_get_option('jinsom_baidu_filter_use_for'))&&!jinsom_is_admin($user_id)){
$filter=jinsom_baidu_filter($content);
if($filter['conclusionType']==2){
$data_arr['code']=0;
$a=$filter['data'][0]['hits'][0]['words'][0];
if($a==''){$a=$filter['data'][0]['msg'];}
$data_arr['msg']='内容含有敏感词：'.$a;
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}

$time=current_time('mysql');
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message_group';
$status=$wpdb->query( "INSERT INTO $table_name (bbs_id,user_id,type,content,msg_time,status) VALUES ('$bbs_id','$user_id',1,'$content','$time',1)" );
if($status){

//记录今日聊天次数
$today_msg=(int)get_option('today_msg');
update_option('today_msg',$today_msg+1);

//更新用户当天发送次数
update_user_meta($user_id,'send_msg_times',$send_msg_times+1);

$data_arr['code']=1;
$data_arr['msg']='发送成功！';

}else{
$data_arr['code']=0;
$data_arr['msg']='发送失败！';	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='数据异常！';
}
header('content-type:application/json');
echo json_encode($data_arr);