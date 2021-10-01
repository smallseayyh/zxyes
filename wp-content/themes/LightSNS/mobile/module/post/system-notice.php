<?php 
//系统通知 加载更多
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$page=$_POST['page'];
$number=15;
$offset=($page-1)*$number;
global $wpdb;
$table_name = $wpdb->prefix.'jin_notice';
$notice_data = $wpdb->get_results("SELECT * FROM $table_name WHERE notice_type ='notice' ORDER BY notice_time DESC LIMIT $offset,$number");

if($notice_data){
foreach ($notice_data as $data){
$notice_time=$data->notice_time;
$user_time=get_user_meta($user_id,'system_notice_time',true);
if($user_time){
if($user_time>strtotime($notice_time)){
$status='';
}else{
$status='<i></i>';	
}
}else{
$status='<i></i>';
}

echo '<div class="time">'.jinsom_timeago($notice_time).'</div>';
echo '<li>'.$status.'<div class="content">'.do_shortcode(convert_smilies($data->notice_content)).'</div></li>';
}
}else{
echo 0;
}