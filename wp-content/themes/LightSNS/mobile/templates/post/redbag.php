<?php
//红包
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
$author_id=get_the_author_meta('ID');
$post_id=get_the_ID();
?>
<div class="jinsom-post-words jinsom-post-<?php echo $post_id;?> jinsom-post-author-<?php echo $author_id;?>">

<?php require( get_template_directory() . '/mobile/templates/post/info.php' );?>
<div class="content">
<div class="jinsom-post-redbag-content" style="<?php echo jinsom_redbag_cover($post_id);?>">
<div class="avatarimg">
<a href="<?php echo jinsom_mobile_author_url($author_id);?>" class="link">
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
</a>
</div>
<div class="content"><?php echo get_the_content();?></div>

<?php if (is_user_logged_in()){?>
<?php if(!jinsom_is_no_redbag($post_id)){?>
<?php if(jinsom_is_redbag($user_id,$post_id)){?>
<div class="had" onclick="jinsom_get_redbag(<?php echo $post_id;?>,this)"><?php _e('已领取','jinsom');?></div>	
<?php }else{?>
<div class="open" onclick="jinsom_get_redbag(<?php echo $post_id;?>,this)"><?php _e('开','jinsom');?></div>
<?php }?>
<?php }else{?>
<div class="had" onclick="jinsom_get_redbag(<?php echo $post_id;?>,this)"><?php _e('已领完','jinsom');?></div>
<?php }?>
<?php }else{?>
<div class="open open-login-screen"><?php _e('开','jinsom');?></div>
<?php }?>

</div>
</div>




<?php require( get_template_directory() . '/mobile/templates/post/bar.php' );?>
</div>