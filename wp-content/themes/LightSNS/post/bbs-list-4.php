<!-- 瀑布流 -->
<div class="jinsom-bbs-box grid-style">
<?php require( get_template_directory() . '/post/bbs-list-search.php' );?>
</div>

<div class="jinsom-bbs-list-box jinsom-bbs-list-3 jinsom-bbs-list-4 clear" id="jinsom-bbs-list-4"  data-no-instant id="jinsom-bbs-post-<?php echo $post_id;?>">
<?php 
query_posts($args);
if (have_posts()){
while ( have_posts() ) : the_post();
$post_id=get_the_ID();
$author_id=get_the_author_meta('ID');
$post_views=(int)get_post_meta($post_id,'post_views',true);
$post_from=get_post_meta($post_id,'post_from',true);
$post_type=get_post_meta($post_id,'post_type',true);
$author_name=jinsom_nickname_link($author_id);
$title_color=get_post_meta($post_id,'title_color',true);//标题颜色
?>
<li class="grid">
<div class="mark">
<?php echo jinsom_bbs_post_type($post_id);?>	
</div>
<a href="<?php the_permalink(); ?>" <?php if($post_target){echo 'target="_blank"';}?>>
<?php echo jinsom_get_bbs_img(get_the_content(),$post_id,2);?>
<h2 <?php if($title_color){echo 'style="color:'.$title_color.'"';}?>><?php the_title();?></h2>
</a>
<div class="info clear">
<span><a href="<?php echo jinsom_userlink($author_id);?>" target="_blank"><?php echo jinsom_avatar($author_id,'40',avatar_type($author_id) );?> <m><?php echo jinsom_nickname($author_id);?></m></a></span>		
<?php if(jinsom_is_like_post($post_id,$user_id)){?>
<span class="jinsom-had-like" onclick='jinsom_like_posts(<?php echo $post_id;?>,this);'>
<i class="jinsom-icon jinsom-xihuan1"></i> <span><?php echo jinsom_count_post(0,$post_id);?></span>
</span>
<?php }else{?>
<span class="jinsom-no-like" onclick='jinsom_like_posts(<?php echo $post_id;?>,this);'>
<i class="jinsom-icon jinsom-xihuan2"></i> <span><?php echo jinsom_count_post(0,$post_id);?></span>
</span>
<?php }?>
</div>
</li>

<?php 
endwhile;

echo '<div class="jinsom-more-posts default" data="2" onclick=\'jinsom_ajax_bbs(this,"'.$type.'")\'>加载更多</div>';

}else{
echo jinsom_empty();
}?>




</div>