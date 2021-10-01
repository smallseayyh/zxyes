<?php
//弹窗选择附件类型
require( '../../../../../wp-load.php' );
$editor_type=$_POST['editor_type'];
$upload_type=$_POST['upload_type'];
$user_id=$current_user->ID;
?>
<div class="jinsom-insert-file-content">

<?php if($upload_type=='file'){?>
<div id="jinsom-insert-file" class="jinsom-insert-file opacity">
<i class="fa fa-plus"></i> <?php _e('从本地选择文件上传','jinsom');?>
<form id="jinsom-insert-file-form" method="post" enctype="multipart/form-data" action="<?php echo get_template_directory_uri();?>/module/upload/file.php">
<input id="jinsom-insert-file-input" type="file" name="file">
<input type="hidden" name="type" value="file">
</form>
</div>
<div class="jinsom-file-progress">
<span class="jinsom-file-bar"></span>
<span class="jinsom-file-percent">0%</span>
</div>
<input type="text"  placeholder="附件地址：https://" id="jinsom-insert-file-url">
<input type="text"  placeholder="请输入文件名称" id="jinsom-insert-file-name">
<textarea id="jinsom-insert-file-info" placeholder="下载密码：xxxx，解压密码：xxxx"></textarea>
<div class="jinsom-insert-file-btn opacity" onclick="jinsom_bbs_insert_file(<?php echo $editor_type;?>);"><?php _e('插入文件','jinsom');?></div>
<?php }else if($upload_type=='video'){?>
<div id="jinsom-insert-file" class="jinsom-insert-file opacity">
<i class="fa fa-plus"></i> <?php _e('从本地选择视频上传','jinsom');?>
<form id="jinsom-insert-file-form" method="post" enctype="multipart/form-data" action="<?php echo get_template_directory_uri();?>/module/upload/file.php">
<input id="jinsom-insert-file-input" type="file" name="file">
<input type="hidden" name="type" value="video">
</form>
</div>
<div class="jinsom-file-progress">
<span class="jinsom-file-bar"></span>
<span class="jinsom-file-percent">0%</span>
</div>
<input type="text"  placeholder="仅支持MP4，m3u8，flv，mov格式" id="jinsom-insert-file-url">
<input type="text"  placeholder="视频封面地址(可为空) https://" id="jinsom-insert-video-cover">
<div class="jinsom-insert-file-btn opacity" onclick="jinsom_bbs_insert_video(<?php echo $editor_type;?>);"><?php _e('插入视频','jinsom');?></div>
<?php }else{?>
<div id="jinsom-insert-file" class="jinsom-insert-file opacity">
<i class="fa fa-plus"></i> <?php _e('从本地选择音乐上传','jinsom');?>
<form id="jinsom-insert-file-form" method="post" enctype="multipart/form-data" action="<?php echo get_template_directory_uri();?>/module/upload/file.php">
<input id="jinsom-insert-file-input" type="file" name="file">
<input type="hidden" name="type" value="music">
</form>
</div>
<div class="jinsom-file-progress">
<span class="jinsom-file-bar"></span>
<span class="jinsom-file-percent">0%</span>
</div>
<input type="text"  placeholder="只支持mp3格式的外链：https://" id="jinsom-insert-file-url">
<div class="jinsom-insert-file-btn opacity" onclick="jinsom_bbs_insert_music(<?php echo $editor_type;?>);"><?php _e('插入音乐','jinsom');?></div>
<?php }?>

</div>


