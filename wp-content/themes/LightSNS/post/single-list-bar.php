<!-- 文章列表工具栏 -->
<?php require($require_url.'/post/application.php' );//应用展示?>

<div class="jinsom-post-single-bar single">
<li class="author">
<a href="<?php echo jinsom_userlink($author_id);?>" target="_blank">
<?php echo jinsom_avatar($author_id, '40' , avatar_type($author_id) );?>
<?php echo jinsom_verify($author_id);?>
</a>
<?php echo jinsom_nickname_link($author_id);?>
</li>

<?php if(jinsom_is_like_post($post_id,$user_id)){?>
<li class="jinsom-had-like" onclick='jinsom_like_posts(<?php echo $post_id;?>,this);'>
<i class="jinsom-icon jinsom-xihuan1"></i> <span><?php echo jinsom_count_post(0,$post_id);?></span>
</li>
<?php }else{?>
<li class="jinsom-no-like" onclick='jinsom_like_posts(<?php echo $post_id;?>,this);'>
<i class="jinsom-icon jinsom-xihuan2"></i> <span><?php echo jinsom_count_post(0,$post_id);?></span>
</li>
<?php }?>

<li onclick="jinsom_comment_toggle(this)"><a href="<?php echo $permalink;?>" target="_blank"><i class="jinsom-icon jinsom-pinglun2"></i> <span><?php comments_number('0','1','%'); ?></span></a></li>


<?php 
if(!$is_bbs_post){
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);?>
<li onclick='jinsom_reprint_form(<?php echo $post_id;?>);'>
<i class="jinsom-icon jinsom-zhuanzai"></i> <span><?php echo $reprint_times;?></span>
</li>
<?php }?>


<li><a href="<?php echo $permalink;?>" target="_blank"><i class="jinsom-icon jinsom-liulan1"></i> <span><?php echo jinsom_views_show($post_views);?></span></a></li>


<?php if($is_bbs_post){?>
<li><i class="jinsom-icon jinsom-fenlei1"></i> <span><a href="<?php echo get_category_link($child_cat_id);?>" target="_blank"><?php echo $child_name;?></a></span></li>
<?php }?>

<li class="right"><?php echo $time;?> <?php echo jinsom_post_from($post_id);?></li>



</div>