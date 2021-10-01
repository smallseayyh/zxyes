<?php
/*
Template Name:空白页面(没有任何内容)
*/
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');	
}else{
while ( have_posts() ) : the_post();
the_content();
endwhile;
}