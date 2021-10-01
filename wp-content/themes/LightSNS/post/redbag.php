<?php 
//红包列表
update_post_meta($post_id,'post_views',($post_views+1));//更新内容浏览量
require($require_url.'/post/info.php' );//引入头部信息
?>

<div class="jinsom-post-content">
<div class="jinsom-post-redbag-content" style="<?php echo jinsom_redbag_cover($post_id);?>">
<div class="avatarimg">
<a href="<?php echo jinsom_userlink($author_id);?>" target="_blank">
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
</a>
</div>
<div class="content"><?php echo get_the_content();?></div>

<?php 
if (is_user_logged_in()){?>
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
<div class="open" onclick="jinsom_pop_login_style()"><?php _e('开','jinsom');?></div>
<?php }?>


</div>
</div>


<?php 
require($require_url.'/post/bar.php' );//内容底部栏
jinsom_post_like_list($post_id);//喜欢列表

