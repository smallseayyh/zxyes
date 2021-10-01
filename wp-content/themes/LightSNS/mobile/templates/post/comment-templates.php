<?php 
//评论模版
foreach ($comments as $data) {
$comment_id=$data->comment_ID;
$comment_user_id=$data->user_id;
$comment_nickname=get_user_meta($comment_user_id,'nickname',true);
$comment_private=get_post_meta($post_id,'comment_private',true);//评论隐私
$comment_from=get_comment_meta($comment_id,'from',true);
$up_comment=get_post_meta($post_id,'up-comment',true);
if($comment_from=='mobile'){
$form=__('手机端','jinsom');
}else{
$form=__('电脑端','jinsom');	
}

if(isset($_GET['comment_id'])&&$comment_id==$_GET['comment_id']){
$class='current-comment';
}else{
$class='';
}
?>	
<div class="jinsom-comment-li jinsom-comment-<?php echo $comment_id;?> <?php echo $class;?>">
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
<div class="from">
<?php if($up_comment==$comment_id){echo '<span class="up-comment">'.__('置顶','jinsom').'</span>';}?>
<span class="time"><?php echo jinsom_timeago($data->comment_date);?></span>
<span><?php echo $form;?></span>
<?php if($up_comment!=$comment_id){if($type=='bbs'){?>
<span><?php echo sprintf(__( '%s楼','jinsom'),(int)get_comment_meta($comment_id,'comment_floor',true));?></span>
<?php }}?>
</div>
</div>
</div>
<div class="content">
<?php 
if($type=='bbs'){
if($answer_adopt==$comment_id){echo '<i class="jinsom-icon jinsom-yicaina"></i>';}
if($comment_private&&!jinsom_is_admin($user_id)&&$user_id!=$author_id&&$user_id!=$comment_user_id){
echo '<div class="jinsom-tips"><i class="jinsom-icon jinsom-niming"></i> '.__('该内容只有作者可以浏览','jinsom').'</div>';
}else{
echo jinsom_autolink(do_shortcode(convert_smilies(jinsom_add_lightbox_content($data->comment_content,$comment_id))));
}	
}else{
echo convert_smilies(jinsom_autolink($data->comment_content));	
}
?>
</div>

<?php 
if(!$comment_private||jinsom_is_admin($user_id)||$user_id==$author_id||$user_id==$comment_user_id){//评论隐私
$comment_img=get_comment_meta($comment_id,'img',true);
if($comment_img){
echo '<div class="jinsom-comment-image-list clear">';
$comment_img_arr=explode(',',$comment_img);
foreach ($comment_img_arr as $comment_img_src){
echo '<a href="'.$comment_img_src.'" data-fancybox="gallery-'.$comment_id.'" data-no-instant><img src="'.$comment_img_src.'"></a>';
}
echo '</div>';
}
}
?>

<div class="footer clear">



<?php if($type=='bbs'){?>
<?php if(!$comment_private||jinsom_is_admin($user_id)||$user_id==$author_id||$user_id==$comment_user_id){
$child_comment_num=jinsom_get_child_comments_num($comment_id);
if($child_comment_num){?>
<span class="comment on">
<a href="<?php echo $theme_url;?>/mobile/templates/page/comment-child-page.php?post_id=<?php echo $post_id;?>&comment_id=<?php echo $comment_id;?>&bbs_id=<?php echo $bbs_id;?>" class="link"><m><?php echo $child_comment_num;?></m></a>
</span>
<?php }else{?>
<span class="comment">
<a href="<?php echo $theme_url;?>/mobile/templates/page/comment-child-page.php?post_id=<?php echo $post_id;?>&comment_id=<?php echo $comment_id;?>&bbs_id=<?php echo $bbs_id;?>" class="link"><m></m></a>
</span>
<?php }?>
<?php }?>

<?php 
$answer_adopt=(int)get_post_meta($post_id,'answer_adopt',true);
$post_type=get_post_meta($post_id,'post_type',true);//内容类型
if(($user_id==$author_id||$is_bbs_admin)&&is_user_logged_in()){
if($post_type=='answer'&& !$answer_adopt){?>
<span class="answer" onclick='jinsom_answer_adopt(this,<?php echo $post_id;?>);' data="<?php echo $comment_id;?>"><?php _e('采纳','jinsom');?></span>
<?php }}?>


<?php if($user_id!=$comment_user_id){?>
<span class="reward"><a href="<?php echo $theme_url;?>/mobile/templates/page/reward.php?post_id=<?php echo $comment_id;?>&type=comment" class="link"><?php _e('打赏','jinsom');?></a></span>
<?php }?>

<?php 
if(($is_bbs_admin||$user_id==$author_id)&&$answer_adopt!=$comment_id){
if($up_comment==$comment_id){
$up_name=__('取消置顶','jinsom');
}else{
$up_name=__('置顶','jinsom');
}
echo '<span class="comment-up" onclick="jinsom_up_comment('.$comment_id.','.$bbs_id.',this)">'.$up_name.'</span>';
}
?>

<?php 
if(!get_comment_meta($comment_id,'delete',true)&&is_user_logged_in()){
if(($is_bbs_admin||$user_id==$comment_user_id||$user_id==$author_id||get_user_meta($user_id,'user_power',true)==3)&&$answer_adopt!=$comment_id){
echo '<span class="delete" onclick=\'jinsom_delete_bbs_post_comments('.$comment_id.','.$bbs_id.',"bbs-post",this)\'>'.__('删除','jinsom').'</span>';
}
}
?>

<?php }else{//动态类?>

<span class="comment">
<?php if(is_user_logged_in()){?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/comment.php?post_id=<?php echo $post_id;?>&name=<?php echo $comment_nickname;?>" class="link"><m></m></a>
<?php }else{?>
<a onclick="myApp.loginScreen();"><m></m></a>
<?php }?>
</span>

<?php if($user_id!=$comment_user_id){?>
<span class="reward"><a href="<?php echo $theme_url;?>/mobile/templates/page/reward.php?post_id=<?php echo $comment_id;?>&type=comment" class="link"><?php _e('打赏','jinsom');?></a></span>
<?php }?>

<?php 
if(jinsom_is_admin($user_id)||$user_id==$author_id){
if($up_comment==$comment_id){
$up_name=__('取消置顶','jinsom');
}else{
$up_name=__('置顶','jinsom');
}
echo '<span class="comment-up" onclick="jinsom_up_comment('.$comment_id.',0,this)">'.$up_name.'</span>';
}
?>

<?php 
if(!get_comment_meta($comment_id,'delete',true)&&is_user_logged_in()){
if(jinsom_is_admin_x($user_id)||$user_id==$comment_user_id||$user_id==$author_id){
echo '<span class="delete" onclick="jinsom_delete_post_comments('.$comment_id.',this)">'.__('删除','jinsom').'</span>';
}
}
?>

<?php }?>

	



</div>	
</div>
<?php 
}
