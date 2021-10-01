<?php
//更多喜欢的用户
require( '../../../../../wp-load.php' );
$post_id=$_POST['post_id'];
if(!is_numeric($post_id)){//防注入
exit();
}

global $wpdb;
$table_name = $wpdb->prefix . 'jin_like';
$show_posts_like=$wpdb->get_results("SELECT * FROM $table_name WHERE post_id = $post_id AND type='post'  and status=1 ORDER BY like_time DESC;");
if($show_posts_like){
echo '<div class="jinsom-sidebar-user-list more-like">';
foreach ($show_posts_like as $data) {
$user_id=$data->user_id;
$desc=get_user_meta($user_id,'description',true);
echo '
<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'" target="_blank">'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'40',avatar_type($user_id)).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.$desc.'</div>
</div>
<div class="follow">
<div class="number">'.jinsom_timeago($data->like_time).'</div>
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';	

}
echo '</div>';
}else{
echo jinsom_empty();
}

?>