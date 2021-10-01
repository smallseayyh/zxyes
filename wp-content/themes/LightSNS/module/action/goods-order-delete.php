<?php
//删除未支付的商品订单，已经支付的都不能删除
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id = $current_user->ID;
$trade_no=(int)$_POST['trade_no'];


//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$order_data=$wpdb->get_results("SELECT * FROM $table_name WHERE trade_no = $trade_no limit 1;");
$status=$wpdb->query( "DELETE FROM $table_name WHERE trade_no=$trade_no and user_id=$user_id;" );
if($status){
$data_arr['code']=1;
$data_arr['msg']='删除成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='删除失败！';
}


header('content-type:application/json');
echo json_encode($data_arr);