<?php 
//发货
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$content=$_POST['content'];
$trade_no=(int)$_POST['trade_no'];

if($content==''){
$data_arr['code']=0;
$data_arr['msg']=__('发货信息不能为空！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';

//更新订单信息
$wpdb->query("UPDATE $table_name SET status=2,address_order='$content'  WHERE trade_no = '$trade_no';");

$order_data=$wpdb->get_results("SELECT * FROM $table_name WHERE trade_no = $trade_no limit 1;");
foreach ($order_data as $data){
$buy_user_id=$data->user_id;//购买的用户
}

//通过IM发送
jinsom_im_tips($buy_user_id,'发货信息：'.$content);//IM发送物流信息
jinsom_add_tips($buy_user_id,$user_id,0,'order-send','你购买的商品已经发货','');

$data_arr['code']=1;
$data_arr['msg']=__('发货成功！','jinsom');


header('content-type:application/json');
echo json_encode($data_arr);