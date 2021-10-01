<?php
//帖子
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
$author_id=get_the_author_meta('ID');
$post_id=get_the_ID();
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);
$post_img=get_post_meta($post_id,'post_img',true);
$post_power=get_post_meta($post_id,'post_power',true);

$single_content=strip_tags(get_the_content());
$single_content = preg_replace("/\[file[^]]+\]/", "[附件]", $single_content);
$single_content = preg_replace("/\[video[^]]+\]/", "[视频]", $single_content);
$single_content = preg_replace("/\[music[^]]+\]/", "[音乐]", $single_content);
// $single_content = str_replace("&nbsp;","",$single_content);//移除空格
$single_content=preg_replace('/\s(?=\s)/','',$single_content);

$commend_post=get_post_meta($post_id,'jinsom_commend',true);
$post_views=(int)get_post_meta($post_id,'post_views',true);
$is_bbs_post=is_bbs_post($post_id);


//论坛列表样式
$bbs_list_style=(int)get_term_meta($bbs_id,'bbs_list_style',true);

if($load_type=='list'){
$sticky_post=is_sticky();
require( get_template_directory() . '/mobile/templates/post/bbs-list/bbs-list-2.php');//图文
// require(get_template_directory().'/mobile/templates/post/single.php');
}else{
if($bbs_list_style==1){
require( get_template_directory() . '/mobile/templates/post/bbs-list/bbs-list-2.php');//图文
}else if($bbs_list_style==2){
require( get_template_directory() . '/mobile/templates/post/bbs-list/bbs-list-3.php');//网格
}else if($bbs_list_style==3){
require( get_template_directory() . '/mobile/templates/post/bbs-list/bbs-list-4.php');//瀑布流
}else if($bbs_list_style==4){
require( get_template_directory() . '/mobile/templates/post/bbs-list/bbs-list-5.php');//商品
}else{
require( get_template_directory() . '/mobile/templates/post/bbs-list/bbs-list-1.php');//简约
}
}