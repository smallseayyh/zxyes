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
$bbs_data = $wpdb->get_results("SELECT * FROM $table_name where type='bbs_admin' ORDER BY time desc limit $offset,$number;");
if($bbs_data){
foreach ($bbs_data as $data) {
$id=$data->ID;
$user_id=$data->user_id;
$bbs_id=$data->bbs_id;
if($data->note_type=='a'){
$admin_name=get_term_meta($bbs_id,'admin_a_name',true);
}else{
$admin_name=get_term_meta($bbs_id,'admin_b_name',true);
}
$bbs='<a href="'.get_category_link($bbs_id).'" target="_blank" title="论坛ID：'.$bbs_id.'">'.get_category($bbs_id)->name.'</a>';
if($data->status==0){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_admin_read_form('.$id.')" style="color:#4CAF50;">点击查看</m>';
}else if($data->status==1){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_admin_read_form('.$id.')">已经通过</m>';	
}else if($data->status==2){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_admin_read_form('.$id.')">已经拒绝</m>';	
}
echo '<li id="jinsom-admin-apply-bbs-admin-'.$id.'"><span>'.jinsom_nickname_link($user_id).'</span><span>'.$admin_name.'</span><span>'.$bbs.'</span><span>'.jinsom_timeago($data->time).'</span><span>'.$do.'</span></li>';
}
}else{
echo jinsom_empty();
}
