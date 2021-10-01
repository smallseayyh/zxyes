<?php 
require( '../../../../../../wp-load.php' );
//邀请码导出
if(!current_user_can('level_10')){exit();}
$status=$_POST['status'];
$rank=$_POST['rank'];

global $wpdb;
$table_name = $wpdb->prefix.'jin_invite_code';

header("Content-type:application/octet-stream");
header("Accept-Ranges:bytes");
header("Content-Disposition:attachment;filename=".$type_text.'邀请码_'.date("Y-m-d+His").".txt");
header("Expires: 0");
header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
header("Pragma:public");

$invite_code_data = $wpdb->get_results("SELECT * FROM $table_name WHERE status='$status' ORDER BY ID desc;");

if($invite_code_data){
if($rank==1){
foreach ($invite_code_data as $data) {
echo $data->code."\r\n";
}
}else{
foreach ($invite_code_data as $data) {
echo $data->code.",";
}	
}
}else{
echo "都没有数据，导出你妹啊！";	
}
