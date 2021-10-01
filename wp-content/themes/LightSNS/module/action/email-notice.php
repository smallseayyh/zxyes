<?php
//完善资料
require( '../../../../../wp-load.php' );


if (is_user_logged_in()) {
$user_id = $current_user->ID;
$value=$_POST['value'];

//系统消息
if(isset($_POST['type'])&&$_POST['type']=='system'){
update_user_meta($user_id,'system_notice',$value);
}

//用户消息
if(isset($_POST['type'])&&$_POST['type']=='user'){
update_user_meta($user_id,'user_notice',$value);
}

//评论艾特消息
if(isset($_POST['type'])&&$_POST['type']=='comment'){
update_user_meta($user_id,'comment_notice',$value);
}


}
?>