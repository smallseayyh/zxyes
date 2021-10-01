<?php
//论坛申请表单
require( '../../../../../wp-load.php' );
?>
<div class="jinsom-apply-bbs-content">

<input type="text" class="title" id="jinsom-apply-bbs-title" placeholder="<?php _e('请输入名称','jinsom');?>">

<textarea placeholder="<?php _e('请输入你申请的原因','jinsom');?>" id="jinsom-apply-bbs-reason"></textarea>
<div class="btn opacity" onclick="jinsom_apply_bbs(<?php echo $bbs_id;?>)"><?php _e('提交申请','jinsom');?></div>
</div>