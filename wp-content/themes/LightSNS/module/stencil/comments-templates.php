<?php 
//评论模版
$comment_id = $comment_datas->comment_ID;
$comment_user_id = $comment_datas->user_id;
$comment_content = $comment_datas->comment_content;
$comment_time = $comment_datas->comment_date;
$comment_avatar=jinsom_avatar($comment_user_id, '20' , avatar_type($comment_user_id));
$comment_user_name = get_user_meta($comment_user_id,'nickname',true);
$up_comment_=get_post_meta($post_id,'up-comment',true);


if(!get_comment_meta($comment_id,'delete',true)){//如果评论被删除过了 则不再显示删除按钮
if (is_user_logged_in()) {
if(jinsom_is_admin_x($user_id)||$user_id==$comment_user_id||$user_id==$author_id){
$delete_btn='<span class="comment_delete" onclick="jinsom_delete_post_comments('.$comment_id.',this);">'.__('删除','jinsom').'</span>';
}else{ $delete_btn='';}
}else{
$delete_btn='';
}
}else{
$delete_btn='';
}


$comment_from=get_comment_meta($comment_id,'from',true);
if(is_user_logged_in()){
$comment_reply= "<span class='reply' onclick=\"jinsom_set_input('jinsom-comment-form-".$post_id."',' @".$comment_user_name." ')\">".__('回复','jinsom')."</span>";

}else{
$comment_reply= '<span class="comment_reply" onclick="jinsom_pop_login_style();">'.__('回复','jinsom').'</span>'; 
$up_comment='<span class="jinsom-comment-up" onclick="jinsom_pop_login_style();"><i class="fa fa-thumbs-o-up"></i>0</span>';
}
if(jinsom_is_comment_up($comment_id,$user_id)){
$up_comment='<span class="jinsom-comment-up on" onclick="jinsom_single_comment_up('.$comment_id.',this)"><i class="fa fa-thumbs-up"></i><m>'.jinsom_get_comment_up_count($comment_id).'</m></span>';
}else{
$up_comment='<span class="jinsom-comment-up" onclick="jinsom_single_comment_up('.$comment_id.',this)"><i class="fa fa-thumbs-o-up"></i><m>'.jinsom_get_comment_up_count($comment_id).'</m></span>';
}

if(get_comment_meta($comment_id,'reward',true)){
$tips='reward';
}else if(get_comment_meta($comment_id,'reprint',true)){
$tips='reprint';
}else{
$tips='';	
}


if(isset($_GET['comment_id'])&&$comment_id==$_GET['comment_id']){
$class='current-comment';
}else{
$class='';
}
?>

<li class="<?php echo $tips;?> <?php echo $class;?>">
<div class="jinsom-comment-avatar">
<a href="<?php echo jinsom_userlink($comment_user_id);?>" target="_blank">
<?php echo $comment_avatar;?>
<?php echo jinsom_verify($comment_user_id);?>
</a>
</div>
<div class="jinsom-comment-header">
<?php echo $up_comment;?>
<div class="jinsom-comment-info">
<?php echo jinsom_nickname_link($comment_user_id);?>
<?php echo jinsom_lv($comment_user_id).jinsom_vip($comment_user_id).jinsom_honor($comment_user_id);
if(get_post_type($post_id)=='goods'){
if(jinsom_is_buy_goods($comment_user_id,$post_id)){
echo '<span class="jinsom-mark buy">'.__('已购买','jinsom').'</span>';
}
}

?>

</div>
<div class="jinsom-comment-info-footer">
<?php if($up_comment_==$comment_id){echo '<span class="up-comment">'.__('置顶','jinsom').'</span>';}?>
<span class="jinsom-comment-time" title="<?php echo $comment_time;?>"><?php echo jinsom_timeago($comment_time);?></span>
<?php 
if($comment_from=='mobile'){
echo '<span class="jinsom-comment-from">'.__('手机端','jinsom').'</span>';
}else{
echo '<span class="jinsom-comment-from">'.__('电脑端','jinsom').'</span>';	
}
?>
</div>
</div>
<div class="jinsom-comment-content" title="<?php echo $comment_time;?>"><?php echo convert_smilies(jinsom_autolink($comment_content));?></div>

<?php 
$comment_img=get_comment_meta($comment_id,'img',true);
if($comment_img){
echo '<div class="jinsom-comment-image-list clear">';
$comment_img_arr=explode(',',$comment_img);
foreach ($comment_img_arr as $comment_img_src){
echo '<a href="'.$comment_img_src.'" data-fancybox="gallery-'.$comment_id.'" data-no-instant><img src="'.$comment_img_src.'"></a>';
}
echo '</div>';
}
?>

<div class="jinsom-comment-footer">
<?php 
if(current_user_can('level_10')){
//echo '<span class="ban_ip" onclick="jinsom_test();">'.__('封禁','jinsom').'</span>';
}
if($user_id!=$comment_user_id){
if(jinsom_is_blacklist($user_id,$comment_user_id)){
echo '<span onclick=\'jinsom_add_blacklist("remove",'.$comment_user_id.',this)\'>'.__('取消拉黑','jinsom').'</span>';	
}else{
echo '<span onclick=\'jinsom_add_blacklist("add",'.$comment_user_id.',this)\'>'.__('拉黑','jinsom').'</span>';	
}
}
?>

<?php echo $delete_btn;?>

<?php 
if(jinsom_is_admin($user_id)||$user_id==$author_id){
if($up_comment_==$comment_id){
$up_name=__('取消置顶','jinsom');
}else{
$up_name=__('置顶','jinsom');
}
echo '<span class="comment-up" onclick="jinsom_up_comment('.$comment_id.',0,this)">'.$up_name.'</span>';
}
?>

<span class="report" onclick="jinsom_test();"><?php _e('举报','jinsom');?></span>

<?php if($user_id!=$comment_user_id){?>
<span class="reward" onclick="jinsom_reward_form(<?php echo $comment_id;?>,'comment');"><?php _e('打赏','jinsom');?></span>
<?php }?>

<?php echo $comment_reply;?>
<?php if($up_comment_!=$comment_id){?>
<span class="post_floor"><?php if($com_count_a){echo $com_count_a.'楼';}?></span>
<?php }?>
</div>
</li>
<?php 
$com_count_a--;
