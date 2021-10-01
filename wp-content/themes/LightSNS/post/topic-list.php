<!-- 话题 -->
<?php if(is_single()){?>
<div class="jinsom-single-topic-list clear">
<?php 
$tags=wp_get_post_tags($post_id);
$i=1;
foreach($tags as $tag){
$tag_link=get_tag_link($tag->term_id);
if($i<=10){
echo '<a href="'.$tag_link.'" title="'.$tag->name.'" class="opacity">'.jinsom_get_bbs_avatar($tag->term_id,1).'<span>'.$tag->name.'</span></a>';
}
$i++;
}
?>
</div>
<?php }?>