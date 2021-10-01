<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name = $wpdb->prefix.'jin_notice';
?>
<!-- 发表全站通知-->
<div class="jinsom-admin-notice-add-content">
<textarea id="jinsom-admin-notice-add-val" placeholder="请输入通知内容"></textarea>
<div class="tips">*发表之后，所有用户都会收到提醒</div>
<div class="btn opacity" onclick="jinsom_admin_notice_add()">发表通知</div>
</div>