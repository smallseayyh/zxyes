<?php 
//评价模版
$args = array(
'status'=>'approve',
'type' => 'comment',
'karma'=>9,
'no_found_rows' =>false,
'number' =>$number,
'post_id' => $post_id
);
$comment_data=get_comments($args);
$args['count']=true;
$comment_count=get_comments($args);
$star_percent=(int)get_post_meta($post_id,'star_percent',true);
if(!$star_percent){$star_percent=100;}
?>
<div class="jinsom-goods-single-box comment">
<div class="title"><?php _e('商品评价','jinsom');?> (<?php echo $comment_count;?>)<div class="right"><a href="<?php echo get_template_directory_uri();?>/mobile/templates/page/shop/goods-star-list.php?post_id=<?php echo $post_id;?>" class="link"><?php _e('好评率','jinsom');?> <?php echo $star_percent;?>% <i class="jinsom-icon jinsom-arrow-right"></i></a></div></div>
<div class="content">
<?php 
if(!empty($comment_data)){ 
foreach ($comment_data as $comment_datas) {
$comment_id=$comment_datas->comment_ID;
$comment_user_id=$comment_datas->user_id;
$comment_content=convert_smilies($comment_datas->comment_content);
$comment_time=jinsom_timeago($comment_datas->comment_date);
$comment_avatar=jinsom_avatar($comment_user_id, '20' , avatar_type($comment_user_id));
$comment_user_name=get_user_meta($comment_user_id,'nickname',true);

$star=(int)get_comment_meta($comment_id,'star',true);
$star_html='';
if($star<=0||$star>5){$star=5;}
for ($i=0; $i < 5; $i++){ 
if($i<$star){
$class='jinsom-shoucang';
}else{
$class='jinsom-shoucang1';	
}
$star_html.='<i class="jinsom-icon '.$class.'"></i>';
}
$star_html='<div class="star">'.$star_html.'</div>';

echo '<li><div class="info"><span class="avatarimg">'.$comment_avatar.'</span><span class="nickname">'.$comment_user_name.'</span><span class="time">'.$comment_time.'</span></div>'.$star_html.'<div class="goods-comment-content">'.$comment_content.'</div></li>';
}
if($comment_count>3){
echo '<a href="'.get_template_directory_uri().'/mobile/templates/page/shop/goods-star-list.php?post_id='.$post_id.'" class="link jinsom-goods-all-comment-btn">'.__('查看全部评价','jinsom').'</a>';
}
}else{
echo '<div class="jinsom-empty-page"><i class="jinsom-icon jinsom-meiyoupinglun"></i><div class="title"><p>'.__('暂没有评价','jinsom').'</p></div></div>';
}

?>
</div>
</div>