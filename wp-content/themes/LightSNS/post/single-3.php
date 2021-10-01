<?php 
//无图
require($require_url.'/post/info.php' );
?>
<?php if(!is_single()){?>
<h2 class="single">
<a href="<?php echo $permalink; ?>" target="_blank" <?php if($title_color){echo 'style="color:'.$title_color.'"';}?>><?php echo $title; ?></a>
<?php 
if(isset($_GET['author_id'])||isset($_POST['author_id'])||$is_author){
if(get_user_meta($author_id,'sticky',true)==$post_id){echo '<span class="jinsom-mark jinsom-member-top"></span>';}
}else{
if($sticky_post){echo '<span class="jinsom-mark jinsom-top"></span>';}	
}
?>
<?php if($commend_post){echo '<span class="jinsom-mark jinsom-commend-icon"></span>';}?>
</h2>

<div class="jinsom-post-single-content b">

<div class="jinsom-post-single-excerp">
<a href="<?php echo $permalink; ?>" target="_blank">
<?php 
$excerp=mb_substr(strip_tags($content),0,$single_excerp_max_words,'utf-8');
if($excerp!=''){
echo convert_smilies($excerp).'...';	
}
?>
</a>
</div>


<?php require( get_template_directory() . '/post/single-list-bar.php' );?>

</div>

<?php }else{
//内容页面
require($require_url.'/post/single-content.php' );
}