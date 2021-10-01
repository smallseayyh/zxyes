<?php
//易支付回调
header('Content-type:text/html; Charset=utf-8');
require( '../../../wp-load.php');
require_once("epay.php");
$jinsom_pay_tab=jinsom_get_option('jinsom_pay_tab');
$epay_key=$jinsom_pay_tab['jinsom_epay_key'];
$sdk = new SDK();
$sdk->key($epay_key);

// error_log(print_r($_GET,true),3,'1.txt');

//参数验证
$data=$_GET;
if (!(isset($data['out_trade_no']) && isset($data['sign']))) {
exit();
}


//签名验证
if (!$sdk->signVerify($data)) {
exit();
}

// 交易状态## 交易成功
if($data['trade_status'] == 'TRADE_SUCCESS') {


$recharge_number_add = jinsom_get_option('jinsom_recharge_number_add');
$recharge_vip_add = jinsom_get_option('jinsom_recharge_vip_add');


//$subject=$_POST['subject'];
$out_trade_no = $data['out_trade_no'];
$trade_no = $data['trade_no'];
$total_fee = $data['money'];
$total_fee=floatval($total_fee);
$buyer_email ='';

global $wpdb;
$table_name = $wpdb->prefix . 'jin_order';
$status=$wpdb->get_results("SELECT * FROM $table_name WHERE out_trade_no = '$out_trade_no' and trade_status=0 and total_fee='$total_fee' LIMIT 1;");
$code=get_term_meta(1,'v' ,true );		

if($status&&$code){//如果存在订单，并且状态还没有完成

foreach ($status as $key){
$subject=$key->content;
}

if($subject==__('开通会员','jinsom')){
$vip_time=0;
if($recharge_vip_add){//如果开通会员的钱跟充值包一样，那直接得到充值包的会员时长
foreach ($recharge_vip_add as $data){
if($data['rmb_price']==$total_fee){
$vip_time=$data['time'];//开通会员的月数
break;	
}
}
}

if($vip_time){
foreach ($status as $statu){
$user_id=$statu->user_id;
$pay_type=$statu->type;
if($pay_type=='epay_wechatpay'){
$pay_type='微信开通会员-Epay';
}else{
$pay_type='支付宝开通会员-Epay';
}

jinsom_update_user_vip_day($user_id,$vip_time*31);//更新vip天数
$user_vip_number=(int)get_user_meta($user_id,'vip_number',true);
update_user_meta($user_id,'vip_number',$user_vip_number+$vip_time*jinsom_get_option('jinsom_vip_number_month'));//更新成长值
jinsom_update_credit($user_id,$vip_time,'add','recharge-vip-alipay',$pay_type,1,$out_trade_no);

//给推广的人加上奖励
$who=(int)get_user_meta($user_id,'who',true);
if($who){//如果存在推广人
$referral_link_opne_vip_credit=jinsom_get_option('jinsom_referral_link_opne_vip_credit');
jinsom_update_credit($who,$referral_link_opne_vip_credit,'add','invite-reg-open-vip','推广用户开通会员返利',1,'');
jinsom_update_exp($who,jinsom_get_option('jinsom_referral_link_opne_vip_exp'),'add','推广用户开通会员返利');
//记录推广获利
$referral_credit=(int)get_user_meta($who,'referral_credit',true);
update_user_meta($who,'referral_credit',$referral_credit+$referral_link_opne_vip_credit);
}

}


//记录实时动态
$table_name_now=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name_now (user_id,post_id,type,do_time) VALUES ('$user_id','0','recharge-vip','$time')");
}

}else{//金币充值
if($recharge_number_add){//如果充值的金额跟充值包一样，那直接得到充值包的金币数量
foreach ($recharge_number_add as $data){
if($data['price']==$total_fee){
$recharge_number=(int)$data['number'];//充值得到的金币
break;	
}
}
}

foreach ($status as $statu) {
$user_id=$statu->user_id;
$pay_type=$statu->type;
if($pay_type=='epay_wechatpay'){
$pay_type='微信充值-Epay';
}else{
$pay_type='支付宝充值-Epay';
}
jinsom_update_credit($user_id,$recharge_number,'add','recharge-alipay',$pay_type,1,$out_trade_no);  

//更新用户充值总数
$user_recharge=(int)get_user_meta($user_id,'recharge',true);
$user_recharge_yuan=(int)get_user_meta($user_id,'recharge_yuan',true);
update_user_meta($user_id,'recharge',$user_recharge+$recharge_number);
update_user_meta($user_id,'recharge_yuan',$user_recharge_yuan+$total_fee); 
}

//更新今日金币收益
$today_credit=(int)get_option('today_credit');
update_option('today_credit',$today_credit+$recharge_number);


//给推广的人加上奖励
$who=(int)get_user_meta($user_id,'who',true);
$jinsom_referral_link_recharge_credit=jinsom_get_option('jinsom_referral_link_recharge_credit')*0.01;
if($who&&$jinsom_referral_link_recharge_credit){//如果存在推广人并且开启了推广充值返利
$referral_recharge_number=(int)($jinsom_referral_link_recharge_credit*$recharge_number);
jinsom_update_credit($who,$referral_recharge_number,'add','invite-reg-recharge-credit','推广用户充值返利',1,'');
//记录推广获利
$referral_credit=(int)get_user_meta($who,'referral_credit',true);
update_user_meta($who,'referral_credit',$referral_credit+$referral_recharge_number);

$referral_recharge_yuan=(int)get_user_meta($who,'referral_recharge_yuan',true);
$today_referral_recharge_yuan=(int)get_user_meta($who,'today_referral_recharge_yuan',true);
update_user_meta($who,'referral_recharge_yuan',$referral_recharge_yuan+$total_fee); 
update_user_meta($who,'today_referral_recharge_yuan',$today_referral_recharge_yuan+$total_fee);

}

//记录实时动态
$table_name_now=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name_now (user_id,post_id,type,do_time) VALUES ('$user_id','0','recharge-credit','$time')");


}

//更新订单信息
$wpdb->query("UPDATE $table_name SET trade_no = '$trade_no',trade_status=1,buyer_email='$buyer_email' WHERE out_trade_no = '$out_trade_no';");	
}

echo "success";



}

