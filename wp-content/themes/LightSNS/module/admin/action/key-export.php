<?php 
require( '../../../../../../wp-load.php' );
//卡密导出
if(!current_user_can('level_10')){exit();}
$user_id=$current_user->ID;
$type=$_POST['type'];
$number=$_POST['number'];
$custom_number=$_POST['custom_number'];
$status=$_POST['status'];
$rank=$_POST['rank'];
if($type=='credit'){
$type_text=jinsom_get_option('jinsom_credit_name');
}else if($type=='vip'){
$type_text='会员';	
}else if($type=='exp'){
$type_text='经验';	
}else if($type=='sign'){
$type_text='补签';	
}else if($type=='vip_number'){
$type_text='成长值';	
}else if($type=='nickname'){
$type_text='改名卡';	
}





global $wpdb;
$table_name = $wpdb->prefix.'jin_key';

header("Content-type:application/octet-stream");
header("Accept-Ranges:bytes");
header("Content-Disposition:attachment;filename=".$type_text.'卡密_'.date("Y-m-d+His").".txt");
header("Expires: 0");
header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
header("Pragma:public");

if($number=='all'){
if($type=='all'){
if($status==2){
$key_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY ID desc;");	
}else{
$key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE status='$status' ORDER BY ID desc;");
}	
}else{
if($status==2){
$key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='$type'ORDER BY ID desc;");
}else{
$key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='$type' and status='$status' ORDER BY ID desc;");
}
}
}else{//自定义数量
if($type=='all'){
if($status==2){
$key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE number='$custom_number' ORDER BY ID desc;");	
}else{
$key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE status='$status' and number='$custom_number' ORDER BY ID desc;");
}	
}else{
if($status==2){
$key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='$type' and number='$custom_number' ORDER BY ID desc;");
}else{
$key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='$type' and status='$status' and number='$custom_number' ORDER BY ID desc;");
}
}	
}

if($rank==1){
foreach ($key_data as $data) {
echo $data->key_number."\r\n";
}
}else{
foreach ($key_data as $data) {
echo $data->key_number.",";
}	
}

