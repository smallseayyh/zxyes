<?php 
//获取消息页面的数据
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$theme_url=get_template_directory_uri();
$item_notice=jinsom_get_item_notice_number($user_id);
$comment_notice=jinsom_get_comment_notice_number($user_id);
$like_follow_notice=jinsom_get_like_follow_notice_number($user_id);
$system_notice=jinsom_site_notice_number($user_id);
echo do_shortcode(jinsom_get_option('jinsom_mobile_notice_header_custom_html'));
?>

<!-- 最近 -->
<div id="jinsom-chat-tab-recently" class="tab active">
<div class="jinsom-chat-notice">

<li class="at">
<?php if($item_notice){echo'<span class="tips">'.$item_notice.'</span>';}?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/notice/notice-item.php" class="link">
<i class="jinsom-icon jinsom-aite2"></i><p><?php _e('@我的','jinsom');?></p>
</a>
</li>
<li class="comment">
<?php if($comment_notice){echo'<span class="tips">'.$comment_notice.'</span>';}?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/notice/notice-comment.php" class="link">
<i class="jinsom-icon jinsom-pinglun2"></i></i><p><?php _e('回复','jinsom');?></p>
</a>
</li>
<li class="like">
<?php if($like_follow_notice){echo'<span class="tips">'.$like_follow_notice.'</span>';}?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/notice/notice-follow-like.php" class="link">
<i class="jinsom-icon jinsom-xihuan2"></i></i><p><?php _e('喜欢','jinsom');?></p>
</a>
</li>
<li class="system">
<?php if($system_notice){echo'<span class="tips">'.$system_notice.'</span>';}?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/notice/notice-system.php" class="link">
<i class="jinsom-icon jinsom-tongzhi1"></i><p><?php _e('通知','jinsom');?></p>
</a>
</li>

</div>
<div class="jinsom-chat-user-list list-block">
<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message';
$list_data = $wpdb->get_results("SELECT * FROM(SELECT * FROM $table_name WHERE user_id = $user_id OR from_id=$user_id ORDER BY msg_date DESC LIMIT 999) as a  GROUP BY user_id,from_id ORDER BY msg_date DESC limit 50;");
if($list_data){
$arr=array();
foreach ($list_data as $data) {
$chat_user_id=$data->from_id;
if($chat_user_id==$user_id){
$chat_user_id=$data->user_id;	
}
$chat_content=$data->msg_content;
if(!in_array($chat_user_id,$arr)){

$chat_date=$data->msg_date;
$chat_time=date('Y-m-d',strtotime($chat_date));
if($chat_time==date('Y-m-d',time())){
$chat_time=date('H:i',strtotime($chat_date));
}else if($chat_time==date("Y-m-d",strtotime("-1 day"))){
$chat_time='昨天';
}else{
$chat_time=date('m-d',strtotime($chat_date));
}

$user_info = get_userdata($chat_user_id);
$msg_num=jinsom_get_my_unread_msg($chat_user_id);
if($msg_num){
$un_read='<span class="badge">'.$msg_num.'</span>';
$had_read='<a onclick="jinsom_chat_set_hadread('.$chat_user_id.',this)" class="swipeout-close">标为已读</a>';
}else{
$un_read='';
$had_read='';
}
if(preg_match ("/<img([^>]*)\s*src=('|\")([^'\"]+)('|\")/",$chat_content)){
$chat_content='['.__('图片','jinsom').']';
}else{
$chat_content=strip_tags($chat_content);
$chat_content=preg_replace("/\[goods[^]]+\]/", "[商品信息]",$chat_content);	
}

$last_time=get_user_meta($chat_user_id,'latest_login',true);
if((time()-strtotime($last_time))<300){//5分钟之内为在线
$online_type=get_user_meta($chat_user_id,'online_type',true);
if($online_type==1){
$online_status='<m>['.__('手机在线','jinsom').'] </m>';
}else{
$online_status='<m>['.__('电脑在线','jinsom').'] </m>';
}
}else{
$online_status='['.__('离线','jinsom').'] ';
}


echo '
<li class="swipeout chat" id="jinsom-chat-user-'.$chat_user_id.'">
<div class="swipeout-content item-content" onclick="jinsom_open_user_chat('.$chat_user_id.',this)">
<div class="item-media">
'.jinsom_avatar($chat_user_id,'40',avatar_type($chat_user_id)).jinsom_verify($chat_user_id).'
</div>
<div class="item-inner">
<div class="item-title">
<div class="name">'.jinsom_nickname($chat_user_id).jinsom_vip($chat_user_id).jinsom_honor($chat_user_id).'</div>
<div class="desc">'.$online_status.$chat_content.'</div>
</div>
<div class="time">'.$chat_time.'</div>
<div class="tips">'.$un_read.'</div>
</div>
</div>
<div class="swipeout-actions-right">
'.$had_read.'
<a href="#" class="swipeout-delete">'.__('删除','jinsom').'</a>
</div>
</li>
';


array_push($arr,$chat_user_id);

}
}

}
?>
</div>
</div>
<!-- 关注 -->
<div id="jinsom-chat-tab-follow" class="tab">
<div class="jinsom-chat-user-list list-block">
<?php 
$table_name_b = $wpdb->prefix . 'jin_follow';
$get_follow_list = $wpdb->get_results("SELECT * FROM $table_name_b WHERE follow_user_id = $user_id and follow_status <>0  ORDER BY follow_time DESC limit 30;");
if($get_follow_list){
foreach ($get_follow_list as $get_follow_lists) {
$follow_id=$get_follow_lists->user_id;
$user_info = get_userdata($follow_id);

$last_time=get_user_meta($follow_id,'latest_login',true);
if((time()-strtotime($last_time))<300){//5分钟之内为在线
$online_type=get_user_meta($follow_id,'online_type',true);
if($online_type==1){
$online_status='<m>['.__('手机在线','jinsom').'] </m>';
}else{
$online_status='<m>['.__('电脑在线','jinsom').'] </m>';
}
}else{
$online_status='['.__('离线','jinsom').'] ';
}



echo '
<li class="swipeout chat" id="jinsom-follow-user-'.$follow_id.'">
<div class="swipeout-content item-content" onclick="jinsom_open_user_chat('.$follow_id.')">
<div class="item-media">
'.jinsom_avatar($follow_id,'40',avatar_type($follow_id)).jinsom_verify($follow_id).'
</div>
<div class="item-inner">
<div class="item-title">
<div class="name">'.jinsom_nickname($follow_id).jinsom_vip($follow_id).jinsom_honor($follow_id).'</div>
<div class="desc">'.$online_status.$user_info->description.'</div>
</div>
</div>
</div>
<div class="swipeout-actions-right"><a href="#" class="swipeout-delete" onclick="jinsom_follow('.$follow_id.',this)">'.__('取消关注','jinsom').'</a></div>
</li>
';


}

}else{
echo jinsom_empty();
}

?>
</div>
</div>
<!-- 群组 -->
<?php if(jinsom_get_option('jinsom_im_group_on_off')){?>
<div id="jinsom-chat-tab-group" class="tab">
<div class="jinsom-chat-user-list list-block">
<?php 
$get_follow_list=jinsom_get_user_follow_bbs($user_id);
if($get_follow_list){
foreach ($get_follow_list as $bbs) {
$bbs_id=$bbs->bbs_id;
$group_im=get_term_meta($bbs_id,'bbs_group_im',true);

if($group_im){//只显示已经开启了群聊的
$bbs_notice=get_term_meta($bbs_id,'bbs_notice',true);


echo '
<li class="swipeout chat" id="jinsom-chat-group-'.$bbs_id.'">
<div class="swipeout-content item-content" onclick="jinsom_open_group_chat('.$bbs_id.')">
<div class="item-media">
'.jinsom_get_bbs_avatar($bbs_id,0).'
</div>
<div class="item-inner">
<div class="item-title">
<div class="name">'.get_category($bbs_id)->name.'</div>
<div class="desc">'.strip_tags(get_term_meta($bbs_id,'desc',true)).'</div>
</div>
</div>
</div>
<div class="swipeout-actions-right"><a href="#" class="swipeout-delete">'.__('取消关注','jinsom').'</a></div>
</li>
';


}
}
}else{
echo jinsom_empty();
}
?>
</div>
</div>
<?php }?>