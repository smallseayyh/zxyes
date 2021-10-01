<?php
//全站通知
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
global $wpdb;
$table_name = $wpdb->prefix.'jin_notice';
$notice_data = $wpdb->get_results("SELECT * FROM $table_name WHERE notice_type ='notice' ORDER BY notice_time DESC LIMIT 15");
?>
<div class="jinsom-system-notice-content">
<?php 
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
if(count($notice_data)==15){
echo '<div class="more opacity" onclick="jinsom_system_notice_more(this)" data="2">'.__('加载更多','jinsom').'</div>';	
}
}else{
echo jinsom_empty(__('暂没有通知','jinsom'));
}
update_user_meta($user_id,'system_notice_time',time());
?>	
</div>

