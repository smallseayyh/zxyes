<?php 
//获取文章专题子菜单数据
require( '../../../../../wp-load.php' );
$data_type=$_POST['data_type'];
$data=$_POST['data'];
$number=$_POST['number'];
$style=$_POST['style'];

$data_str_arr=explode(",",$data);
if($data_type=='topic'){
$args = array(
'post_status' => 'publish',
'showposts' => $number,
'meta_key' => 'post_type',
'meta_value'=>'single',
// 'tag__in' =>$data_str_arr,
'ignore_sticky_posts'=>1
);
if($data){
$args['tag__in']=$data_str_arr;
}
}else{
$args = array(
'post_status' => 'publish',
'showposts' => $number,
// 'cat' =>$data_str_arr,
'post_parent'=>999999999,
'ignore_sticky_posts'=>1
);	
if($data){
$args['cat']=$data_str_arr;
}
}

$args['no_found_rows']=true;
query_posts($args);
if(have_posts()){

while (have_posts()):the_post();
$post_id=get_the_ID();
$author_id=get_the_author_meta('ID');

if($style=='one'){
$topics=wp_get_post_tags(get_the_ID());
$i=1;
$topic_html='';
if($topics){
foreach($topics as $topic){
if($i<=3){
$topic_html.='<a href="'.get_tag_link($topic->term_id).'" title="'.$topic->name.'" target="_blank">'.$topic->name.'</a>';
}
$i++;
}
}

echo '
<li>
<a href="'.get_the_permalink().'" target="_blank" class="bg">
<img src="'.jinsom_single_cover(get_the_content()).'">
</a>
<div class="bottom">
<div class="title">'.get_the_title().'</div>
<div class="topic">'.$topic_html.'</div>
<div class="info">
<a href="'.jinsom_userlink($author_id).'" target="_blank">
'.jinsom_avatar($author_id,'30',avatar_type($author_id)).'
<span class="name">'.get_user_meta($author_id,'nickname',true).'</span>
</a>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
</div>
</div>
</li>';


}else if($style=='two'){
$topics=wp_get_post_tags(get_the_ID());
$i=1;
$topic_html='';
if($topics){
foreach($topics as $topic){
if($i<=3){
$topic_html.='<a href="'.get_tag_link($topic->term_id).'" title="'.$topic->name.'" target="_blank">'.$topic->name.'</a>';
}
$i++;
}
}

echo '
<li>
<a href="'.get_the_permalink().'" target="_blank" class="bg">
<img src="'.jinsom_single_cover(get_the_content()).'">
</a>
<div class="bottom">
<div class="title">'.get_the_title().'</div>
<div class="info">
<a href="'.jinsom_userlink($author_id).'" target="_blank">
'.jinsom_avatar($author_id,'30',avatar_type($author_id)).'
<span class="name">'.get_user_meta($author_id,'nickname',true).'</span>
</a>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
</div>
</div>
</li>';


}else{

preg_match_all('/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is',get_the_content(),$result);
$single_img_number=count($result[1]);

if($single_img_number<=1){
$content=strip_tags(get_the_content());
$content = preg_replace("/\[file[^]]+\]/", "[附件]",$content);
$content = preg_replace("/\[video[^]]+\]/", "[视频]",$content);
$content = preg_replace("/\[music[^]]+\]/", "[音乐]",$content);
$content=preg_replace('/\s(?=\s)/','',$content);
$single_excerp_max_words = (int)jinsom_get_option('jinsom_publish_single_excerp_max_words');//文章摘要字数
}

if($single_img_number>=2){

echo '<li class="b">
<div class="title">
<a href="'.get_the_permalink().'" target="_blank">'.get_the_title().'</a>
</div>
<div class="cover">'.jinsom_get_single_img(get_the_content(),$post_id).'</div>
<div class="info">
<a href="'.jinsom_userlink($author_id).'" target="_blank">
'.jinsom_avatar($author_id,'30',avatar_type($author_id)).'
<span class="name">'.get_user_meta($author_id,'nickname',true).'</span>
</a>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
</div>
</li>';  


}else if($single_img_number==1){//只有一图

echo '<li class="a">
<a href="'.get_the_permalink().'" target="_blank" class="bg">
<img src="'.jinsom_single_cover(get_the_content()).'">
</a>
<div class="right">
<div class="title"><a href="'.get_the_permalink().'" target="_blank">'.get_the_title().'</a></div>
<div class="desc">
<a href="'.get_the_permalink().'" target="_blank">'.convert_smilies(mb_substr($content,0,$single_excerp_max_words,'utf-8')).'...</a>
</div>
<div class="info">
<a href="'.jinsom_userlink($author_id).'" target="_blank">
'.jinsom_avatar($author_id,'30',avatar_type($author_id)).'
<span class="name">'.get_user_meta($author_id,'nickname',true).'</span>
</a>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
</div>
</div>
</li>';


}else{

echo '<li class="c">
<div class="title">
<a href="'.get_the_permalink().'" target="_blank">'.get_the_title().'</a>
</div>
<div class="desc"><a href="'.get_the_permalink().'" target="_blank">'.convert_smilies(mb_substr($content,0,$single_excerp_max_words,'utf-8')).'...</a></div>
<div class="info">
<a href="'.jinsom_userlink($author_id).'" target="_blank">
'.jinsom_avatar($author_id,'30',avatar_type($author_id)).'
<span class="name">'.get_user_meta($author_id,'nickname',true).'</span>
</a>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
</div>
</li>';  


}




}





endwhile;
}else{
echo jinsom_empty();
}