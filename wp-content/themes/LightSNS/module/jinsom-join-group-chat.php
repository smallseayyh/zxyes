<?php
//加入群聊
require( '../../../../wp-load.php' );
if (is_user_logged_in()) {
$user_id=$current_user->ID;

//加入群聊
if(isset($_POST['bbs_id'])){
$bbs_id=$_POST['bbs_id'];
if(jinsom_is_bbs_like($user_id,$bbs_id)){
echo 1;
}else{
echo 2;
}

}

}else{
echo 3;
}