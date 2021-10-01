<?php

if(!wp_is_mobile()){
$enabled=$jinsom_bbs_header['list']['enabled'];
if(key($enabled)==$x){$class='layui-show';}else{$class='';}
query_posts($args);
echo '<div class="layui-tab-item '.$class.' post">';
if(have_posts()){
while (have_posts()):the_post();
$post_id=get_the_ID();
if(get_the_time('Y-m-d')==date('Y-m-d',time())){
$time='<font style="color:#f00;">'.get_the_time('m-d').'</font>';	
}else{
$time=get_the_time('m-d');		
}
$img='';
if(preg_match("/<img.*>/",get_the_content())){ 
$img= '<span class="jinsom-bbs-post-type-img"><i class="jinsom-icon jinsom-tupian2"></i></span>';
}
$title_color=get_post_meta($post_id,'title_color',true);//标题颜色
if($title_color){$title_color='style="color:'.$title_color.'"';}else{$title_color='';}
echo '
<li>
<a href="'.get_the_permalink().'" target="_blank" '.$title_color.'>'.get_the_title().'</a>
'.$img.'
<span class="mark">
'.jinsom_bbs_post_type($post_id).'
</span>
<m>'.$time.'</m>
</li>';
endwhile;
wp_reset_query();
}else{
echo jinsom_empty();	
}
echo '</div>';

}else{

if(key($enabled)==$x){$class='on';}else{$class='';}
query_posts($args);
echo '<ul class="'.$class.'">';
if (have_posts()) {
while ( have_posts() ) : the_post();
$post_id=get_the_ID();
$author_id=get_the_author_meta('ID');
$title_color=get_post_meta($post_id,'title_color',true);//标题颜色
if($title_color){$title_color='style="color:'.$title_color.'"';}else{$title_color='';}
echo '
<div class="jinsom-bbs-list-default">
<a href="'.jinsom_mobile_post_url($post_id).'" class="link">
<div class="avatarimg">'.jinsom_avatar($author_id,'40',avatar_type($author_id)).jinsom_verify($author_id).'</div>
<div class="info">
<h2 '.$title_color.'>'.get_the_title().'</h2>
<div class="user">
<span class="name">'.jinsom_nickname($author_id).'</span>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
<span class="number"><i class="jinsom-icon jinsom-liaotian"></i> '.get_comments_number($post_id).'</span>
</div>
</div>
</a>
</div>
';
endwhile;
wp_reset_query();
}else{
echo jinsom_empty();	
}
echo '</ul>';
}