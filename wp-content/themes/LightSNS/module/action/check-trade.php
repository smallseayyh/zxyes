<?php
//检查订单是否完成
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$credit_name=jinsom_get_option('jinsom_credit_name');
$recharge_number_add = jinsom_get_option('jinsom_recharge_number_add');
$recharge_vip_add = jinsom_get_option('jinsom_recharge_vip_add');

global $wpdb;
$table_name = $wpdb->prefix . 'jin_order';
$WIDout_trade_no=$_POST['WIDout_trade_no'];//商户订单号

//检查商品订单
if(isset($_POST['trade_no'])){
$trade_no=$_POST['trade_no'];
$shop_table_name = $wpdb->prefix . 'jin_shop_order';
$data=$wpdb->get_results("SELECT * FROM $shop_table_name WHERE trade_no = $trade_no and status!= 0 LIMIT 1;");
if($data){
$data_arr['code']=1;
$data_arr['msg']='已经购买成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='购买失败！你还没有付款！';
}
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


//当面付
if(isset($_POST['type'])&&$_POST['type']=='qrcode'){

$i=0;  
while (true){
sleep(1); 
$i++;  

$data=$wpdb->get_results("SELECT * FROM $table_name WHERE out_trade_no = '$WIDout_trade_no' LIMIT 1;");
if($data){
foreach ($data as $datas) {
$subject=$datas->content;//订单名称
$total_fee=$datas->total_fee;//充值的金额
$trade_status=$datas->trade_status;
if($trade_status){//已经支付完成

if($subject=='开通会员'){

if($recharge_vip_add){//如果开通会员的钱跟充值包一样，那直接得到充值包的会员时长
foreach ($recharge_vip_add as $data){
if($data['rmb_price']==$total_fee){
$vip_time=$data['time'];//开通会员的月数
break;  
}
}
}

if($vip_time){

if($vip_time<1){
$vip_time=(int)($vip_time*30).__('天','jinsom');
}else{
$vip_time=$vip_time.__('个月','jinsom');	
}

$data_arr['code']=1;
$data_arr['type']='vip';
$data_arr['vip_time']=$vip_time;
$data_arr['content']=get_user_meta($user_id,'vip_time',true);

if(wp_is_mobile()){
$data_arr['msg']='支付成功！成功开通了'.$vip_time.'VIP会员！';	
}else{
$data_arr['msg']='<p><i class="jinsom-icon jinsom-zhifuchenggong"></i></p><p>支付成功</p><p>VIP会员 + '.$vip_time.'</p>';
}
}

header('content-type:application/json');
echo json_encode($data_arr);
exit(); 

}else{//金币充值

if($recharge_number_add){//如果充值的金额跟充值包一样，那直接得到充值包的金币数量
foreach ($recharge_number_add as $data){
if($data['price']==$total_fee){
$recharge_number=(int)$data['number'];//充值得到的金币
break;	
}
}
}

$data_arr['code']=1;
$data_arr['credit']=(int)get_user_meta($user_id,'credit',true);
$data_arr['type']='credit';
$data_arr['recharge_number']=$recharge_number;

if(wp_is_mobile()){
$data_arr['msg']='支付成功！成功充值了'.$recharge_number.$credit_name;
}else{
$data_arr['msg']='<p><i class="jinsom-icon jinsom-zhifuchenggong"></i></p><p>支付成功</p><p>+ '.$recharge_number.$credit_name.'</p>';
}

header('content-type:application/json');
echo json_encode($data_arr);
exit(); 

}

}
}

}


if($i==29){//30秒后 强制退出轮询
$data_arr['code']=0;
header('content-type:application/json');
echo json_encode($data_arr);
exit();  
} 

}//while

}


//电脑端支付
if(isset($_POST['type'])&&($_POST['type']=='alipay'||$_POST['type']=='epay_alipay'||$_POST['type']=='epay_wechatpay')){

$data=$wpdb->get_results("SELECT * FROM $table_name WHERE out_trade_no = '$WIDout_trade_no' LIMIT 1;");
if($data){
foreach ($data as $datas) {
$subject=$datas->content;//订单名称
$total_fee=$datas->total_fee;//充值的金额
$trade_status=$datas->trade_status;
if($trade_status){//已经支付完成

if($subject=='开通会员'){

if($recharge_vip_add){//如果开通会员的钱跟充值包一样，那直接得到充值包的会员时长
foreach ($recharge_vip_add as $data){
if($data['rmb_price']==$total_fee){
$vip_time=$data['time'];//开通会员的月数
break;  
}
}
}

if($vip_time){
if($vip_time<1){
$vip_time=(int)($vip_time*30).__('天','jinsom');
}else{
$vip_time=$vip_time.__('个月','jinsom');	
}

$data_arr['code']=1;
$data_arr['type']='vip';
$data_arr['vip_time']=$vip_time;
$data_arr['content']=get_user_meta($user_id,'vip_time',true);
$data_arr['msg']='支付成功！成功开通了'.$vip_time.'VIP会员！';
}else{
$data_arr['code']=0;
$data_arr['msg']='开通时长不合法！';
}

// header('content-type:application/json');
// echo json_encode($data_arr);

}else{//金币充值
if($recharge_number_add){//如果充值的金额跟充值包一样，那直接得到充值包的金币数量
foreach ($recharge_number_add as $data){
if($data['price']==$total_fee){
$recharge_number=(int)$data['number'];//充值得到的金币
break;	
}
}
}

$data_arr['code']=1;
$data_arr['type']='credit';
$data_arr['recharge_number']=$recharge_number;
$data_arr['msg']='支付成功！成功充值了'.$recharge_number.$credit_name;


}

}else{
$data_arr['code']=0;
$data_arr['msg']='支付失败！';	
}
}

}else{
$data_arr['code']=0;
$data_arr['msg']='数据异常！';
}
header('content-type:application/json');
echo json_encode($data_arr);
}


//查询微信订单
if(isset($_POST['type'])&&$_POST['type']=='wechatpay'){
// set_time_limit(0);//无限请求超时时间  
$i=0;  
while (true){
sleep(1); 
$i++;  


$data=$wpdb->get_results("SELECT * FROM $table_name WHERE out_trade_no = '$WIDout_trade_no' LIMIT 1;");
if($data){
foreach ($data as $datas) {
$trade_status=$datas->trade_status;
$total_fee=$datas->total_fee;//充值的金额
$subject=$datas->content;//订单名称

if($trade_status){//已经支付完成

if($subject=='开通会员'){

if($recharge_vip_add){//如果开通会员的钱跟充值包一样，那直接得到充值包的会员时长
foreach ($recharge_vip_add as $data){
if($data['rmb_price']==$total_fee){
$vip_time=$data['time'];//开通会员的月数
break;  
}
}
}

if($vip_time){

if($vip_time<1){
$vip_time=(int)($vip_time*30).__('天','jinsom');
}else{
$vip_time=$vip_time.__('个月','jinsom');	
}

$data_arr['code']=1;
$data_arr['type']='vip';
$data_arr['vip_time']=$vip_time;
$data_arr['content']=get_user_meta($user_id,'vip_time',true);
$data_arr['msg']='<p><i class="jinsom-icon jinsom-zhifuchenggong"></i></p><p>支付成功</p><p>VIP会员 + '.$vip_time.'</p>';
}else{
$data_arr['code']=0;
$data_arr['msg']='开通时长不合法！';	
}

header('content-type:application/json');
echo json_encode($data_arr);
exit(); 

}else{//金币充值
if($recharge_number_add){//如果充值的金额跟充值包一样，那直接得到充值包的金币数量
foreach ($recharge_number_add as $data){
if($data['price']==$total_fee){
$recharge_number=$data['number'];//充值得到的金币
break;	
}
}
}
$data_arr['code']=1;
$data_arr['type']='credit';
$data_arr['recharge_number']=$recharge_number;
$data_arr['msg']='<p><i class="jinsom-icon jinsom-zhifuchenggong"></i></p><p>支付成功</p><p>+ '.$recharge_number.$credit_name.'</p>';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 

}

}//status
}

}



if($i==29){//30秒后 强制退出轮询
$data_arr['code']=0;
header('content-type:application/json');
echo json_encode($data_arr);
exit();  
} 


}//while

}

