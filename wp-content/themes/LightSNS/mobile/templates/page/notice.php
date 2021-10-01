<?php 
//内页消息提醒
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$theme_url=get_template_directory_uri();
?>
<div data-page="notice" class="page no-tabbar">

<div class="navbar  jinsom-notice-navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center"><?php _e('消息','jinsom');?> <i class="jinsom-icon jinsom-qingchu" onclick="jinsom_clear_notice()"></i></div>
<div class="right">
<a href="#" class="link icon-only">
<!-- <i class="jinsom-icon jinsom-guanzhu" style="font-size: 8vw;"></i> -->
</a>
</div>
<!-- 聊天tab -->
<div class="subnavbar">
<div class="jinsom-chat-tab">
<a href="#jinsom-chat-tab-recently" class="link tab-link jinsom-tab-button active"><?php _e('最近','jinsom');?></a>
<a href="#jinsom-chat-tab-follow" class="link tab-link jinsom-tab-button"><?php _e('关注','jinsom');?></a>
<?php if(jinsom_get_option('jinsom_im_group_on_off')){?>
<a href="#jinsom-chat-tab-group" class="link tab-link jinsom-tab-button"><?php _e('群组','jinsom');?></a>
<?php }?>
</div>
</div>

</div>
</div>



<div class="jinsom-notice-page-content page-content pull-to-refresh-content hide-navbar-on-scroll"><!-- 内容区 -->

<!-- 下拉刷新 -->
<div class="pull-to-refresh-layer">
<div class="pull-to-refresh-arrow"><i class="jinsom-icon jinsom-xialashuaxin"></i></div>
</div>
<div class="jinsom-chat tabs">
<div class="jinsom-load-post"><div class="jinsom-loading-post"><i></i><i></i><i></i><i></i><i></i></div></div>
</div><!-- 加载异步容器 -->
</div>


</div>