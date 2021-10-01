<?php 
$post_id=get_the_ID();
//实时计算
preg_match_all('/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is',get_the_content(),$result);
$single_img_number=count($result[1]);
$title_color=get_post_meta($post_id,'title_color',true);//标题颜色

if($single_img_number>=2){
require( get_template_directory().'/mobile/templates/post/single-2.php' );
}else if($single_img_number==1){
require( get_template_directory().'/mobile/templates/post/single-1.php' );
}else{
require( get_template_directory().'/mobile/templates/post/single-3.php' );	//无图
}
