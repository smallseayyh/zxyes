<?php 
//查询提现记录 分页
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$page=$_POST['page'];
$number=$_POST['number'];
$offset=($page-1)*$number;
global $wpdb;
$table_name = $wpdb->prefix.'jin_bbs_note';
$bbs_data = $wpdb->get_results("SELECT * FROM $table_name where type='bbs' ORDER BY time desc limit $offset,$number;");
if($bbs_data){
foreach ($bbs_data as $data) {
$id=$data->ID;
$user_id=$data->user_id;
$status=$data->status;
if($status==0||$status==2){//未审核和拒绝
$bbs='<a>'.$data->title.'</a>';
}else{
$bbs='<a href="'.get_category_link($data->bbs_id).'" target="_blank" title="论坛ID：'.$data->bbs_id.'">'.get_category($data->bbs_id)->name.'</a>';
}
if($status==0){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_read_form('.$id.')" style="color:#4CAF50;">点击查看</m>';
}else if($status==1){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_read_form('.$id.')">已经通过</m>';	
}else if($status==2){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_read_form('.$id.')">已经拒绝</m>';	
}
echo '<li id="jinsom-admin-apply-bbs-admin-'.$id.'"><span>'.jinsom_nickname_link($user_id).'</span><span>'.$bbs.'</span><span>'.jinsom_timeago($data->time).'</span><span>'.$do.'</span></li>';
}
}else{
echo jinsom_empty();
}
