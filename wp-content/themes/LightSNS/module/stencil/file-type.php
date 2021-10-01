<?php
//弹窗选择附件类型
require( '../../../../../wp-load.php' );
$type=$_POST['type'];
?>
<div class="jinsom-upload-file-type">
<li class="file opacity" onclick="jinsom_insert_file_form('file',<?php echo $type;?>)"><i class="jinsom-icon jinsom-fujian-fill"></i><?php _e('文件','jinsom');?></li>
<li class="video opacity" onclick="jinsom_insert_file_form('video',<?php echo $type;?>)"><i class="jinsom-icon jinsom-shipin1"></i><?php _e('视频','jinsom');?></li>
<li class="music opacity" onclick="jinsom_insert_file_form('music',<?php echo $type;?>)"><i class="jinsom-icon jinsom-yinle"></i><?php _e('音乐','jinsom');?></li>
</div>
