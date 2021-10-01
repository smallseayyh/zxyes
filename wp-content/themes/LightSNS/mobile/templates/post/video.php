<?php
//视频
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
$author_id=get_the_author_meta('ID');
$post_id=get_the_ID();
$video_url=get_post_meta($post_id,'video_url',true);//视频地址
$video_img=jinsom_video_cover($post_id);//视频封面
$content=strip_tags(jinsom_get_post_content($post_id));
$content_number=mb_strlen($content,'utf-8');
$fold_number = jinsom_get_option('jinsom_mobile_content_more_fold_number');

?>
<div class="jinsom-post-words jinsom-post-<?php echo $post_id;?> jinsom-post-author-<?php echo $author_id;?>">
<?php require( get_template_directory() . '/mobile/templates/post/info.php' );?>



<?php 
if($post_power==1){//付费可见类型
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要付费才可以播放','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_buy_post_form(<?php echo $post_id;?>)"><?php _e('马上购买','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div onclick="jinsom_play_video('<?php echo $post_id;?>','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
<?php }?>
<?php }else if($post_power==2){//密码类型
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要密码才可以播放','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_video_password(<?php echo $post_id;?>)"><?php _e('输入密码','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div onclick="jinsom_play_video('<?php echo $post_id;?>','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
<?php }?>
<?php }else if($post_power==4){//VIP可见类型
if(!is_vip($user_id)&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、VIP用户
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要开通会员才可以播放','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_recharge_vip_type_form()"><?php _e('马上开通','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div onclick="jinsom_play_video('<?php echo $post_id;?>','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
<?php }?>
<?php }else if($post_power==5){//登录可见类型
if(!is_user_logged_in()){//没有登录的用户
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要登录才可以播放','jinsom');?></div>
<div class="btn opacity open-login-screen" onclick="myApp.closeModal()"><?php _e('马上登录','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div onclick="jinsom_play_video('<?php echo $post_id;?>','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
<?php }?>
<?php }else if($post_power==6){//回复
if($user_id!=$author_id&&!jinsom_is_comment($user_id,$post_id)&&!jinsom_is_admin($user_id)){//没有回复
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要回复之后才可以播放','jinsom');?></div>
<?php 
if (is_user_logged_in()){
echo '<div class="btn opacity"><a href="'.$theme_url.'/mobile/templates/page/comment.php?post_id='.$post_id.'&comment_see" class="link">'.__('回复','jinsom').'</a></div>';
}else{
echo '<div class="btn open-login-screen opacity">'.__('回复','jinsom').'</div>'; 
}
?>
</div>
</div>
<?php }else{?>
<div onclick="jinsom_play_video('<?php echo $post_id;?>-single','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
<?php }?>
<?php }else if($post_power==7){//认证
if($user_id!=$author_id&&!get_user_meta($user_id,'verify',true)&&!jinsom_is_admin($user_id)){//没有认证
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要认证用户才可以播放','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div onclick="jinsom_play_video('<?php echo $post_id;?>-single','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
<?php }?>
<?php }else if($post_power==8){//粉丝
if($user_id!=$author_id&&!jinsom_is_follow_author($author_id,$user_id)&&!jinsom_is_admin($user_id)){//没有关注
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要关注作者才可以播放','jinsom');?></div>
<div class="btn jinsom-follow-<?php echo $author_id;?> follow-see opacity" data="<?php echo get_the_permalink();?>" onclick="jinsom_follow(<?php echo $author_id;?>,this);"><i class="jinsom-icon jinsom-guanzhu"></i><?php _e('关注','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div onclick="jinsom_play_video('<?php echo $post_id;?>-single','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
<?php }?>
<?php }else{?>
<div onclick="jinsom_play_video('<?php echo $post_id;?>','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
<?php }?>




<div class="content <?php if($content_number>$fold_number){ echo 'hidden';} ?>">
<a href="<?php echo jinsom_mobile_post_url($post_id);?>" class="link">
<h1><?php the_title();?></h1>
<?php echo do_shortcode(convert_smilies(jinsom_autolink(wpautop(get_the_content()))));?>
</a>
</div>
<?php if($content_number>$fold_number){echo"<div class='jinsom-post-read-more' onclick='jinsom_moren_content(this)''>查看全文</div>";}?>



<?php require( get_template_directory() . '/mobile/templates/post/bar.php' );?>
</div>
