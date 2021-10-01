<?php 
require( '../../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
global $wpdb;
$table_name = $wpdb->prefix.'jin_notice';
$notice_data = $wpdb->get_results("SELECT * FROM $table_name WHERE my_id = $user_id and (notice_type ='follow' or notice_type ='like' or notice_type='comment-up') ORDER BY notice_time DESC LIMIT 30");
?>
<div data-page="like-follow-notice" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">提醒消息</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-notice-tips-content">
<div class="jinsom-chat-user-list notice list-block">
<?php 
if($notice_data){
foreach ($notice_data as $data) {
$notice_user_id=$data->user_id;
$post_id=$data->post_id;
$action=$data->notice_content;
$time=$data->notice_time;
$type=$data->notice_type;
$status=$data->status;
if(!$status){$status='<span></span>';}else{$status='';}
if($type=='follow'){
$link=jinsom_mobile_author_url($notice_user_id);
}else{
$link=jinsom_mobile_post_url($post_id);	
}
if($type=='comment-up'){
$link.='&comment_id='.$data->remark;
}
echo '
<li>
<div class="item-content">
<div class="item-media">
<a href="'.jinsom_mobile_author_url($notice_user_id).'" class="link">
'.$status.'
'.jinsom_avatar($notice_user_id,'40',avatar_type($notice_user_id)).jinsom_verify($notice_user_id).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="'.$link.'" class="link">
<div class="name">'.jinsom_nickname($notice_user_id).jinsom_vip($notice_user_id).jinsom_honor($notice_user_id).'</div>
<div class="desc">'.$action.'</div>
</a>
</div>
<div class="time">'.jinsom_timeago($time).'</div>
</div>
</div>
</li>
';
}
//将消息标记为已读
$wpdb->query("UPDATE $table_name SET status = 1 WHERE my_id = $user_id and (notice_type ='follow' or notice_type ='like' or notice_type='comment-up');");	
}else{
echo jinsom_empty();
}
?>
</div>
</div>
</div>        