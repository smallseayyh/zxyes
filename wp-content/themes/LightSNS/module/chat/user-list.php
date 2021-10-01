<?php
//IM列表
require( '../../../../../wp-load.php' );
if (is_user_logged_in()) {
$user_id=$current_user->ID;

//最近会话	
if(isset($_POST['recent'])){
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
$un_read='<span class="jinsom-chat-list-tips">'.$msg_num.'</span>';
}else{
$un_read='';
}

if(preg_match ("/<img([^>]*)\s*src=('|\")([^'\"]+)('|\")/",$chat_content)){
$chat_content='['.__('图片','jinsom').']';
}else{
$chat_content=strip_tags($chat_content);	
$chat_content=preg_replace("/\[goods[^]]+\]/", "[商品信息]",$chat_content);
}

echo '
<li onclick="jinsom_open_user_chat('.$chat_user_id.',this)">
<div class="jinsom-chat-content-recent-user-avatar">
'.jinsom_vip_icon($chat_user_id).jinsom_avatar($chat_user_id,'40',avatar_type($chat_user_id)).jinsom_verify($chat_user_id).'
</div>	
<div class="jinsom-chat-content-recent-user-info">
<span class="name">'.jinsom_nickname($chat_user_id).'</span>
<span class="time">'.$chat_time.'</span>	
<span class="msg">'.$chat_content.'</span>
</div>
'.$un_read.'
</li>	
';
array_push($arr,$chat_user_id);
}


}

}else{
echo jinsom_empty();
}

}



//我关注的人	
if(isset($_POST['follow'])){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$get_follow_list = $wpdb->get_results("SELECT * FROM $table_name WHERE follow_user_id = $user_id and follow_status <>0  ORDER BY follow_time DESC limit 30;");
if($get_follow_list){
foreach ($get_follow_list as $get_follow_lists) {
$follow_id=$get_follow_lists->user_id;
$user_info = get_userdata($follow_id);

echo '
<li onclick="jinsom_open_user_chat('.$follow_id.',this)">
<div class="jinsom-chat-content-recent-user-avatar">
'.jinsom_vip_icon($follow_id).jinsom_avatar($follow_id,'40',avatar_type($follow_id)).jinsom_verify($follow_id).'
</div>	
<div class="jinsom-chat-content-recent-user-info">
<span class="name">'.jinsom_nickname($follow_id).'</span>
<span class="msg">'.$user_info->description.'</span>
</div>
</li>	
';


}

}else{
echo jinsom_empty();
}

}


//群组	
if(isset($_POST['group'])){
if(jinsom_get_option('jinsom_im_group_on_off')){
$get_follow_list=jinsom_get_user_follow_bbs($user_id);
if($get_follow_list){
foreach ($get_follow_list as $bbs) {
$bbs_id=$bbs->bbs_id;
$group_im=get_term_meta($bbs_id,'bbs_group_im',true);

if($group_im){//只显示已经开启了群聊的
$bbs_notice=get_term_meta($bbs_id,'bbs_notice',true);

echo '
<li onclick="jinsom_open_group_chat('.$bbs_id.')">
<div class="jinsom-chat-content-recent-user-avatar">
'.jinsom_get_bbs_avatar($bbs_id,0).'
</div>	
<div class="jinsom-chat-content-recent-user-info">
<span class="name">'.get_category($bbs_id)->name.'</span>	
<span class="msg">'.strip_tags(get_term_meta($bbs_id,'desc',true)).'</span>
</div>
</li>	
';
}
}
}else{
echo jinsom_empty();
}



}

}
}