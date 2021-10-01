<?php $title_color=get_post_meta($post_id,'title_color',true);//标题颜色 ?>
<div class="jinsom-post-words jinsom-post-<?php echo $post_id;?> jinsom-post-author-<?php echo $author_id;?>">

<?php require( get_template_directory() . '/mobile/templates/post/info.php' );?>
<div class="content">
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
<?php echo jinsom_mobile_bbs_post_type($post_id);?>
</h1>
<div class="desc">
<?php 
$excerp=mb_substr($single_content,0,80,'utf-8');
if($excerp!=''){
echo convert_smilies($excerp).'...';	
}
?>
</div>
<div class="jinsom-post-single-thum clear">
<?php echo jinsom_mobile_single_img(get_the_content(),$post_id);?>	
</div>
</a>
</div>

<?php require( get_template_directory() . '/mobile/templates/post/bar.php' );?>
</div>