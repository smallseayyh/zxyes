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

$trade_no = $result['out_trade_no'];
$out_trade_no = $result['transaction_id'];
$total_fee = $result['cash_fee'];
$total_fee =$total_fee/100;
$total_fee=floatval($total_fee);

global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$datas=$wpdb->get_results("SELECT * FROM $table_name WHERE trade_no = $trade_no and pay_price='$total_fee' LIMIT 1;");	

if($datas){//如果存在订单，并且状态还没有完成

foreach ($datas as $data){
$user_id=$data->user_id;
$post_id=$data->post_id;
$goods_type=$data->goods_type;//商品类型
$virtual_type=$data->virtual_type;//虚拟物品类型
$number=(int)$data->number;//订单数量
}

$goods_data=get_post_meta($post_id,'goods_data',true);//商品数据
$virtual_number=(int)$goods_data['virtual_number'];
$virtual_info='';
if($number>1){$virtual_number=$virtual_number*$number;}//如果购买的数量大于1，则自动相乘

if($goods_type=='a'){//本站虚拟
$status=2;//订单完成
$virtual_info=$virtual_number;//虚拟物品的信息：数量、头衔名称、卡密、下载信息
if($virtual_type=='honor'){
jinsom_add_honor($user_id,$goods_data['virtual_honor']);
$virtual_info=$goods_data['virtual_honor'];
}else if($virtual_type=='exp'){
jinsom_update_exp($user_id,$virtual_number,'add','购买商品');
}else if($virtual_type=='charm'){
jinsom_update_user_charm($user_id,$virtual_number);
}else if($virtual_type=='vip_number'){
jinsom_update_user_vip_number($user_id,$virtual_number);
}else if($virtual_type=='sign'){
jinsom_update_user_sign_number($user_id,$virtual_number);
}else if($virtual_type=='nickname'){//改名卡
jinsom_update_user_nickname_card($user_id,$virtual_number);
}else if($virtual_type=='download'){
$virtual_info=$goods_data['virtual_download_info'];
if(!$virtual_info){$virtual_info='购买的商品没有提取信息，请联系管理员！';}
jinsom_im_tips($user_id,$virtual_info);//IM发送
}else if($virtual_type=='faka'){
$virtual_faka=$goods_data['virtual_faka'];
$virtual_faka_arr=explode(PHP_EOL,$virtual_faka);
if($virtual_faka_arr){
$virtual_info='发卡信息：'.$virtual_faka_arr[0];
unset($virtual_faka_arr[0]);//删除卡密
$goods_data['virtual_faka']=implode(PHP_EOL,$virtual_faka_arr);//取出卡密后的发卡信息
}else{
$virtual_info='卡密已经没有了！请联系管理员！';	
}

//遍历取出来========
jinsom_im_tips($user_id,$virtual_info);//IM发送

}




}else{//其他虚拟、实物
$status=1;//待发货

//更新库存
$goods_count=(int)$goods_data['jinsom_shop_goods_count'];
$goods_count=$goods_count-1;
$goods_data['jinsom_shop_goods_count']=$goods_count;

}

//商品交易数量
$buy_number=(int)$goods_data['buy_number'];
$goods_data['buy_number']=$buy_number+$number;


update_post_meta($post_id,'goods_data',$goods_data);//更新商品信息


$time=current_time('mysql');

//更新订单信息
$wpdb->query("UPDATE $table_name SET out_trade_no='$out_trade_no',status=$status,virtual_info='$virtual_info',pay_type='wechatpay',time='$time' WHERE trade_no = '$trade_no';");

//推广返利
$who=(int)get_user_meta($user_id,'who',true);
$jinsom_shop_referral_credit=(int)$goods_data['jinsom_shop_referral_credit'];//返利金币
if($jinsom_shop_referral_credit){//设置了返利金币
if(isset($_COOKIE["invite"])){//通过推广链接访问
jinsom_update_credit($_COOKIE["invite"],$jinsom_shop_referral_credit,'add','shop-goods-referral','推广用户商品返利',1,'');
}else if($who){//存在上级推广人
jinsom_update_credit($who,$jinsom_shop_referral_credit,'add','shop-goods-referral','推广用户商品返利',1,'');	
}
}


jinsom_im_tips(1,'提醒：你网站有新的商城订单，请登录后台查看！<br>购买用户：'.jinsom_nickname($user_id).'<br>商品名称：'.get_the_title($post_id));//提醒管理员有新的订单

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$post_id','buy_goods','$time')");

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
