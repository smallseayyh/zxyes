<?php
require( '../../../../../wp-load.php' );
//获取所有未读消息的数量
if(is_user_logged_in()){
$tips_number=(int)jinsom_all_notice_number();
$jinsom_chat_notice=(int)jinsom_get_my_all_unread_msg();
$all_tip_number=$tips_number+$jinsom_chat_notice;
if($all_tip_number){
echo $all_tip_number;
}
}