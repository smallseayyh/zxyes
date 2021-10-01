<?php
require( '../../../../../wp-load.php' );
if (is_user_logged_in()) {
$user_id=$current_user->ID;

//聊天消息	
if(isset($_POST['user_id'])){
$chat_user_id=$_POST['user_id'];

$get_msg=jinsom_get_msg($user_id,$chat_user_id,0,20);
foreach (array_reverse($get_msg) as $get_msgs) {

$time1=strtotime($get_msgs->msg_date);
$time2=time();
$time3=$time2-$time1;
if($time3<300){
$chat_time=date('H:i:s',$time1);
}elseif($time3>=300&&$time3<60*60*24){
$chat_time=date('H:i:s',$time1);	
}else{
$chat_time=date('Y/m/d H:i:s',$time1);			
}

echo '<p class="jinsom-chat-message-list-time">'.$chat_time.'</p>';
if($get_msgs->from_id==$user_id){

echo '
<li class="myself">
<div class="jinsom-chat-message-list-user-info avatarimg-'.$user_id.' ">
'.jinsom_avatar($user_id,'50',avatar_type($user_id) ).'
</div>
<div class="jinsom-chat-message-list-content">'.do_shortcode(wpautop(jinsom_autolink(convert_smilies($get_msgs->msg_content)))).'</div>
</li>';
}else{
echo '
<li>
<div class="jinsom-chat-message-list-user-info avatarimg-'.$chat_user_id.' ">
<m onclick="jinsom_chat_group_show_user_info('.$chat_user_id.',this)">
'.jinsom_avatar($chat_user_id,'50',avatar_type($chat_user_id)).jinsom_verify($chat_user_id).'
</m>
</div>
<div class="jinsom-chat-message-list-content">'.do_shortcode(wpautop(jinsom_autolink(convert_smilies($get_msgs->msg_content)))).'</div>
</li>
';
}
//<div class="tool"><i class="jinsom-icon jinsom-chehui-"></i> 撤回</div>
}
//将未读的消息设置为已经读取
jinsom_update_had_read_msg($chat_user_id);

}


}