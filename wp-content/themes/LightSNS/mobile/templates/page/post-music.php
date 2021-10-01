<?php 
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$post_data = get_post($post_id, ARRAY_A);
$post_power=get_post_meta($post_id,'post_power',true);
$author_id=$post_data['post_author'];
$content=do_shortcode(convert_smilies(wpautop(jinsom_autolink($post_data['post_content'],$post_id))));
$title=$post_data['post_title'];
$post_date=$post_data['post_date'];
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);
$more_type='single';//用于区分当前页面是列表页面还是内页	

//更新内容浏览量
$post_views=(int)get_post_meta($post_id,'post_views',true);
update_post_meta($post_id,'post_views',($post_views+1));

$comments_number=get_comments_number($post_id);
$comment_post_credit = jinsom_get_option('jinsom_comment_post_credit');

$post_status=get_post_status($post_id);//内容状态
?>
<div data-page="post-single" class="page no-tabbar post-music">

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

<div class="jinsom-single jinsom-post-<?php echo $post_id;?>">



<div onclick="jinsom_play_music(<?php echo $post_id;?>,this)" class="jinsom-music-voice jinsom-music-voice-<?php echo $post_id;?>"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>

<?php if($title!=''){?>
<h1><?php echo $title;?></h1>
<?php }?>

<div class="jinsom-single-author-info">
<span class="name">播放 <?php echo $post_views;?></span>
<span class="dot">•</span>
<span class="from"><?php echo jinsom_post_from($post_id);?></span>
<span class="dot">•</span>
<span class="time"><?php echo date('Y-m-d H:i',strtotime($post_date));?></span>
</div>

<?php jinsom_title_bottom_hook();//标题下方的钩子 ?>

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