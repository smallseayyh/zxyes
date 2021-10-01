<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name = $wpdb->prefix.'jin_notice';
$id=$_POST['id'];
$notice_data = $wpdb->get_results("SELECT notice_content FROM $table_name WHERE ID=$id limit 1;");
foreach ($notice_data as $data) {
$notice_content=$data->notice_content;
}
?>
<!-- 编辑全站通知-->
<div class="jinsom-admin-notice-add-content read">
<?php echo $notice_content;?>
</div>