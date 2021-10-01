<?php
//查询商品订单
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$trade_no=$_POST['trade_no'];//订单号


$i=0;  
while (true){
sleep(1); 
$i++;  

$data=$wpdb->get_results("SELECT * FROM $table_name WHERE trade_no = $trade_no LIMIT 1;");
if($data){
foreach ($data as $datas) {
$out_trade_no=$datas->out_trade_no;//商户订单
}
if($out_trade_no){//已经支付完成

$data_arr['code']=1;
$data_arr['msg']='<p><i class="jinsom-icon jinsom-zhifuchenggong"></i></p><p>支付成功</p><p class="small">请在我的订单中查看，正在跳转...</p>';

header('content-type:application/json');
echo json_encode($data_arr);
exit(); 



}


}


if($i==29){//30秒后 强制退出轮询
$data_arr['code']=0;
header('content-type:application/json');
echo json_encode($data_arr);
exit();  
} 

}//while


