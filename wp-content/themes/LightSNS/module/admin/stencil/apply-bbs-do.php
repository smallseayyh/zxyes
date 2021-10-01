<?php 
//版主申请
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$ID=$_POST['id'];
global $wpdb;
$table_name = $wpdb->prefix.'jin_bbs_note';
$bbs_data = $wpdb->get_results("SELECT * FROM $table_name WHERE ID='$ID' limit 1;");
foreach ($bbs_data as $data) {
$content=$data->content;
$status=$data->status;
}
?>
<div class="jinsom-admin-apply-bbs-admin-read-form">
<div class="content"><?php echo $content;?></div>
<div class="btn">
<?php if($status==0){?>
<span class="agree opacity" onclick="jinsom_admin_apply_bbs_do('agree',<?php echo $ID;?>,this)">通过</span>
<span class="refuse opacity" onclick="jinsom_admin_apply_bbs_do('refuse',<?php echo $ID;?>,this)">拒绝</span>
<?php }?>
<span class="del opacity" onclick="jinsom_admin_apply_bbs_do('del',<?php echo $ID;?>,this)">删除</span>
</div>
</div>
