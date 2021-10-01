<?php 
//将消息标为已读
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(is_user_logged_in()&&isset($_POST['author_id'])){
// jinsom_update_had_read_msg($_POST['author_id']);
}