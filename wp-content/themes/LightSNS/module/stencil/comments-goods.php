<div class="jinsom-comment-form" style="display:block';">
<div class="jinsom-post-comment-list">
<?php 
$nums=50;
$args = array(
'status'=>'approve',
'type' => 'comment',
'karma'=>9,
'no_found_rows' =>false,
'number' => $nums,
'post_id' => $post_id
);
$comment_data = get_comments($args);
if (!empty($comment_data) ) { 
foreach ($comment_data as $comment_datas) {
$comment_id = $comment_datas->comment_ID;
$comment_user_id = $comment_datas->user_id;
$comment_content = $comment_datas->comment_content;
$comment_time = $comment_datas->comment_date;
$comment_avatar=jinsom_avatar($comment_user_id, '20' , avatar_type($comment_user_id));
$comment_user_name = get_user_meta($comment_user_id,'nickname',true);



if(!get_comment_meta($comment_id,'delete',true)){//如果评论被删除过了 则不再显示删除按钮
if (is_user_logged_in()) {
if(jinsom_is_admin($user_id)||$user_id==$comment_user_id||$user_id==$author_id){
$delete_btn='<span class="comment_delete" onclick="jinsom_delete_post_comments('.$comment_id.',this);">'.__('删除','jinsom').'</span>';
}else{ $delete_btn='';}
}else{
$delete_btn='';
}
}else{
$delete_btn='';
}


$comment_from=get_comment_meta($comment_id,'from',true);

?>

<li <?php echo $tips;?>>
<div class="jinsom-comment-avatar">
<a href="<?php echo jinsom_userlink($comment_user_id);?>" target="_blank">
<?php echo $comment_avatar;?>
<?php echo jinsom_verify($comment_user_id);?>
</a>
</div>
<div class="jinsom-comment-header">
<div class="jinsom-comment-info">
<?php echo jinsom_nickname_link($comment_user_id);?>
<?php echo jinsom_lv($comment_user_id).jinsom_vip($comment_user_id).jinsom_honor($comment_user_id);?>

</div>
<div class="jinsom-comment-info-footer">
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
<div class="jinsom-comment-content" title="<?php echo $comment_time;?>">
<div class="jinsom-goods-comment-star">
<ul class="layui-rate" readonly>
<?php 
$star=(int)get_comment_meta($comment_id,'star',true);
if($star<=0||$star>5){$star=5;}
for ($i=0; $i < 5; $i++){ 
if($i<$star){
$class='layui-icon-rate-solid';
}else{
$class='layui-icon-rate';	
}
echo '<li class="layui-inline"><i class="layui-icon '.$class.'"></i></li>';
}
?>
</ul>
</div>
<?php echo convert_smilies(jinsom_autolink($comment_content));?>
</div>
<div class="jinsom-comment-footer"></div>
</li>
<?php 

}
}else{
echo jinsom_empty(__('该商品还没有人评价','jinsom'));	
}
 ?>

</div>


</div>
