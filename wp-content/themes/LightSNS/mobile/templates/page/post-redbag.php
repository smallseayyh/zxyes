<?php 
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;

$more_type='single';//用于区分当前页面是列表页面还是内页
$post_id=$_GET['post_id'];
$post_data = get_post($post_id, ARRAY_A);
$author_id=$post_data['post_author'];
$content=$post_data['post_content'];
$post_date=$post_data['post_date'];


//更新内容浏览量
$post_views=(int)get_post_meta($post_id,'post_views',true);
update_post_meta($post_id,'post_views',($post_views+1));

$comments_number=get_comments_number($post_id);
$comment_post_credit = jinsom_get_option('jinsom_comment_post_credit');
?>
<div data-page="post-single" class="page no-tabbar post-redbag">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">
<a href="<?php echo jinsom_mobile_author_url($author_id);?>" class="link icon-only">
<span class="avatarimg">
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
<?php echo jinsom_verify($author_id);?>
</span>
<span class="name"><?php echo get_user_meta($author_id,'nickname',true);?></span>
</a>
</div>
<div class="right">
<?php if(is_user_logged_in()){?>
<a href="#" class="link icon-only">
<?php if($user_id!=$author_id){echo jinsom_mobile_follow_button($user_id,$author_id);}?>
</a>
<?php }else{?>
<a href="#" class="link icon-only jinsom-login-avatar open-login-screen">
<?php if($user_id!=$author_id){echo jinsom_mobile_follow_button($user_id,$author_id);}?>
</a>
<?php }?>
</div>
</div>
</div>

<?php require($require_url.'/mobile/templates/post/comment-toolbar.php');?>

<div class="page-content keep-toolbar-on-scroll infinite-scroll jinsom-page-single-content jinsom-page-single-content-<?php echo $post_id;?>" data-distance="800">

<div class="jinsom-single jinsom-post-<?php echo $post_id;?>">

<div class="jinsom-single-author-info">
<span class="name"><?php _e('浏览','jinsom');?> <?php echo $post_views;?></span>
<span class="dot">•</span>
<span class="from"><?php echo jinsom_post_from($post_id);?></span>
<span class="dot">•</span>
<span class="time"><?php echo date('Y-m-d H:i',strtotime($post_date));?></span>
</div>

<div class="jinsom-post-redbag-content" style="<?php echo jinsom_redbag_cover($post_id);?>">
<div class="avatarimg">
<a href="<?php echo jinsom_mobile_author_url($author_id);?>" class="link">
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
</a>
</div>
<div class="content"><?php echo $content;?></div>

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

<?php 
require( get_template_directory() . '/mobile/templates/post/bar.php' );
jinsom_mobile_post_like_list($post_id);
?>

</div>

<?php require( get_template_directory() . '/mobile/templates/post/comment.php' );?>


</div>
</div>        