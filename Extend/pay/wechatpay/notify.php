<?php
/**
* 原生支付（扫码支付）及公众号支付的异步回调通知
* 说明：需要在native.php或者jsapi.php中的填写回调地址。例如：http://www.xxx.com/wx/notify.php
* 付款成功后，微信服务器会将付款结果通知到该页面
*/
require( '../../../wp-load.php');
header('Content-type:text/html; Charset=utf-8');
$recharge_number_add = jinsom_get_option('jinsom_recharge_number_add');
$recharge_vip_add = jinsom_get_option('jinsom_recharge_vip_add');

$jinsom_pay_tab=jinsom_get_option('jinsom_pay_tab');
$mchid=$jinsom_pay_tab['jinsom_wechatpay_mchid'];          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
$appid=$jinsom_pay_tab['jinsom_wechatpay_appid'];  //微信支付申请对应的公众号的APPID
$apiKey=$jinsom_pay_tab['jinsom_wechatpay_apiKey'];   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥



$wxPay = new WxpayService($mchid,$appid,$apiKey);
$result = $wxPay->notify();
if($result){
//完成你的逻辑
//例如连接数据库，获取付款金额$result['cash_fee']，获取订单号$result['out_trade_no']，修改数据库中的订单状态等;

$out_trade_no = $result['out_trade_no'];
$trade_no = $result['transaction_id'];
$total_fee = $result['cash_fee'];
$total_fee =$total_fee/100;
$total_fee=floatval($total_fee);

// update_option('test_out_trade_no',$out_trade_no);//测试字段
// update_option('test_trade_no',$trade_no);//测试字段
// update_option('test_total_fee',$total_fee);//测试字段

global $wpdb;
$table_name = $wpdb->prefix . 'jin_order';
$status=$wpdb->get_results("SELECT user_id,content FROM $table_name WHERE out_trade_no = '$out_trade_no' and trade_status=0 and total_fee='$total_fee' LIMIT 1;");
$code=get_term_meta( 1,'v',true ); 


if($status&&$code){//如果存在订单，并且状态还没有完成
foreach ($status as $statu) {   
$user_id=$statu->user_id;
$subject=$statu->content;    


if($subject==__('开通会员','jinsom')){
if($recharge_vip_add){//如果开通会员的钱跟充值包一样，那直接得到充值包的会员时长
foreach ($recharge_vip_add as $data){
if($data['rmb_price']==$total_fee){
$vip_time=$data['time'];//开通会员的月数
break;  
}
}
}

if($vip_time){
jinsom_update_user_vip_day($user_id,$vip_time*31);//更新vip天数
$user_vip_number=(int)get_user_meta($user_id,'vip_number',true);
update_user_meta($user_id,'vip_number',$user_vip_number+$vip_time*jinsom_get_option('jinsom_vip_number_month'));//更新成长值
jinsom_update_credit($user_id,$vip_time,'add','recharge-vip-wechatpay','微信开通会员',1,$out_trade_no); 

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

}else{//金币充值


if($recharge_number_add){//如果充值的金额跟充值包一样，那直接得到充值包的金币数量
foreach ($recharge_number_add as $data){
if($data['price']==$total_fee){
$recharge_number=(int)$data['number'];//充值得到的金币
break;  
}
}
}

jinsom_update_credit($user_id,$recharge_number,'add','recharge-wechatpay','微信充值',1,$out_trade_no); 

//更新用户充值总数
$user_recharge=(int)get_user_meta($user_id,'recharge',true);
$user_recharge_yuan=(int)get_user_meta($user_id,'recharge_yuan',true);
update_user_meta($user_id,'recharge',$user_recharge+$recharge_number);
update_user_meta($user_id,'recharge_yuan',$user_recharge_yuan+$total_fee);


//更新今日充值金额
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
$wpdb->query("UPDATE $table_name SET trade_no = '$trade_no',trade_status=1 WHERE out_trade_no = '$out_trade_no';");  
}//foreach
}




}else{
echo 'pay error';
}
class WxpayService
{
protected $mchid;
protected $appid;
protected $apiKey;
public function __construct($mchid, $appid, $key)
{
$this->mchid = $mchid;
$this->appid = $appid;
$this->apiKey = $key;
}

public function notify()
{
$config = array(
'mch_id' => $this->mchid,
'appid' => $this->appid,
'key' => $this->apiKey,
);
$postStr = file_get_contents('php://input');
$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
if ($postObj === false) {
die('parse xml error');
}
if ($postObj->return_code != 'SUCCESS') {
die($postObj->return_msg);
}
if ($postObj->result_code != 'SUCCESS') {
die($postObj->err_code);
}
$arr = (array)$postObj;
unset($arr['sign']);
if (self::getSign($arr, $config['key']) == $postObj->sign) {
echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
return $arr;
}
}

/**
* 获取签名
*/
public static function getSign($params, $key)
{
ksort($params, SORT_STRING);
$unSignParaString = self::formatQueryParaMap($params, false);
$signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
return $signStr;
}
protected static function formatQueryParaMap($paraMap, $urlEncode = false)
{
$buff = "";
ksort($paraMap);
foreach ($paraMap as $k => $v) {
if (null != $v && "null" != $v) {
if ($urlEncode) {
$v = urlencode($v);
}
$buff .= $k . "=" . $v . "&";
}
}
$reqPar = '';
if (strlen($buff) > 0) {
$reqPar = substr($buff, 0, strlen($buff) - 1);
}
return $reqPar;
}
}
