<?php 
$content = preg_replace("/\[file[^]]+\]/", "[附件]",$content);
$content = preg_replace("/\[video[^]]+\]/", "[视频]",$content);
$content = preg_replace("/\[music[^]]+\]/", "[音乐]",$content);
$content=preg_replace('/\s(?=\s)/','',$content);
$single_excerp_max_words = (int)jinsom_get_option('jinsom_publish_single_excerp_max_words');//文章摘要字数

//实时计算
preg_match_all('/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is',$content_source,$result);
$single_img_number=count($result[1]);
$title_color=get_post_meta($post_id,'title_color',true);//标题颜色

if($single_img_number>=2){
require($require_url.'/post/single-2.php');
}else if($single_img_number==1){
require($require_url.'/post/single-1.php');
}else{
require($require_url.'/post/single-3.php');//无图
}
