<?php 
//查询充值记录 分页
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$page=$_POST['page'];
$number=$_POST['number'];
$offset=($page-1)*$number;
global $wpdb;
$table_name = $wpdb->prefix.'jin_credit_note';
$recharge_add_data = $wpdb->get_results("SELECT user_id,content,number,action,time FROM $table_name WHERE (action='recharge' or action='recharge-alipay' or action='recharge-wechatpay' or action='recharge-vip-wechatpay' or action='recharge-vip-alipay') ORDER BY time desc limit $offset,$number;");	
if($recharge_add_data){
foreach ($recharge_add_data as $data) {
$time=$data->time;
$time_a=strtotime($time);
$time_b=date('Y-m-d',$time_a);
$today_date=date('Y-m-d');
if($today_date==$time_b){
$time_red='<font style="color:#f00;">'.$time.'</font>';
}else{
$time_red=$time;
}
if($data->action=='recharge-vip-wechatpay' || $data->action=='recharge-vip-alipay'){
echo '<li><span>'.jinsom_nickname_link($data->user_id).'</span><span>'.$data->content.'</span><span>'.$data->number.' 个月</span><span title="'.$time.'">'.$time_red.'</span></li>';
}else{
echo '<li><span>'.jinsom_nickname_link($data->user_id).'</span><span>'.$data->content.'</span><span>'.$data->number.' '.jinsom_get_option('jinsom_credit_name').'</span><span title="'.$time.'">'.$time_red.'</span></li>';	
}
}
}else{
echo jinsom_empty();
}

