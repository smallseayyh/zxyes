<!-- 商品 -->
<div class="jinsom-bbs-box grid-style">
<?php require( get_template_directory() . '/post/bbs-list-search.php' );?>
</div>

<div class="jinsom-bbs-list-box jinsom-bbs-list-3 jinsom-bbs-list-5 clear" data-no-instant id="jinsom-bbs-post-<?php echo $post_id;?>">
<?php 
query_posts($args);
if (have_posts()){
while ( have_posts() ) : the_post();
$post_id=get_the_ID();
$author_id=get_the_author_meta('ID');
$post_views=(int)get_post_meta($post_id,'post_views',true);
$post_from=get_post_meta($post_id,'post_from',true);
$post_type=get_post_meta($post_id,'post_type',true);
$buy_times=(int)get_post_meta($post_id,'buy_times',true);
$title_color=get_post_meta($post_id,'title_color',true);//标题颜色
?>
<li>
<div class="mark">
<?php echo jinsom_bbs_post_type($post_id);?>	
</div>
<a href="<?php the_permalink(); ?>" <?php if($post_target){echo 'target="_blank"';}?>>
<?php echo jinsom_get_bbs_img(get_the_content(),$post_id,1);?>
<h2 <?php if($title_color){echo 'style="color:'.$title_color.'"';}?>><?php the_title();?></h2>
</a>
<div class="info clear">
<?php 
echo '<span><m class="jinsom-icon jinsom-jinbi"></m>'.(int)get_post_meta($post_id,'post_price',true).'</span><span><i class="jinsom-icon jinsom-goumai2"></i> '.$buy_times.'</span>';?>	
</div>
</li>

<?php 
endwhile;

echo '<div class="jinsom-more-posts default" data="2" onclick=\'jinsom_ajax_bbs(this,"'.$type.'")\'>加载更多</div>';

}else{
echo jinsom_empty();
}?>


</div>