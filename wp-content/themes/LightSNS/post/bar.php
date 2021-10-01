<?php require($require_url.'/post/application.php' );//应用展示?>


<?php if($post_status=='publish'){?>
<?php 
$post_city=get_post_meta($post_id,'city',true);
if($post_city){
?>
<div class="jinsom-post-city"><i class="jinsom-icon jinsom-xiazai19"></i> <?php echo $post_city;?></div>
<?php }?>


<div class="jinsom-post-bar">

<?php if(jinsom_is_like_post($post_id,$user_id)){?>
<li class="jinsom-had-like" onclick='jinsom_like_posts(<?php echo $post_id;?>,this);'>
<i class="jinsom-icon jinsom-xihuan1"></i> <span><?php echo jinsom_count_post(0,$post_id);?></span>
</li>
<?php }else{?>
<li class="jinsom-no-like" onclick='jinsom_like_posts(<?php echo $post_id;?>,this);'>
<i class="jinsom-icon jinsom-xihuan2"></i> <span><?php echo jinsom_count_post(0,$post_id);?></span>
</li>
<?php }?>

<li class="comments" onclick="jinsom_comment_toggle(this)"><i class="jinsom-icon jinsom-pinglun2"></i> <span><?php comments_number('0','1','%'); ?></span></li>

<?php if(!is_page()&&$post_type!='redbag'){
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);?>
<li onclick='jinsom_reprint_form(<?php echo $post_id;?>);'>
<i class="jinsom-icon jinsom-zhuanzai"></i> <span><?php echo $reprint_times;?></span>
</li>
<?php }?>

<?php 
if($post_power==1){//付费 
$buy_times=(int)get_post_meta($post_id,'buy_times',true);
 ?>
<li><i class="jinsom-icon jinsom-goumai2"></i> <span><?php echo $buy_times;?></span></li>
<?php }?>


<li class="views"><a href="<?php echo $permalink;?>" target="_blank"><i class="jinsom-icon jinsom-liulan1"></i> <span><?php echo jinsom_views_show($post_views);?></span></a></li>


<?php if(!is_single()&&$post_type!='redbag'){
$tags = wp_get_post_tags($post_id);
if($tags){
echo '<li class="tag clear">#';
$i=1;
foreach ($tags as $tag) {
$tag_link = get_tag_link( $tag->term_id );
if($i<=3){
echo '<a href="'.$tag_link.'" title="'.$tag->name.'" class="opacity">'.$tag->name.'</a>';
}
$i++;
}
echo '</li>';
}
}
?>


</div>

<?php }?>