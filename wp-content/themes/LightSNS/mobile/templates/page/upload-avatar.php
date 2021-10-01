<?php 
//上传头像
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="upload-avatar" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">上传头像</div>
<div class="right">
<a href="#" class="link icon-only" id="jinsom-clip-avatar">确定</a>
</div>
</div>
</div>

<div class="page-content">
<div id="jinsom-avatar-demo"></div>
<div style="text-align: center;font-size: 4vw;color: #999;margin-top: 5vw;">可以拖动扩大或缩小进行裁剪</div>
</div>

</div>
</div>        