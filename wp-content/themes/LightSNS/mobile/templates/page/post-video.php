<?php 
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;


$post_id=$_GET['post_id'];
$post_data = get_post($post_id, ARRAY_A);
$author_id=$post_data['post_author'];
$post_power=get_post_meta($post_id,'post_power',true);
$content=do_shortcode(convert_smilies(wpautop(jinsom_autolink($post_data['post_content'],$post_id))));
$title=$post_data['post_title'];
$post_date=$post_data['post_date'];	
$video_url=get_post_meta($post_id,'video_url',true);
$video_img=jinsom_video_cover($post_id);//视频封面
$video_lists=get_post_meta($post_id,'video_lists',true);//集数


$more_type='single';//用于区分当前页面是列表页面还是内页

//更新内容浏览量
$post_views=(int)get_post_meta($post_id,'post_views',true);
update_post_meta($post_id,'post_views',($post_views+1));

$comments_number=get_comments_number($post_id);
$comment_post_credit = jinsom_get_option('jinsom_comment_post_credit');

$post_status=get_post_status($post_id);//内容状态

?>
<div data-page="post-single" class="page no-tabbar post-video">

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

<?php 
if($post_status!='pending'&&$post_status!='draft'){
require($require_url.'/mobile/templates/post/comment-toolbar.php');
}
?>

<div class="page-content keep-toolbar-on-scroll infinite-scroll jinsom-page-single-content jinsom-page-single-content-<?php echo $post_id;?>" data-distance="800">

<?php 
if($post_power==3&&!jinsom_is_admin($user_id)&&$user_id!=$author_id){
require($require_url.'/post/private-no-power.php');
}else{
?>

<?php 
if($post_status=='pending'){
echo '<div class="jinsom-no-power-tips"><i class="fa fa-warning"></i> '.__('该内容处于审核中状态','jinsom').'</div>';
}else if($post_status=='draft'){
echo '<div class="jinsom-no-power-tips"><i class="fa fa-warning"></i> '.__('内容处于驳回状态，需重新编辑进行提交审核','jinsom').'</div>';	
}
?>

<div class="jinsom-single video jinsom-post-<?php echo $post_id;?>">



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
<div onclick="jinsom_play_video('<?php echo $post_id;?>-single','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
<?php }?>
<?php }else if($post_power==2){//密码类型
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要密码才可以播放','jinsom');?></div>
<div class="btn opacity" onclick="layer.open({content:'暂未开启！',skin:'msg',time:2});"><?php _e('输入密码','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div onclick="jinsom_play_video('<?php echo $post_id;?>-single','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
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
<div onclick="jinsom_play_video('<?php echo $post_id;?>-single','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
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
<div onclick="jinsom_play_video('<?php echo $post_id;?>-single','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
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
<div onclick="jinsom_play_video('<?php echo $post_id;?>-single','<?php echo $video_url;?>',this)" class="jinsom-video-img" style="background-image: url(<?php echo jinsom_video_cover($post_id);?>);"><i class="jinsom-icon jinsom-bofang-"></i></div>
<?php }?>


<h1><?php echo $title;?></h1>


<div class="jinsom-single-author-info">
<span class="name">播放 <?php echo $post_views;?></span>
<span class="dot">•</span>
<span class="from"><?php echo jinsom_post_from($post_id);?></span>
<span class="dot">•</span>
<span class="time"><?php echo date('Y-m-d H:i',strtotime($post_date));?></span>
</div>

<?php jinsom_title_bottom_hook();//标题下方的钩子 ?>


<?php 
if($video_lists){
echo '<div class="jinsom-single-video-lists">';
$video_lists_arr=explode(",",$video_lists);
foreach ($video_lists_arr as $data) {
$data_arr=explode("|",$data);
if($data_arr[1]==$post_id){$on='on';}else{$on='';}
$video_lists_power=get_post_meta($data_arr[1],'post_power',true);
if($video_lists_power==1){
$video_lists_power='<i>付费</i>';
}else if($video_lists_power==2){
$video_lists_power='<i>密码</i>';
}else if($video_lists_power==4){
$video_lists_power='<i>VIP</i>';
}else if($video_lists_power==5){
$video_lists_power='<i>登录</i>';
}else{
$video_lists_power='';
}
echo '<li class="'.$on.'">'.$video_lists_power.'<a href="'.jinsom_mobile_post_url($data_arr[1]).'" target="_blank" class="link"><img src="'.jinsom_video_cover($data_arr[1]).'"><p>'.$data_arr[0].'</p></a></li>';
}
echo '</div>';
}
?>

<div class="jinsom-post-single-content">
<?php echo $content;?>
</div>



<?php 
require( get_template_directory() . '/mobile/templates/post/topic-list.php' );//话题 
jinsom_single_content_end_hook();//自定义内容结束钩子
require( get_template_directory() . '/mobile/templates/post/bar.php' );
jinsom_mobile_post_like_list($post_id);//喜欢列表
?>

</div>

<?php 
if($post_status!='pending'&&$post_status!='draft'){
require( get_template_directory().'/mobile/templates/post/comment.php');
}
?>


<?php }//私密内容?>

</div>
</div>        