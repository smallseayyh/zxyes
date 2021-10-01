<?php
require( '../../../wp-load.php');
require_once("epay.php");
$jinsom_pay_tab=jinsom_get_option('jinsom_pay_tab');
$epay_pid=$jinsom_pay_tab['jinsom_epay_pid'];
$epay_key=$jinsom_pay_tab['jinsom_epay_key'];
$api=$jinsom_pay_tab['jinsom_epay_api'];
$sdk = new SDK();
$sdk->key($epay_key);


if(isset($_GET['trade_no'])){
$notifyUrl =home_url().'/Extend/pay/epay/notify-goods.php';    //付款成功后的异步回调地址
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
$notifyUrl = home_url().'/Extend/pay/epay/notify.php';     //付款成功后的异步回调地址
$outTradeNo = $_GET['WIDout_trade_no'];     //你自己的商品订单号，不能重复
$payAmount = $_GET['WIDtotal_fee'];          //付款金额，单位:元
$orderName = $_GET['WIDsubject'];    //订单标题
}

$pay_type=$_GET['pay_type'];//支付类型
if($pay_type=='epay_wechatpay'){$pay_type='wxpay';}else{$pay_type='alipay';}


// 发起订单
try {
echo $sdk->pid($epay_pid)
->url($api)
->outTradeNo($outTradeNo)
->type($pay_type)
->notifyUrl($notifyUrl)
->returnUrl(home_url('/Extend/pay/epay/return.php'))
->money($payAmount)
->name($orderName)
->sitename(jinsom_get_option('jinsom_site_name'))
->submit()
->getHtmlForm();
} catch (EpayException $e) {
echo $e->getMessage();
}
