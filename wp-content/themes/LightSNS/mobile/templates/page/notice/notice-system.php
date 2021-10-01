<?php 
require( '../../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
global $wpdb;
$table_name = $wpdb->prefix.'jin_notice';
$notice_data = $wpdb->get_results("SELECT * FROM $table_name WHERE notice_type ='notice' ORDER BY notice_time DESC LIMIT 15");
?>
<div data-page="system-notice" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">通知消息</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-site-notice-content infinite-scroll" data-distance="200">
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

echo '<div class="time">'.date('m-d H:i',strtotime($notice_time)).'</div>';
echo '<li>'.$status.'<div class="content">'.do_shortcode(convert_smilies($data->notice_content)).'</div></li>';
}
}else{
echo jinsom_empty('暂没有通知');
}
update_user_meta($user_id,'system_notice_time',time());
?>
</div>
</div>        