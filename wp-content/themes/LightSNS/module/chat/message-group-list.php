<?php
require( '../../../../../wp-load.php' );
if (is_user_logged_in()) {
$user_id=$current_user->ID;

//群聊消息	
if(isset($_POST['bbs_id'])){
$bbs_id=$_POST['bbs_id'];
$get_msg=jinsom_get_group_msg($bbs_id,0,50);
$msg='';
foreach (array_reverse($get_msg) as $get_msgs) {
$type=$get_msgs->type;
$time1=strtotime($get_msgs->msg_time);
$time2=time();
$time3=$time2-$time1;
if($time3<300){
$chat_time=date('H:i:s',$time1);
}elseif($time3>=300&&$time3<60*60*24){
$chat_time=date('H:i:s',$time1);	
}else{
$chat_time=date('Y/m/d H:i:s',$time1);			
}

if($type==1){
$msg .= '<p class="jinsom-chat-message-list-time">'.$chat_time.'</p>';

if($get_msgs->user_id==$user_id){
$msg .= '
<li class="myself">
<div class="jinsom-chat-message-list-user-info  avatarimg-'.$user_id.' ">
'.jinsom_avatar($user_id, '50' , avatar_type($user_id) ).'
</div>
<div class="jinsom-chat-message-list-content">'.wpautop(jinsom_autolink(convert_smilies($get_msgs->content))).'</div>
</li>';
}else{
$msg .= '
<li>
<div class="jinsom-chat-message-list-user-info  avatarimg-'.$get_msgs->user_id.' ">
<m onclick="jinsom_chat_group_show_user_info('.$get_msgs->user_id.',this)">
'.jinsom_vip_icon($get_msgs->user_id).jinsom_avatar($get_msgs->user_id, '50' , avatar_type($get_msgs->user_id) ).jinsom_verify($get_msgs->user_id).'
</m>
<a href="'.jinsom_userlink($get_msgs->user_id).'" target="_blank">
<span>'.jinsom_nickname($get_msgs->user_id).'</span>
'.jinsom_lv($get_msgs->user_id).jinsom_vip($get_msgs->user_id).jinsom_honor($get_msgs->user_id).'
</a>
</div>
<div class="jinsom-chat-message-list-content">'.wpautop(jinsom_autolink(convert_smilies($get_msgs->content))).'</div>
</li>
';
}

}else if($type==2){
$msg .= '<p class="jinsom-chat-message-list-join"><span>'.jinsom_nickname($get_msgs->user_id).' 加入了群聊</span></p>';	
}else if($type==3){
$msg .= '<p class="jinsom-chat-message-list-join"><span>'.jinsom_nickname($get_msgs->user_id).' 退出了群聊</span></p>';	
}


}


echo json_encode(array("code"=>2,"count"=>jinsom_get_chat_group_msg_count($bbs_id),"msg"=>$msg));


}


}