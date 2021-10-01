<?php
//文章=== 2张图或以上
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
$author_id=get_the_author_meta('ID');
$post_id=get_the_ID();
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);
$post_img=get_post_meta($post_id,'post_img',true);
$post_power=get_post_meta($post_id,'post_power',true);
//浏览量
$post_views=(int)get_post_meta($post_id,'post_views',true);
?>
<div class="jinsom-post-single more jinsom-post-<?php echo $post_id;?> jinsom-post-author-<?php echo $author_id;?>">
<a href="<?php echo jinsom_mobile_post_url($post_id);?>" class="link">
<h1 <?php if($title_color){echo 'style="color:'.$title_color.'"';}?>>
<?php the_title();?>
<?php 
if(isset($_GET['author_id'])||isset($_POST['author_id'])){
if(get_user_meta($author_id,'sticky',true)==$post_id){echo '<span class="sticky-member"></span>';}
}else{
if($sticky_post){echo '<span class="sticky"></span>';}	
}
if($commend_post){echo '<span class="commend"></span>';}
?>
</h1>
<div class="jinsom-post-single-content">
<div class="jinsom-post-single-thum clear">
<?php echo jinsom_mobile_single_img(get_the_content(),$post_id);?>	
</div>
</div>
</a>
<?php require( get_template_directory() . '/mobile/templates/post/bar.php' );?>
</div>