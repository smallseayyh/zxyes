<?php
//版主申请表单
require( '../../../../../wp-load.php' );
$bbs_id=$_POST['bbs_id'];
$admin_a_name=get_term_meta($bbs_id,'admin_a_name',true);
$admin_b_name=get_term_meta($bbs_id,'admin_b_name',true);
?>
<div class="jinsom-apply-bbs-admin-content layui-form">

<select id="jinsom-apply-bbs-admin-type">
<option value="a"><?php echo $admin_a_name;?></option>
<option value="b"><?php echo $admin_b_name;?></option>
</select>

<textarea placeholder="<?php _e('请输入你申请的原因','jinsom');?>" id="jinsom-apply-bbs-admin-reason"></textarea>
<div class="btn opacity" onclick="jinsom_apply_bbs_admin(<?php echo $bbs_id;?>)"><?php _e('提交申请','jinsom');?></div>
</div>