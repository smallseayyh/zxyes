<?php 
//批量导入
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
?>

<div class="jinsom-multiple-import-form layui-form">
<div class="lable">
<p>随机作者用户ID：</p>
<p>批量导入内容：(一行一条数据)</p>
</div>
<div class="content">
<textarea class="jinsom-multiple-import-ids" placeholder="1,2,3,4,5,6,7,8"></textarea>
<textarea class="jinsom-multiple-import-content" placeholder="视频内容#视频地址#视频封面地址#话题1,话题2,话题3"></textarea>
</div>
<div class="default">
备注：话题是英文逗号隔开，视频封面和话题可以为空，例如：视频内容#视频地址##
</div>
<div class="btn opacity" onclick="jinsom_multiple_import()"><i class="fa fa-superpowers"></i> 开始导入<span></span></div>
</div>