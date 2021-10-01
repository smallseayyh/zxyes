<?php
//支付宝
require( '../../../wp-load.php');
header('Content-type:text/html; Charset=utf-8');
/*** 请填写以下配置信息 ***/
$jinsom_pay_tab=jinsom_get_option('jinsom_pay_tab');
$appid=$jinsom_pay_tab['jinsom_alipay_h5_appid'];
$returnUrl=home_url('/Extend/pay/alipay/return.php');     //付款成功后的同步回调地址


if(isset($_GET['trade_no'])){
$notifyUrl = home_url().'/Extend/pay/alipay/notify-goods.php';     //付款成功后的异步回调地址
$outTradeNo=(int)$_GET['trade_no'];
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$datas = $wpdb->get_results("SELECT * FROM $table_name WHERE trade_no = $outTradeNo LIMIT 1;");
foreach ($datas as $data){
$post_id=$data->post_id;
$payAmount=$data->pay_price;//最终支付价格
}
$orderName=get_the_title($post_id);

}else{
$notifyUrl = home_url().'/Extend/pay/alipay/notify.php';     //付款成功后的异步回调地址
$outTradeNo = $_GET['WIDout_trade_no'];     //你自己的商品订单号，不能重复
$payAmount = $_GET['WIDtotal_fee'];          //付款金额，单位:元
$orderName = $_GET['WIDsubject'];    //订单标题
}

$signType = 'RSA2';			//签名算法类型，支持RSA2和RSA，推荐使用RSA2
$rsaPrivateKey=$jinsom_pay_tab['jinsom_alipay_h5_private_key'];		//商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
/*** 配置结束 ***/
$aliPay = new AlipayService();
$aliPay->setAppid($appid);
$aliPay->setReturnUrl($returnUrl);
$aliPay->setNotifyUrl($notifyUrl);
$aliPay->setRsaPrivateKey($rsaPrivateKey);
$aliPay->setTotalFee($payAmount);
$aliPay->setOutTradeNo($outTradeNo);
$aliPay->setOrderName($orderName);
$sHtml = $aliPay->doPay();
echo $sHtml;

class AlipayService
{
protected $appId;
protected $returnUrl;
protected $notifyUrl;
protected $charset;
//私钥值
protected $rsaPrivateKey;
protected $totalFee;
protected $outTradeNo;
protected $orderName;

public function __construct()
{
$this->charset = 'utf8';
}

public function setAppid($appid)
{
$this->appId = $appid;
}

public function setReturnUrl($returnUrl)
{
$this->returnUrl = $returnUrl;
}

public function setNotifyUrl($notifyUrl)
{
$this->notifyUrl = $notifyUrl;
}

public function setRsaPrivateKey($saPrivateKey)
{
$this->rsaPrivateKey = $saPrivateKey;
}

public function setTotalFee($payAmount)
{
$this->totalFee = $payAmount;
}

public function setOutTradeNo($outTradeNo)
{
$this->outTradeNo = $outTradeNo;
}

public function setOrderName($orderName)
{
$this->orderName = $orderName;
}

/**
* 发起订单
* @return array
*/
public function doPay()
{
//请求参数
$requestConfigs = array(
'out_trade_no'=>$this->outTradeNo,
'product_code'=>'FAST_INSTANT_TRADE_PAY',
'total_amount'=>$this->totalFee, //单位 元
'subject'=>$this->orderName,  //订单标题
);
$commonConfigs = array(
//公共参数
'app_id' => $this->appId,
'method' => 'alipay.trade.page.pay',             //接口名称
'format' => 'JSON',
'return_url' => $this->returnUrl,
'charset'=>$this->charset,
'sign_type'=>'RSA2',
'timestamp'=>date('Y-m-d H:i:s'),
'version'=>'1.0',
'notify_url' => $this->notifyUrl,
'biz_content'=>json_encode($requestConfigs),
);
$commonConfigs["sign"] = $this->generateSign($commonConfigs, $commonConfigs['sign_type']);
return $this->buildRequestForm($commonConfigs);
}

/**
* 建立请求，以表单HTML形式构造（默认）
* @param $para_temp 请求参数数组
* @return 提交表单HTML文本
*/
protected function buildRequestForm($para_temp) {

$sHtml = "正在跳转至支付页面...<form id='alipaysubmit' name='alipaysubmit' action='https://openapi.alipay.com/gateway.do?charset=".$this->charset."' method='POST'>";
foreach($para_temp as $key=>$val){
if (false === $this->checkEmpty($val)) {
$val = str_replace("'","&apos;",$val);
$sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
}	
}
//submit按钮控件请不要含有name属性
$sHtml = $sHtml."<input type='submit' value='ok' style='display:none;''></form>";
$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
return $sHtml;
}

public function generateSign($params, $signType = "RSA") {
return $this->sign($this->getSignContent($params), $signType);
}

protected function sign($data, $signType = "RSA") {
$priKey=$this->rsaPrivateKey;
$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
wordwrap($priKey, 64, "\n", true) .
"\n-----END RSA PRIVATE KEY-----";
($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
if ("RSA2" == $signType) {
openssl_sign($data, $sign, $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256); //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
} else {
openssl_sign($data, $sign, $res);
}
$sign = base64_encode($sign);
return $sign;
}

/**
* 校验$value是否非空
*  if not set ,return true;
*    if is null , return true;
**/
protected function checkEmpty($value) {
if (!isset($value))
return true;
if ($value === null)
return true;
if (trim($value) === "")
return true;

return false;
}

public function getSignContent($params) {
ksort($params);
$stringToBeSigned = "";
$i = 0;
foreach ($params as $k => $v) {
if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
// 转换成目标字符集
$v = $this->characet($v, $this->charset);
if ($i == 0) {
$stringToBeSigned .= "$k" . "=" . "$v";
} else {
$stringToBeSigned .= "&" . "$k" . "=" . "$v";
}
$i++;
}
}

unset ($k, $v);
return $stringToBeSigned;
}

/**
* 转换字符集编码
* @param $data
* @param $targetCharset
* @return string
*/
function characet($data, $targetCharset) {
if (!empty($data)) {
$fileType = $this->charset;
if (strcasecmp($fileType, $targetCharset) != 0) {
$data = mb_convert_encoding($data, $targetCharset, $fileType);
//$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
}
}
return $data;
}
}
