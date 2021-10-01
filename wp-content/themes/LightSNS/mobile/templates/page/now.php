<?php 
//实时动态
require( '../../../../../../wp-load.php');
?>
<div data-page="now" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('实时动态','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-now-content infinite-scroll" data-distance="200">
<div class="jinsom-chat-user-list notice list-block">
<?php require(get_template_directory().'/mobile/module/post/now.php' );?>
</div>
</div>
</div>        
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>