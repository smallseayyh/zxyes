<?php
/*
Template Name:空白单栏页面
*/
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');	
}else{
get_header();
 ?>
<!-- 主内容 -->
<div class="jinsom-main-content single clear">
<div class="jinsom-content-left full"><!-- 左侧 -->
<?php while ( have_posts() ) : the_post();?>
<div class="jinsom-page-content">
<?php echo get_the_content();?>
</div>
<?php endwhile;?>
</div><!-- 左侧结束 -->
</div>
<?php get_footer();
}
?>