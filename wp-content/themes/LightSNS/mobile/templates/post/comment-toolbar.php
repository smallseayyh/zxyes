<?php 
$credit_name=jinsom_get_option('jinsom_credit_name');
if($post_power!=3||jinsom_is_admin($user_id)||$user_id==$author_id){?>
<div class="toolbar">
<div class="toolbar-inner">
<div class="jinsom-post-words-tool">
<?php if(is_user_logged_in()){?>
<?php if(comments_open($post_id)||(!comments_open($post_id)&&(jinsom_is_admin($user_id)||$user_id==$author_id))){?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/comment.php?post_id=<?php echo $post_id;?>" class="link">
<i class="jinsom-icon jinsom-fabu7"></i> <?php _e('写评论','jinsom');?><?php if($comment_post_credit<0){echo '（'.$comment_post_credit.$credit_name.'）';}?>
</a>
<?php }else{?>
<a><?php _e('该内容已关闭评论','jinsom');?></a>	
<?php }}else{?>
<a onclick="myApp.loginScreen();"><i class="jinsom-icon jinsom-fabu7"></i> <?php _e('写评论','jinsom');?><?php if($comment_post_credit<0){echo '（'.$comment_post_credit.$credit_name.'）';}?></a>
<?php }?>

<span>
<a class="link comment"><i class="jinsom-icon jinsom-pinglun2 comment"><?php if($comments_number){?><m><?php echo $comments_number;?></m><?php }?></i></a>

<?php if(jinsom_is_collect($user_id,'post',$post_id,'')){?>
<a class="link collect collect-post-<?php echo $post_id;?>" onclick="jinsom_collect(<?php echo $post_id;?>,'post',this)"><i class="jinsom-icon jinsom-shoucang"></i></a>
<?php }else{?>
<a class="link collect collect-post-<?php echo $post_id;?>" onclick="jinsom_collect(<?php echo $post_id;?>,'post',this)"><i class="jinsom-icon jinsom-shoucang1"></i></a>
<?php }?>

<?php if($user_id!=$author_id){?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/reward.php?post_id=<?php echo $post_id;?>&type=post" class="link reward">
<i class="jinsom-icon jinsom-hongbao1"></i></a>

<?php if(jinsom_get_option('jinsom_gift_on_off')&&jinsom_get_option('jinsom_gift_add')){?>
<a class="link gift" onclick="jinsom_send_gift_page(<?php echo $author_id;?>,<?php echo $post_id;?>)"><i class="jinsom-icon jinsom-liwu2"></i></a>
<?php }?>

<?php }?>
<a class="link" onclick="jinsom_post_more_form(<?php echo $post_id;?>,0,'single')"><i class="jinsom-icon jinsom-zhuanfa"></i></a>
</span>

</div>
</div>
</div>
<?php }?>