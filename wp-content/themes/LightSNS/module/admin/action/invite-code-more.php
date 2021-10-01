<?php 
//加载更多邀请码
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$page=$_POST['page'];
$number=$_POST['number'];
$offset=($page-1)*$number;
global $wpdb;
$table_name = $wpdb->prefix.'jin_invite_code';
$invite_code_data = $wpdb->get_results("SELECT * FROM $table_name  ORDER BY ID desc limit $offset,$number;");	
if($invite_code_data){
foreach ($invite_code_data as $data) {
if(!$data->status){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
if($data->use_user_id==''){
$use_user='--';
}else{
$use_user=jinsom_nickname_link($data->use_user_id);
}
if($data->use_time==''){
$use_time='--';	
}else{
$use_time=$data->use_time;	
}


echo '<li><span>'.$data->code.'</span><span>'.$status.'</span><span>'.$use_user.'</span><span>'.$use_time.'</span></li>';
}
}else{
echo jinsom_empty();
}


