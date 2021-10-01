<?php 
require( '../../../../../../wp-load.php');
//$post_id=$_GET['post_id'];
$page_template=$_GET['page_template'];
if($page_template=='page/layout-bbs.php'){
require(get_template_directory().'/mobile/templates/page/bbs-show.php');
}else if($page_template=='page/layout-sns.php'){
require(get_template_directory().'/mobile/templates/page/sns.php');
}else if($page_template=='page/topic.php'){
require(get_template_directory().'/mobile/templates/page/topic-show.php');
}else if($page_template=='page/video.php'){
require(get_template_directory().'/mobile/templates/page/video-special.php');
}else if($page_template=='page/black-house.php'){
require(get_template_directory().'/mobile/templates/page/black-house.php');
}else if($page_template=='page/leaderboard.php'){
require(get_template_directory().'/mobile/templates/page/leaderboard.php');
}else if($page_template=='page/case.php'){
require(get_template_directory().'/mobile/templates/page/case.php');
}else if($page_template=='page/sign.php'){
require(get_template_directory().'/mobile/templates/page/sign.php');
}else if($page_template=='page/luck-draw.php'){
require(get_template_directory().'/mobile/templates/page/luck-draw.php');
}else if($page_template=='page/live.php'){
require(get_template_directory().'/mobile/templates/page/live.php');
}else if($page_template=='page/select.php'){
require(get_template_directory().'/mobile/templates/page/select.php');
}else if($page_template=='page/shop.php'){
require(get_template_directory().'/mobile/templates/page/shop.php');
}else if($page_template!=''){
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/page/'.$page_template.'/mobile.php')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/page/'.$page_template.'/mobile.php');//模块扩展
}else{
require(get_template_directory().'/mobile/templates/post/page.php');	
}
}else{
require(get_template_directory().'/mobile/templates/post/page.php');
}