<?php
//使用金币开通会员
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

$user_id = $current_user->ID;
$credit=get_user_meta($user_id,'credit',true);
$total_fee=abs($_POST['WIDtotal_fee']);//订单金额
$recharge_vip_add = jinsom_get_option('jinsom_recharge_vip_add');


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

if(!$total_fee){
$data_arr['code']=0;
$data_arr['msg']='非法金额数据！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

if($credit<$total_fee){
$data_arr['code']=0;
$data_arr['msg']='你的'.jinsom_get_option('jinsom_credit_name').'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

$vip_time=0;
if($recharge_vip_add){//如果开通会员的钱跟充值包一样，那直接得到充值包的会员时长
foreach ($recharge_vip_add as $data){
if($data['credit_price']==$total_fee){
$vip_time=$data['time'];//开通会员的月数
break;  
}
}
}

if($vip_time){
jinsom_update_credit($user_id,$total_fee,'cut','recharge-vip','开通了'.$vip_time.'个月会员',1,''); 
jinsom_update_user_vip_day($user_id,$vip_time*31);//更新vip天数
$user_vip_number=(int)get_user_meta($user_id,'vip_number',true);
update_user_meta($user_id,'vip_number',$user_vip_number+$vip_time*jinsom_get_option('jinsom_vip_number_month'));//更新成长值

$data_arr['code']=1;
$data_arr['content']=get_user_meta($user_id,'vip_time',true);

if($vip_time>=1){
$data_arr['msg']='支付成功！开通了'.$vip_time.'个月会员！';
}else{
$data_arr['msg']='支付成功！开通了'.(int)($vip_time*30).'天会员！';	
}

//给推广的人加上奖励
if(jinsom_get_option('jinsom_referral_link_opne_vip_credit_on_off')){
$who=(int)get_user_meta($user_id,'who',true);
if($who){//如果存在推广人
jinsom_update_credit($who,jinsom_get_option('jinsom_referral_link_opne_vip_credit'),'add','invite-reg-open-vip','推广用户开通会员返利',1,'');
jinsom_update_exp($who,jinsom_get_option('jinsom_referral_link_opne_vip_exp'),'add','推广用户开通会员返利');
}
}


//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$user_id','0','recharge-vip','$time','金币支付')");

}else{
$data_arr['code']=0;
$data_arr['msg']='开通时长不合法！';	
}


header('content-type:application/json');
echo json_encode($data_arr);
