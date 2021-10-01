<?php
/*
Template Name:空白双栏页面
*/
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');	
}else{
get_header();
 ?>
<!-- 主内容 -->
<div class="jinsom-main-content single clear">
<?php require(get_template_directory().'/sidebar/sidebar-page.php');?>
<div class="jinsom-content-left"><!-- 左侧 -->
<?php while ( have_posts() ) : the_post();?>
<div class="jinsom-page-content">
<?php echo get_the_content();?>
</div>
<?php endwhile;?>
</div><!-- 左侧结束 -->
</div>
<?php get_footer(); 
}?>