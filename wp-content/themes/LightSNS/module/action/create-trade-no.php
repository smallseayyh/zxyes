<?php
//创建订单
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
global $wpdb;
$table_name = $wpdb->prefix . 'jin_order';

if(is_user_logged_in()){
if(isset($_POST['type'])){
$WIDtotal_fee=$_POST['WIDtotal_fee'];//订单金额
$WIDsubject=$_POST['WIDsubject'];//订单名称
$WIDout_trade_no=$_POST['WIDout_trade_no'];//商户订单号
$trade_status=0;//0未完成交易
$type=$_POST['type'];//支付宝或者微信
$trade_time=current_time('mysql');//订单创建时间
$status=$wpdb->get_var("SELECT count(*) FROM $table_name WHERE out_trade_no = '$WIDout_trade_no' LIMIT 1;"); 
if(!$status){//不存在该订单才创建订单
$wpdb->query( "INSERT INTO $table_name (out_trade_no,trade_status,total_fee,user_id,type,content,trade_time) VALUES ('$WIDout_trade_no','$trade_status','$WIDtotal_fee','$user_id','$type','$WIDsubject','$trade_time')" );
}
}
}
