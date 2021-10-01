<?php
//提现
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;
$credit=(int)get_user_meta($user_id,'credit',true);
$credit_name=jinsom_get_option('jinsom_credit_name');
$jinsom_cash_ratio=jinsom_get_option('jinsom_cash_ratio');
$jinsom_cash_mini_number=jinsom_get_option('jinsom_cash_mini_number');
$jinsom_cash_on_off = jinsom_get_option('jinsom_cash_on_off');
$cash_power = jinsom_get_option('jinsom_cash_power');
$cash_exp = jinsom_get_option('jinsom_cash_exp');

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

if(!$jinsom_cash_on_off){
$data_arr['code']=0;
$data_arr['msg']='提现功能已经关闭！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//需要绑定手机号才能使用
$bind_phone_use_for=jinsom_get_option('jinsom_bind_phone_use_for');
if($bind_phone_use_for&&in_array("buy",$bind_phone_use_for)&&!current_user_can('level_10')){
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
if($bind_email_use_for&&in_array("buy",$bind_email_use_for)&&!current_user_can('level_10')){
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


if($cash_power=='vip'){
if(!is_vip($user_id)){
$data_arr['code']=0;
$data_arr['msg']='VIP用户才可以使用提现功能！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($cash_power=='verify'){
$verify=get_user_meta($user_id,'verify',true);
if(!$verify){
$data_arr['code']=0;
$data_arr['msg']='认证用户才可以使用提现功能！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}
}else if($cash_power=='exp'){
$exp=(int)get_user_meta($user_id,'exp',true);
if($exp<$cash_exp){
$data_arr['code']=0;
$data_arr['msg']='经验值达到'.$cash_exp.'的用户才可以使用提现功能！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

}

if (get_magic_quotes_gpc()){
$_POST=jinsom_sqlfilter_array($_POST);
}

$number=$_POST['number'];
$type=$_POST['type'];
$name=$_POST['name'];
$alipay=$_POST['alipay'];
$wechat=$_POST['wechat'];

if(empty($number)){
$data_arr['code']=0;
$data_arr['msg']='提现数量不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(empty($name)){
$data_arr['code']=0;
$data_arr['msg']='收款姓名不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if($type=='alipay'){
if(empty($alipay)){
$data_arr['code']=0;
$data_arr['msg']='支付宝收款帐号不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}	
}else{
if(empty($wechat)){
$data_arr['code']=0;
$data_arr['msg']='微信收款帐号不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}

//手续费百分比
$jinsom_cash_poundage=jinsom_get_option('jinsom_cash_poundage');
if($jinsom_cash_poundage){
$poundage=(int)($number*$jinsom_cash_poundage)/100;
$last_number=$poundage+$number;
}else{
$poundage=0;
$last_number=$number;
}

if($credit<$last_number){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！(含手续费'.$poundage.$credit_name.')';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if($number<$jinsom_cash_mini_number){
$data_arr['code']=0;
$data_arr['msg']='提现的'.$credit_name.'不能少于'.$jinsom_cash_mini_number.'！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


//更新最后一次提现的信息
update_user_meta($user_id,'cash_name',$name);
if($type=='alipay'){
$account=$alipay;
update_user_meta($user_id,'alipay',$alipay);
}else{
$account=$wechat;
update_user_meta($user_id,'cash_wechat_phone',$wechat);
}


jinsom_update_credit($user_id,$last_number,'cut','cash','申请提现(含手续费'.$poundage.$credit_name.')',1,'');  

$time=current_time('mysql');
$rmb=intval($number/$jinsom_cash_ratio);

global $wpdb;
$table_name = $wpdb->prefix.'jin_cash';
$wpdb->query("INSERT INTO $table_name (user_id,credit,rmb,status,type,name,phone_email,cash_time) VALUES ('$user_id','$number','$rmb','0','$type','$name','$account','$time')");

jinsom_im_tips(1,__('提醒：你网站有用户申请提现，请登录后台查看！<br>申请用户：'.jinsom_nickname($user_id).'<br>提现金额：'.$rmb.'元('.$number.$credit_name.')，手续费：'.$poundage.$credit_name.'','jinsom'));//提醒管理员有新的提现

//记录实时动态
$table_name=$wpdb->prefix.'jin_now';
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$user_id','0','cash','$time','')");



$data_arr['code']=1;
$data_arr['msg']='提交申请成功！';
header('content-type:application/json');
echo json_encode($data_arr);