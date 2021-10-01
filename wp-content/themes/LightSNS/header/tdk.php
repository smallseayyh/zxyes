<?php 
$user_id=$current_user->ID;
$site_name=jinsom_get_option('jinsom_site_name');//网站名称
$jinsom_keyword = jinsom_get_option('jinsom_keyword');
$jinsom_description = jinsom_get_option('jinsom_description');
$jinsom_title_index = jinsom_get_option('jinsom_title_index');
if(empty($jinsom_title_index)){
$jinsom_title_index='LightSNS';
}
$jinsom_title_user = jinsom_get_option('jinsom_title_user');
$jinsom_title_post = jinsom_get_option('jinsom_title_post');
$jinsom_title_bbs_post = jinsom_get_option('jinsom_title_bbs_post');
$jinsom_title_search = jinsom_get_option('jinsom_title_search');
$jinsom_title_single_publish = jinsom_get_option('jinsom_title_single_publish');

if(is_category()){//论坛页面
$bbs_id = get_query_var('cat');
}else if(is_tag()){//话题页面
$topic_name = single_tag_title('', false);
$topic_data=get_term_by('name',$topic_name,'post_tag');
$topic_id=$topic_data->term_id;
$topic_title=get_term_meta($topic_id,'bbs_seo_title',true);
$topic_desc=get_term_meta($topic_id,'bbs_seo_desc',true);
$topic_keywords=get_term_meta($topic_id,'bbs_seo_keywords',true);
}else if(is_single()||is_page()){//文章或页面
$post_id=get_the_ID();
if(is_page()){
$page_option=get_post_meta($post_id,'page_option',true);
}
}else if(is_author()){//个人主页
global $wp_query;
$curauth = $wp_query->get_queried_object();
$author_id=$curauth->ID;
}
// if(1){exit();}

echo'<title>';

if(is_author()){//个人主页

echo do_shortcode($jinsom_title_user);

}else if(is_home()||is_front_page()){//首页
echo $jinsom_title_index;
}elseif(is_category()){//论坛板块
echo do_shortcode(get_term_meta($bbs_id,'bbs_seo_title',true));

}elseif(is_single()){//文章内页

if(is_bbs_post($post_id)){//帖子内容页面
echo do_shortcode($jinsom_title_bbs_post);
}else{//动态页面
if(get_post_meta($post_id,'post_power',true)!=3){
echo do_shortcode($jinsom_title_post);
}else{
echo '私密内容';	
}
}

}else if(is_page()&&!is_front_page()){//普通页面

if($page_option){
echo do_shortcode($page_option['jinsom_page_seo_title']);
}else{
echo get_the_title().'-'.$jinsom_title_index;	
}

}else if(is_tag()){//话题页面

if(empty($topic_title)){
$topic_title=do_shortcode(jinsom_get_option('jinsom_title_topic'));
}
echo $topic_title;

}else if(is_search()){//搜索页面
echo do_shortcode($jinsom_title_search);
}else{
echo $jinsom_title_index;
}
echo'</title>';


if(is_category()){
echo '
<meta name="keywords" content="'.do_shortcode(get_term_meta($bbs_id,'bbs_seo_keywords',true)).'" />
<meta name="description" content="'.do_shortcode(get_term_meta($bbs_id,'bbs_seo_desc',true)).'" />';
}else if(is_single()){//动态内页、帖子、页面、
$content=htmlspecialchars(strip_tags(jinsom_get_post_content($post_id)));
$content = str_replace(array("\r\n","\r","\n","&amp;nbsp;"),"",$content); 
$content = str_replace(" ","",$content);
$content=mb_substr($content,0,100,'utf-8');

if(get_post_meta($post_id,'post_power',true)!=3){
$gettags = get_the_tags($post_id);
if ($gettags) {
foreach ($gettags as $tag) {
$posttag[] = $tag->name;
}
$tags = implode(',',$posttag);
}else{
if(is_bbs_post($post_id)){//帖子
$category = get_the_category();
$tags=$category[0]->cat_name;
}else{
$tags =get_the_title();	
}
}
echo '
<meta name="keywords" content="'.$tags.'" />
<meta name="description" content="'.$content.'" />';
}
//}
}else if(is_author()){//个人主页
$description=$curauth->description;
$user_desc=$description?$description:do_shortcode(jinsom_get_option('jinsom_author_page_description'));
echo '
<meta name="keywords" content="'.get_user_meta($author_id,'nickname',true).'" />
<meta name="description" content="'.$user_desc.'" />';	
}else if(is_tag()){//话题页面
if(empty($topic_keywords)){$topic_keywords=single_tag_title('', false);}
if(empty($topic_desc)){
$desc=get_term_meta($topic_id,'desc',true);
if($desc==''){
$topic_desc=do_shortcode(jinsom_get_option('jinsom_topic_page_description'));	
}else{
$topic_desc=$desc;
}	
}
echo '
<meta name="keywords" content="'.$topic_keywords.'" />
<meta name="description" content="'.$topic_desc.'" />';	
}else if(is_page()){
if($page_option){
echo '
<meta name="keywords" content="'.do_shortcode($page_option['jinsom_page_seo_keyword']).'" />
<meta name="description" content="'.do_shortcode($page_option['jinsom_page_seo_description']).'"/>';
}
}else{//其他页面 包括首页
echo '
<meta name="keywords" content="'.$jinsom_keyword.'" />
<meta name="description" content="'.$jinsom_description.'" />';	
}

?>

