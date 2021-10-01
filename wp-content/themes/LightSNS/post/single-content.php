<?php 
//文章内页
update_post_meta($post_id,'post_views',($post_views+1));//更新内容浏览量
$hide_content=get_post_meta($post_id,'pay_cnt',true);//隐藏内容
$single_copyright_info = jinsom_get_option('jinsom_single_copyright_info');//版权
?>
<h1 class="single">
<?php echo $title; ?>
<?php if($sticky_post){echo '<span class="jinsom-mark jinsom-top"></span>';}?>
<?php if($commend_post){echo '<span class="jinsom-mark jinsom-commend-icon"></span>';}?>	
</h1>

<?php 
if(is_single()){
jinsom_title_bottom_hook();//标题下方的钩子
}
?>

<div class="jinsom-single-content">
<?php 
echo do_shortcode(convert_smilies(wpautop(jinsom_autolink(jinsom_add_lightbox_content($content_source,$post_id)))));//内容
if($post_power==1){//付费可见类型
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> 隐藏内容需要付费才可以看见</p>
<div class="jinsom-btn opacity" onclick="jinsom_show_pay_form(<?php echo $post_id;?>);">马上购买</div>
</div>
<?php 
}else{//已经购买||作者
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_autolink(jinsom_add_lightbox_content($hide_content,$post_id)))).'</div>';
}

}else if($post_power==2){//密码类型
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> 隐藏内容需要输入密码才可以看见</p>
<div class="jinsom-post-password">
<input id="jinsom-post-password">
<span class="opacity" onclick="jinsom_get_password_posts(<?php echo $post_id;?>,'page',this);">查看</span>
</div>
</div>
<?php }else{ 
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_autolink(jinsom_add_lightbox_content($hide_content,$post_id)))).'</div>';//隐藏内容
} 
}else if($post_power==4){//VIP可见类型
if(!is_vip($user_id)&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、VIP用户
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> 隐藏内容需要开通会员才可以看见</p>
<div class="jinsom-btn opacity" onclick="jinsom_recharge_vip_form()">开通会员</div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_autolink(jinsom_add_lightbox_content($hide_content,$post_id)))).'</div>';//隐藏内容
}

}else if($post_power==5){//登录可见类型
if(!is_user_logged_in()){//没有登录的用户
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> 隐藏内容需要登录才可以看见</p>
<div class="jinsom-btn opacity" onclick="jinsom_pop_login_style();">马上登录</div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_autolink(jinsom_add_lightbox_content($hide_content,$post_id)))).'</div>';//隐藏内容
}
}else if($post_power==6){//回复
if($user_id!=$author_id&&!jinsom_is_comment($user_id,$post_id)&&!jinsom_is_admin($user_id)){//没有回复
?>
<div class="jinsom-tips comment-see">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要回复才可以看见','jinsom');?></p>
<div class="jinsom-btn opacity" onclick="jinsom_comment_toggle(this)"><?php _e('马上回复','jinsom');?></div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
}
}else if($post_power==7){//认证
if($user_id!=$author_id&&!get_user_meta($user_id,'verify',true)&&!jinsom_is_admin($user_id)){//没有认证
?>
<div class="jinsom-tips verify-see">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容仅限认证用户可见','jinsom');?></p>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
}
}else if($post_power==8){//粉丝
if($user_id!=$author_id&&!jinsom_is_follow_author($author_id,$user_id)&&!jinsom_is_admin($user_id)){//没有关注
?>
<div class="jinsom-tips follow-see" data="<?php echo $permalink;?>">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要关注作者才可以看见','jinsom');?></p>
<div class="jinsom-btn opacity" onclick="jinsom_follow(<?php echo $author_id;?>,this);"><i class="jinsom-icon jinsom-guanzhu"></i> <?php _e('关注','jinsom');?></div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
}
}
?>


</div>

<?php require($require_url.'/post/topic-list.php');?>

<?php 
//开启版权
if($single_copyright_info){
echo '<div class="jinsom-single-copyright-info">'.do_shortcode($single_copyright_info).'</div>';
}

//打赏
if($author_id!=$user_id&&$post_status=='publish'){?>
<div class="jinsom-single-reward-btn">
<i class="jinsom-icon opacity jinsom-shang" onclick="jinsom_reward_form(<?php echo $post_id;?>,'post');"></i>
</div>
<?php }?>

<?php jinsom_single_content_end_hook();//自定义内容结束钩子?>

<?php require($require_url.'/post/bar.php' );?>
<?php jinsom_post_like_list($post_id);?>




<!-- 自动文章目录 -->
<script type="text/javascript">

if($('.jinsom-single-content').children('h2').length>0||$('.jinsom-single-content').children('h3').length>0||$('.jinsom-single-content').children('h4').length>0) {
$('#jinsom-single-title-list').show();
}
$(".jinsom-single-content").find("h2,h3,h4").each(function(i,item){
var tag = $(item).get(0).nodeName.toLowerCase();
$(item).attr("id","wow"+i);
$(".jinsom-single-title-list-content ul").append('<li class="jinsom-single-title-'+tag+' jinsom-single-title-link" link="#wow'+i+'">'+$(this).text()+'</li>');
});
$(".jinsom-single-title-link").click(function(){
$("html,body").animate({scrollTop: $($(this).attr("link")).offset().top}, 600);
})

</script>