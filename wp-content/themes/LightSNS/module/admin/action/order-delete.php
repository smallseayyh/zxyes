<?php 
//订单删除
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$trade_no=(int)$_POST['trade_no'];


global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';


$wpdb->query("DELETE FROM $table_name WHERE trade_no=$trade_no;");


$data_arr['code']=1;
$data_arr['msg']=__('删除成功！','jinsom');


header('content-type:application/json');
echo json_encode($data_arr);