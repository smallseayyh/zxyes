<?php 
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$post_data = get_post($post_id, ARRAY_A);
$post_power=get_post_meta($post_id,'post_power',true);
$hide_content=get_post_meta($post_id,'pay_cnt',true);//隐藏内容
$author_id=$post_data['post_author'];
$content=do_shortcode(convert_smilies(wpautop(jinsom_autolink(jinsom_add_lightbox_content($post_data['post_content'],$post_id)))));
$title=$post_data['post_title'];
$post_date=$post_data['post_date'];
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);
//更新内容浏览量
$post_views=(int)get_post_meta($post_id,'post_views',true);
update_post_meta($post_id,'post_views',($post_views+1));
$comments_number=get_comments_number($post_id);
$comment_post_credit = jinsom_get_option('jinsom_comment_post_credit');

$more_type='single';//用于区分当前页面是列表页面还是内页

//文章版权
$jinsom_copyright_info = jinsom_get_option('jinsom_mobile_single_copyright_info');

//置顶、推荐
$commend_post=get_post_meta($post_id,'jinsom_commend',true);
$sticky_post=get_post_meta($post_id,'sticky',true);


$post_status=get_post_status($post_id);//内容状态
?>
<div data-page="post-single" class="page no-tabbar post-single">

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
}else{?>

<?php 
if($post_status=='pending'){
echo '<div class="jinsom-no-power-tips"><i class="fa fa-warning"></i> '.__('该内容处于审核中状态','jinsom').'</div>';
}else if($post_status=='draft'){
echo '<div class="jinsom-no-power-tips"><i class="fa fa-warning"></i> '.__('内容处于驳回状态，需重新编辑进行提交审核','jinsom').'</div>';	
}
?>

<div class="jinsom-single jinsom-post-<?php echo $post_id;?>">
<h1>
<?php 
if($sticky_post){echo '<span class="sticky"></span>';}
if($commend_post){echo '<span class="commend"></span>';}
?>
<?php echo $title;?>
</h1>

<div class="jinsom-single-author-info">
<span class="name">浏览 <?php echo $post_views;?></span>
<span class="dot">•</span>
<span class="from"><?php echo jinsom_post_from($post_id);?></span>
<span class="dot">•</span>
<span class="time"><?php echo date('Y-m-d H:i',strtotime($post_date));?></span>
</div>

<?php jinsom_title_bottom_hook();//标题下方的钩子 ?>

<div class="jinsom-post-single-content">
<?php echo $content;?>


<?php 
if($post_power==1){//付费可见类型
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
?>
<div class="jinsom-tips jinsom-tips-<?php echo $post_id;?>">
<p><i class="jinsom-icon jinsom-niming"></i> 隐藏内容需要付费才可以看见</p>
<div class="jinsom-btn opacity" onclick="jinsom_buy_post_form(<?php echo $post_id;?>)">马上购买</div>
</div>
<?php 
}else{//已经购买||作者
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_autolink(jinsom_add_lightbox_content($hide_content,$post_id)))).'</div>';
}

}else if($post_power==2){//密码类型
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人
?>
<div class="jinsom-tips jinsom-tips-<?php echo $post_id;?>">
<p><i class="jinsom-icon jinsom-niming"></i> 隐藏内容需要输入密码才可以看见</p>
<div class="jinsom-post-password">
<input id="jinsom-post-password">
<span class="opacity" onclick="jinsom_get_password_posts(<?php echo $post_id;?>,this);">查看</span>
</div>
</div>
<?php }else{ 
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_autolink(jinsom_add_lightbox_content($hide_content,$post_id)))).'</div>';//隐藏内容
} 
}else if($post_power==4){//VIP可见类型
if(!is_vip($user_id)&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、VIP用户
?>
<div class="jinsom-tips jinsom-tips-<?php echo $post_id;?>">
<p><i class="jinsom-icon jinsom-niming"></i> 隐藏内容需要开通会员才可以看见</p>
<div class="jinsom-btn opacity" onclick="jinsom_recharge_vip_type_form()">开通会员</div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_autolink(jinsom_add_lightbox_content($hide_content,$post_id)))).'</div>';//隐藏内容
}

}else if($post_power==5){//登录可见类型
if(!is_user_logged_in()){//没有登录的用户
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> 隐藏内容需要登录才可以看见</p>
<div class="jinsom-btn opacity open-login-screen">马上登录</div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_autolink(jinsom_add_lightbox_content($hide_content,$post_id)))).'</div>';//隐藏内容
}
}else if($post_power==6){//回复可见
if($user_id!=$author_id&&!jinsom_is_comment($user_id,$post_id)&&!jinsom_is_admin($user_id)){//没有回复
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要回复才可以看见','jinsom');?></p>
<?php 
if (is_user_logged_in()){
echo '<div class="jinsom-btn opacity"><a href="'.$theme_url.'/mobile/templates/page/comment.php?post_id='.$post_id.'&comment_see" class="link">'.__('回复','jinsom').'</a></div>';
}else{
echo '<div class="jinsom-btn open-login-screen opacity">'.__('回复','jinsom').'</div>'; 
}
?>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
}
}else if($post_power==7){//认证可见
if($user_id!=$author_id&&!get_user_meta($user_id,'verify',true)&&!jinsom_is_admin($user_id)){//非认证用户
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容仅限认证用户可见','jinsom');?></p>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
}
}else if($post_power==8){//粉丝可见
if($user_id!=$author_id&&!jinsom_is_follow_author($author_id,$user_id)&&!jinsom_is_admin($user_id)){//没有关注
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要关注作者才可以看见','jinsom');?></p>
<div class="jinsom-btn jinsom-follow-<?php echo $author_id;?> follow-see opacity" data="<?php echo get_the_permalink();?>" onclick="jinsom_follow(<?php echo $author_id;?>,this);"><i class="jinsom-icon jinsom-guanzhu"></i><?php _e('关注','jinsom');?></div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
}
}
?>

<!-- 话题 -->
<?php require( get_template_directory() . '/mobile/templates/post/topic-list.php' );?>


<?php if($jinsom_copyright_info){?>
<div class="jinsom-single-copyright-info">
<?php echo do_shortcode($jinsom_copyright_info);?>	
</div>
<?php }?>
</div>

<?php 
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