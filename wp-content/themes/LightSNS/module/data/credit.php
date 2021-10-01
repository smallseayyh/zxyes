<?php 
//查询金币记录
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$credit_name=jinsom_get_option('jinsom_credit_name');
if(jinsom_is_admin($user_id)){
$user_id=$_POST['user_id'];
}

$page=$_POST['page'];
$number=$_POST['number'];
$offset=($page-1)*$number;
global $wpdb;

//查询金币收入
if(isset($_POST['type'])&&$_POST['type']=='credit-add'){
$table_name = $wpdb->prefix.'jin_credit_note';
$credit_add_data = $wpdb->get_results("SELECT content,number,time FROM $table_name WHERE  type='add' and user_id='$user_id' and action !='recharge-vip-wechatpay' and action !='recharge-vip-alipay' ORDER BY time desc limit $offset,$number;");	
if($credit_add_data){
foreach ($credit_add_data as $data) {
echo '<li><span>'.$data->content.'</span><span>+'.$data->number.' '.$credit_name.'</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';
}
}else{
echo jinsom_empty();
}
}

//查询金币支出
if(isset($_POST['type'])&&$_POST['type']=='credit-cut'){
$table_name = $wpdb->prefix.'jin_credit_note';
$credit_cut_data = $wpdb->get_results("SELECT content,number,time FROM $table_name WHERE  type='cut' and user_id='$user_id' ORDER BY time desc limit $offset,$number;");	
if($credit_cut_data){
foreach ($credit_cut_data as $data) {
echo '<li><span>'.$data->content.'</span><span>-'.$data->number.' '.$credit_name.'</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';
}
}else{
echo jinsom_empty();
}
}

//充值记录
if(isset($_POST['type'])&&$_POST['type']=='recharge-add'){
$table_name = $wpdb->prefix.'jin_credit_note';
$recharge_add_data = $wpdb->get_results("SELECT content,number,action,time FROM $table_name WHERE (action='recharge' or action='recharge-alipay' or action='recharge-wechatpay' or action='recharge-vip-wechatpay' or action='recharge-vip-alipay') and user_id='$user_id' ORDER BY time desc limit $offset,$number;");	
if($recharge_add_data){
foreach ($recharge_add_data as $data) {
if($data->action=='recharge-vip-wechatpay'||$data->action=='recharge-vip-alipay'){
echo '<li><span>'.$data->content.'</span><span>+'.$data->number.' 个月</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';
}else{
echo '<li><span>'.$data->content.'</span><span>+'.$data->number.' '.$credit_name.'</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';	
}

}
}else{
echo jinsom_empty();
}
}


//提现记录
if(isset($_POST['type'])&&$_POST['type']=='withdrawals'){
$table_name = $wpdb->prefix.'jin_cash';
$cash_data = $wpdb->get_results("SELECT ID,status,credit,cash_time FROM $table_name WHERE user_id='$user_id' ORDER BY cash_time desc limit $offset,$number;");
if($cash_data){
foreach ($cash_data as $data) {
if($data->status==0){
$status='等待审核中';
}else if($data->status==1){
$status='<font style="color:#46c47c;">提现成功</font>';	
}else{
$status='<font style="color:#f00;">提现失败，点击查看原因。</font>';	
}
echo '<li onclick="jinsom_cash_more('.$data->ID.')"><span>'.$status.'</span><span>'.$data->credit.' '.$credit_name.'</span><span title="'.$data->cash_time.'">'.jinsom_timeago($data->cash_time).'</span></li>';
}
}else{
echo jinsom_empty();
}
}




//查询经验
if(isset($_POST['type'])&&$_POST['type']=='exp-add'){
$table_name = $wpdb->prefix.'jin_exp_note';
$exp_add_data = $wpdb->get_results("SELECT content,number,time,type FROM $table_name WHERE user_id='$user_id' ORDER BY time desc limit $offset,$number;");	
if($exp_add_data){
foreach ($exp_add_data as $data) {
if($data->type=='add'){
echo '<li><span>'.$data->content.'</span><span>+'.$data->number.' 经验</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';	
}else{
echo '<li><span>'.$data->content.'</span><span>-'.$data->number.' 经验</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';		
}
}
}else{
echo jinsom_empty();
}
}