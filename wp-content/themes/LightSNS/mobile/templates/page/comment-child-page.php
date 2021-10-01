<?php 
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$credit_name=jinsom_get_option('jinsom_credit_name');
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$author_id=jinsom_get_user_id_post($post_id);
$bbs_id=$_GET['bbs_id'];
$comment_id=$_GET['comment_id'];
$answer_adopt=(int)get_post_meta($post_id,'answer_adopt',true);
$parent_comment=get_comment($comment_id);
$parent_comment_id=$parent_comment->comment_ID;
$parent_user_id=$parent_comment->user_id;
$comment_nickname=get_user_meta($parent_user_id,'nickname',true);
$comment_from=get_comment_meta($parent_comment_id,'from',true);
if($comment_from=='mobile'){
$form=__('手机端','jinsom');
}else{
$form=__('电脑端','jinsom');	
}

$args_floor = array(
'number' => 100,
'parent'=>$comment_id,
'status' =>'approve',
'orderby' => 'comment_ID',
'order' => 'ASC',
);
$comments = get_comments($args_floor);
$child_comment_num=jinsom_get_child_comments_num($comment_id);

$credit_reply_number=get_term_meta($bbs_id,'bbs_credit_reply_number',true);


$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);

if (is_user_logged_in()) {
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}

$comments_number=get_comments_number($post_id);
?>
<div data-page="comment-bbs-floor-page" class="page no-tabbar comment-post">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('楼层回帖','jinsom');?></div>
<div class="right">
<a class="link icon-only"></a>
</div>
</div>
</div>

<div class="toolbar">
<div class="toolbar-inner">
<div class="jinsom-post-words-tool">
<?php if(is_user_logged_in()){?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/comment-bbs-floor.php?post_id=<?php echo $post_id;?>&bbs_id=<?php echo $bbs_id;?>&comment_id=<?php echo $comment_id;?>&bbs_id=<?php echo $bbs_id;?>" class="link">
<i class="jinsom-icon jinsom-fabu7"></i> <?php _e('写评论','jinsom');?><?php if($credit_reply_number<0){echo '（'.$credit_reply_number.$credit_name.'）';}?></a>
<?php }else{?>
<a onclick="myApp.loginScreen();">
<i class="jinsom-icon jinsom-fabu7"></i> <?php _e('写评论','jinsom');?><?php if($credit_reply_number<0){echo '（'.$credit_reply_number.$credit_name.'）';}?></a>
<?php }?>

<span>
<a class="link"><i class="jinsom-icon jinsom-pinglun2 comment"><?php if($comments_number){?><m><?php echo $comments_number;?></m><?php }?></i></a>

<?php if(jinsom_is_collect($user_id,'post',$post_id,'')){?>
<a class="link collect collect-post-<?php echo $post_id;?>" onclick="jinsom_collect(<?php echo $post_id;?>,'post',this)"><i class="jinsom-icon jinsom-shoucang"></i></a>
<?php }else{?>
<a class="link collect collect-post-<?php echo $post_id;?>" onclick="jinsom_collect(<?php echo $post_id;?>,'post',this)"><i class="jinsom-icon jinsom-shoucang1"></i></a>
<?php }?>

<?php if($user_id!=$author_id){?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/reward.php?post_id=<?php echo $post_id;?>&type=post" class="link reward">
<i class="jinsom-icon jinsom-hongbao1"></i></a>
<a class="link gift" onclick="jinsom_send_gift_page(<?php echo $author_id;?>,<?php echo $post_id;?>)"><i class="jinsom-icon jinsom-liwu2"></i></a>
<?php }?>
<a class="link" onclick="jinsom_post_more_form(<?php echo $post_id;?>,0,'single')"><i class="jinsom-icon jinsom-zhuanfa"></i></a>
</span>

</div>
</div>
</div>

<div class="page-content" style="background: #fff;" id="jinsom-comment-child-page">

<div class="jinsom-single-comment-list">

<div class="jinsom-comment-<?php echo $parent_comment_id;?>">
<?php if(jinsom_is_comment_up($parent_comment_id,$user_id)){?>
<div class="up on" onclick="jinsom_comment_up(<?php echo $parent_comment_id;?>,this)"><i class="fa fa-thumbs-up"></i><m><?php echo jinsom_get_comment_up_count($parent_comment_id);?></m></div>
<?php }else{?>
<div class="up" onclick="jinsom_comment_up(<?php echo $parent_comment_id;?>,this)"><i class="fa fa-thumbs-o-up"></i><m><?php echo jinsom_get_comment_up_count($parent_comment_id);?></m></div>
<?php }?>

<div class="header clear">
<div class="avatarimg">
<a href="<?php echo jinsom_mobile_author_url($parent_user_id);?>" class="link">
<?php echo jinsom_avatar($parent_user_id,'40',avatar_type($parent_user_id));?>
<?php echo jinsom_verify($parent_user_id);?>
</a>
</div>	
<div class="info">
<div class="name">
<a href="<?php echo jinsom_mobile_author_url($parent_user_id);?>" class="link">
<?php echo jinsom_nickname($parent_user_id);?><?php echo jinsom_lv($parent_user_id);?><?php echo jinsom_vip($parent_user_id);?><?php echo jinsom_honor($parent_user_id);?>
</a>
</div>	
<div class="from">
<span class="time"><?php echo jinsom_timeago($parent_comment->comment_date);?></span>
<span><?php echo $form;?></span>
</div>
</div>
</div>
<div class="content">
<?php if($answer_adopt==$comment_id){echo '<i class="jinsom-icon jinsom-yicaina"></i>';}?>
<?php 
$comment_private=get_post_meta($post_id,'comment_private',true);
if($comment_private&&!jinsom_is_admin($user_id)&&$user_id!=$author_id&&$user_id!=$parent_comment_id){
echo '<div class="jinsom-tips"><i class="jinsom-icon jinsom-niming"></i> '.__('该内容只有作者可以浏览','jinsom').'</div>';
}else{
echo jinsom_autolink(convert_smilies(jinsom_add_lightbox_content($parent_comment->comment_content,$parent_comment_id)));
}
?>
</div>	
</div>


</div>

<div class="jinsom-single-comment">
<div class="header"><?php _e('全部回复','jinsom');?></div>
<div class="jinsom-single-comment-list jinsom-single-comment-list-<?php echo $post_id;?>-<?php echo $comment_id;?>">
<?php
if($comments){
foreach ($comments as $data) {
$comment_id=$data->comment_ID;
$comment_user_id=$data->user_id;
$comment_nickname=get_user_meta($comment_user_id,'nickname',true);
$comment_from=get_comment_meta($comment_id,'from',true);
if($comment_from=='mobile'){
$form=__('手机端','jinsom');
}else{
$form=__('电脑端','jinsom');	
}
$child_comment_num=jinsom_get_child_comments_num($comment_id);
?>	
<div class="jinsom-comment-<?php echo $comment_id;?>">
<?php if(jinsom_is_comment_up($comment_id,$user_id)){?>
<div class="up on" onclick="jinsom_comment_up(<?php echo $comment_id;?>,this)"><i class="fa fa-thumbs-up"></i><m><?php echo jinsom_get_comment_up_count($comment_id);?></m></div>
<?php }else{?>
<div class="up" onclick="jinsom_comment_up(<?php echo $comment_id;?>,this)"><i class="fa fa-thumbs-o-up"></i><m><?php echo jinsom_get_comment_up_count($comment_id);?></m></div>
<?php }?>

<div class="header clear">
<div class="avatarimg">
<a href="<?php echo jinsom_mobile_author_url($comment_user_id);?>" class="link">
<?php echo jinsom_avatar($comment_user_id,'40',avatar_type($comment_user_id));?>
<?php echo jinsom_verify($comment_user_id);?>
</a>
</div>	
<div class="info">
<div class="name">
<a href="<?php echo jinsom_mobile_author_url($comment_user_id);?>" class="link">
<?php echo jinsom_nickname($comment_user_id);?><?php echo jinsom_lv($comment_user_id);?><?php echo jinsom_vip($comment_user_id);?><?php echo jinsom_honor($comment_user_id);?>
</a>
</div>	
<div class="from"><span class="time"><?php echo jinsom_timeago($data->comment_date);?></span><span><?php echo $form;?></span></div>
</div>
</div>
<div class="content"><?php echo jinsom_autolink(do_shortcode(convert_smilies(jinsom_add_lightbox_content($data->comment_content,$comment_id))));?></div>
<div class="footer clear">
<span class="comment">
<?php if(is_user_logged_in()){?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/comment-bbs-floor.php?post_id=<?php echo $post_id;?>&comment_id=<?php echo $parent_comment_id;?>&bbs_id=<?php echo $bbs_id;?>&name=<?php echo $comment_nickname;?>" class="link"><m></m></a>
<?php }else{?>
<a onclick="myApp.loginScreen();"><m></m></a>
<?php }?>
</span>	

<?php 
if(!get_comment_meta($comment_id,'delete',true)&&is_user_logged_in()){
if($is_bbs_admin||$user_id==$parent_user_id||$user_id==$comment_user_id||$user_id==$author_id||get_user_meta($user_id,'user_power',true)==3){
echo '<span class="delete" onclick=\'jinsom_delete_bbs_post_comments('.$comment_id.','.$bbs_id.',"bbs-post-floor",this)\'>删除</span>';
}
}
?>

</div>	
</div>
<?php 
}
}else{
echo jinsom_empty();
}?>
</div>
</div>


</div>
</div>        