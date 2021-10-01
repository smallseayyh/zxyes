<?php
//迅虎支付
require( '../../../wp-load.php');
require_once 'wechatpay-xunhu-api.php';
$jinsom_pay_tab=jinsom_get_option('jinsom_pay_tab');
$appid=$jinsom_pay_tab['jinsom_xunhu_appid'];
$appsecret=$jinsom_pay_tab['jinsom_xunhu_appsecret'];
$api=$jinsom_pay_tab['jinsom_xunhu_api'];
if(!$api){$api='https://api.xunhupay.com/payment/do.html';}
$my_plugin_id ='LightSNS';


if(isset($_POST['trade_no'])){
$return_url=home_url().'/Extend/pay/xunhupay/return.php';
$notifyUrl=home_url().'/Extend/pay/xunhupay/notify-goods.php';     //付款成功后的异步回调地址
$outTradeNo=(int)$_POST['trade_no'];
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$datas = $wpdb->get_results("SELECT * FROM $table_name WHERE trade_no = $outTradeNo LIMIT 1;");
foreach ($datas as $data){
$post_id=$data->post_id;
$payAmount=$data->pay_price;//最终支付价格
}
$orderName=get_the_title($post_id);

}else{
$notifyUrl = home_url().'/Extend/pay/xunhupay/notify.php';     //付款成功后的异步回调地址
$outTradeNo = $_POST['WIDout_trade_no'];     //你自己的商品订单号，不能重复
$payAmount = $_POST['WIDtotal_fee'];          //付款金额，单位:元
$orderName = $_POST['WIDsubject'];    //订单标题
}


$data=array(
'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
'plugins'   => $my_plugin_id,//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
'appid'     => $appid, //必须的，APPID
'trade_order_id'=> $outTradeNo, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
'payment'   => 'wechat',//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
'total_fee' => $payAmount,//人民币，单位精确到分(测试账户只支持0.1元内付款)
'title'     => $orderName, //必须的，订单标题，长度32或以内
'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
'notify_url'=> $notifyUrl, //必须的，支付成功异步回调接口
'return_url'=> $return_url,//必须的，支付成功后的跳转地址
'callback_url'=>home_url(),//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
'modal'=>null, //可空，支付模式 ，可选值( full:返回完整的支付网页; qrcode:返回二维码; 空值:返回支付跳转链接)
'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
);

if(wp_is_mobile()){
$data['type']='WAP';
$data['wap_url']=home_url();
$data['wap_name']=home_url();
}

$hashkey =$appsecret;
$data['hash']     = XH_Payment_Api::generate_xh_hash($data,$hashkey);
/**
* 个人支付宝/微信即时到账，支付网关：https://pay2.xunhupay.com/v2
* 微信支付宝代收款，需提现，支付网关：https://pay.wordpressopen.com
*/
$url              = $api;

try {
$response     = XH_Payment_Api::http_post($url, json_encode($data));
/**
* 支付回调数据
* @var array(
*      order_id,//支付系统订单ID
*      url//支付跳转地址
*  )
*/
$result       = $response?json_decode($response,true):null;
if(!$result){
throw new Exception('Internal server error',500);
}

$hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
if(!isset( $result['hash'])|| $hash!=$result['hash']){
throw new Exception('签名为空！',40029);
}

if($result['errcode']!=0){
throw new Exception($result['errmsg'],$result['errcode']);
}

if(!wp_is_mobile()){
// echo '<div class="jinsom-wechatpay-code-content">';
// echo "<img src='{$result['url_qrcode']}' style='width:200px;height:200px;'>";
// echo '<p><i class="jinsom-icon jinsom-weixinzhifu"></i> 微信扫码支付</p></div>';
echo $result['url_qrcode'];
}else{
echo $result['url'];
}
// $pay_url =$result['url'];
// header("Location: $pay_url");
// exit;
} catch (Exception $e) {
echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";
//TODO:处理支付调用异常的情况
}
?>
