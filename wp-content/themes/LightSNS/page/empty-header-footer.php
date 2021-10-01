<?php
/*
Template Name:空白页面(只有头部和底部)
*/
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');	
}else{
get_header();
while ( have_posts() ) : the_post();
the_content();
endwhile;
get_footer(); 
}