<?php 
foreach($comments as $comment) {
$comment_id=$comment->comment_ID;
$comment_from=get_comment_meta($comment_id,'from',true);
$this_comment_floor=get_comment_meta($comment_id,'comment_floor',true);
$comment_user_id=$comment->user_id;
$comment_content=$comment->comment_content;
$comment_date=$comment->comment_date;

$admin_a_name=get_term_meta($bbs_id,'admin_a_name',true);
$admin_b_name=get_term_meta($bbs_id,'admin_b_name',true);

$up_comment=get_post_meta($post_id,'up-comment',true);

if(isset($_GET['comment_id'])&&$comment_id==$_GET['comment_id']){
$class='current-comment';
}else{
$class='';
}
?>
<div class="jinsom-bbs-single-box <?php echo $class;?> clear">
<div class="left">
<?php if($author_id==$comment_user_id){echo '<div class="landlord"></div>';}?>		
<div class="avatar">
<a href="<?php echo jinsom_userlink($comment_user_id);?>" target="_blank">
<?php echo jinsom_vip_icon($comment_user_id);?>
<?php echo jinsom_avatar($comment_user_id, '50' , avatar_type($comment_user_id) );?>
<?php echo jinsom_verify($comment_user_id);?>
</a>
</div>
<div class="name">
<?php echo jinsom_nickname_link($comment_user_id);?>
</div>


<div class="info">
<?php if(in_array($comment_user_id,$admin_a_arr)||in_array($comment_user_id,$admin_b_arr)){?>
<div class="admin">
<?php 
if(in_array($comment_user_id,$admin_a_arr)){
echo '<span class="jinsom-icon jinsom-guanliyuan1 a"><i>'.$admin_a_name.'</i></span>';
}else{
echo '<span class="jinsom-icon jinsom-guanliyuan1 b"><i>'.$admin_b_name.'</i></span>';
}
?>
</div> 
<?php }?>
<div class="lv"><?php echo jinsom_lv($comment_user_id);?></div>
<div class="vip"><?php echo jinsom_vip($comment_user_id);?></div>
<div class="honor"><?php echo jinsom_honor($comment_user_id);?></div>
</div>
</div><!-- left -->

<div class="right">
<?php if($up_comment==$comment_id){echo '<span class="up-comment">'.__('置顶','jinsom').'</span>';}?>
<?php if($answer_adopt==$comment_id){echo '<i class="jinsom-icon answer-icon jinsom-yicaina"></i>';}?>
<div class="jinsom-bbs-single-content">
<?php 
$comment_private=get_post_meta($post_id,'comment_private',true);
if($comment_private&&!jinsom_is_admin($user_id)&&$user_id!=$author_id&&$user_id!=$comment_user_id){
echo '<div class="jinsom-tips"><i class="jinsom-icon jinsom-niming"></i> '.__('该内容只有作者可以浏览','jinsom').'</div>';
}else{
echo jinsom_autolink(do_shortcode(convert_smilies(jinsom_add_lightbox_content($comment_content,$comment_id))));
}
?>

<?php 
if(!$comment_private||jinsom_is_admin($user_id)||$user_id==$author_id||$user_id==$comment_user_id){//评论隐私
$comment_img=get_comment_meta($comment_id,'img',true);
if($comment_img){
echo '<div class="jinsom-comment-image-list bbs clear">';
$comment_img_arr=explode(',',$comment_img);
foreach ($comment_img_arr as $comment_img_src){
echo '<a href="'.$comment_img_src.'" data-fancybox="gallery-'.$comment_id.'" data-no-instant><img src="'.$comment_img_src.'"></a>';
}
echo '</div>';
}
}
?>
</div>



<div class="jinsom-bbs-single-footer">
<?php 
//管理团队、作者、大小版主、评论者都可以删除帖子评论
if(($user_id==$comment_user_id||$user_id==$author_id||$is_bbs_admin||get_user_meta($user_id,'user_power',true)==3)&&$answer_adopt!=$comment_id){?>
<span class="delete" onclick='jinsom_delete_bbs_comments(<?php echo $comment_id;?>,<?php echo $bbs_id;?>,this);'><?php _e('删除','jinsom');?></span>
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
if($user_id!=$comment_user_id){?>
<span class="reward" onclick="jinsom_reward_form(<?php echo $comment_id;?>,'comment')"><?php _e('打赏','jinsom');?></span>
<?php }?>

<?php 
if($user_id!=$comment_user_id){
if(jinsom_is_blacklist($user_id,$comment_user_id)){
echo '<span onclick=\'jinsom_add_blacklist("remove",'.$comment_user_id.',this)\'>'.__('取消拉黑','jinsom').'</span>';	
}else{
echo '<span onclick=\'jinsom_add_blacklist("add",'.$comment_user_id.',this)\'>'.__('拉黑','jinsom').'</span>';	
}
}
?>
<?php 
if(($user_id==$author_id||$is_bbs_admin)&&is_user_logged_in()){
if($post_type=='answer'&& !get_post_meta($post_id,'answer_adopt',true)){?>
<span class="answer" onclick='jinsom_answer_adopt(this,<?php echo $post_id;?>);' data="<?php echo $comment_id;?>"><?php _e('采纳答案','jinsom');?></span>
<?php }}?>
<span title="<?php echo $comment_date;?>"><?php echo jinsom_timeago($comment_date);?></span>
<?php 
//来自
if($comment_from=='mobile'){
echo '<span>'.__('手机端','jinsom').'</span>';
}else{
echo '<span>'.__('电脑端','jinsom').'</span>';  
}

//楼层
if(empty($this_comment_floor)){
  echo '';
}else{
  echo '<span>'.$this_comment_floor.' '.__('楼','jinsom').'</span>';
}?>

<span onclick="jinsom_bbs_show_comment_form(this);" class="comment"><?php _e('回复','jinsom');?></span>
<a style="color: #999;"><?php echo '('.jinsom_get_child_comments_num($comment_id).')';?></a>
</div>


<?php 
if(!$comment_private||jinsom_is_admin($user_id)||$user_id==$author_id||$user_id==$comment_user_id){
$child_comments_num=jinsom_get_child_comments_num($comment_id);?>
<div class="jinsom-bbs-comment-floor clear" <?php if($child_comments_num){echo 'style="display:block;"'; };?>>
<div class="jinsom-bbs-comment-floor-list" >
<?php 
$args_floor = array(
'number' => 100,
'parent'=>$comment_id,
'status' =>'approve',
'orderby' => 'comment_ID',
'order' => 'ASC',
);
$comments_floor = get_comments($args_floor);
foreach ($comments_floor as $comments_floors) {
$comment_id_floor=$comments_floors->comment_ID;
$comment_from_floor=get_comment_meta($comment_id_floor,'from',true);

?>

<li class="clear">
<div class="floor-left">
<a href="<?php echo jinsom_userlink($comments_floors->user_id);?>" target="_blank">
<?php echo jinsom_avatar($comments_floors->user_id,'40',avatar_type($comments_floors->user_id));?>
<?php echo jinsom_verify($comments_floors->user_id);?>
</a>
</div>

<div class="floor-right">
<div class="name">
<?php echo jinsom_nickname_link($comments_floors->user_id);?>：
<span class="content"><?php echo jinsom_autolink(convert_smilies($comments_floors->comment_content));?></span></div>
</div>

<div class="bottom">
<?php 
//管理团队、作者、评论者、楼主、大小版主都可以删除帖子评论
if($user_id==$comment_user_id||$user_id==$author_id||$is_bbs_admin||$user_id==$comments_floors->user_id||get_user_meta($user_id,'user_power',true)==3){?>
<span class="delete" onclick='jinsom_delete_bbs_comments_er(<?php echo $comment_id_floor;?>,<?php echo $bbs_id;?>,this);'><?php _e('删除','jinsom');?></span>
<?php }?>

<?php 
if($user_id!=$comments_floors->user_id){
if(jinsom_is_blacklist($user_id,$comments_floors->user_id)){
echo '<span onclick=\'jinsom_add_blacklist("remove",'.$comments_floors->user_id.',this)\'>'.__('取消拉黑','jinsom').'</span>';	
}else{
echo '<span onclick=\'jinsom_add_blacklist("add",'.$comments_floors->user_id.',this)\'>'.__('拉黑','jinsom').'</span>';	
}
}
?>

<span title="<?php echo $comments_floors->comment_date;?>"><?php echo jinsom_timeago($comments_floors->comment_date);?></span>
<?php 
//二级回帖来自
if($comment_from_floor=='mobile'){
echo '<span>'.__('手机端','jinsom').'</span>';
}else{
echo '<span>'.__('电脑端','jinsom').'</span>';  
}
?>
<span onclick="jinsom_set_input('jinsom-bbs-comment-floor-<?php echo $comment_id;?>',' @<?php echo get_user_meta($comments_floors->user_id,'nickname',true);?> ');" class="comment"><?php _e('回复','jinsom');?></span>
</div>

</li>

<?php }//二级回帖循环  ?>
</div>

<textarea id="jinsom-bbs-comment-floor-<?php echo $comment_id;?>" class="jinsom-post-comments"></textarea>

<span class="jinsom-single-expression-btn" onclick="jinsom_smile(this,'normal','')">
<i class="jinsom-icon expression jinsom-weixiao-"></i>
</span>


<?php if (is_user_logged_in()) { ?>



<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("comment",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($user_id)){?>
<div class="jinsom-comments-btn opacity" id="comment-floor-<?php echo $comment_id;?>"><?php _e('回复','jinsom');?><?php if($credit_reply_number<0){echo '（'.$credit_reply_number.$credit_name.'）';}?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('comment-floor-<?php echo $comment_id;?>'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_bbs_comment_floor(<?php echo $comment_id;?>,<?php echo $post_id;?>,<?php echo $bbs_id;?>,$('#comment-floor-<?php echo $comment_id;?>'),res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<div onclick="jinsom_bbs_comment_floor(<?php echo $comment_id;?>,<?php echo $post_id;?>,<?php echo $bbs_id;?>,this,'','');" class="jinsom-comments-btn opacity"><?php _e('回复','jinsom');?><?php if($credit_reply_number<0){echo '（'.$credit_reply_number.$credit_name.'）';}?></div>
<?php }?>



<?php }else{?>
<div onclick="jinsom_pop_login_style();" class="jinsom-comments-btn opacity"><?php _e('回复','jinsom');?><?php if($credit_reply_number<0){echo '（'.$credit_reply_number.$credit_name.'）';}?></div>
<?php }?>
</div>
<?php }//如果是回复隐私则不显示楼层?>

</div>

</div>

<?php }//foreach?>