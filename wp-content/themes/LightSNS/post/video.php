<?php 
//视频列表

$time_a=time ()- (1*24*60*60);
if(get_the_time('Y-m-d')==date('Y-m-d',time())){
$time= '今天 '.get_the_time('H:i');
}else if(get_the_time('Y-m-d')==date('Y-m-d',$time_a)){
$time= '昨天 '.get_the_time('H:i');
}else{
$time= get_the_time('m-d H:i');
}

$video_url=get_post_meta($post_id,'video_url',true);//视频地址
$video_img=jinsom_video_cover($post_id);//视频封面
$video_lists=get_post_meta($post_id,'video_lists',true);//集数
$content_number=mb_strlen(strip_tags($content),'utf-8');//内容
update_post_meta($post_id,'post_views',($post_views+1));//更新内容浏览量


if(($sticky_post||$commend_post)&&!$title){//如果不存在标题 才显示时光轴的知道和推荐图标
if($sticky_post&&$commend_post){
echo '<div class="jinsom-post-top-time"><span class="top"></span><span class="commend"></span></div>';
}else if($sticky_post){
echo '<div class="jinsom-post-top-time"><span class="top"></span></div>';
}else{
echo '<div class="jinsom-post-top-time"><span class="commend"></span></div>';
}
}

if(!is_single()){require($require_url.'/post/info.php' );}//引入头部信息
?>


<div class="jinsom-post-video">
<?php 
if($post_power==1){//付费可见类型
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要付费才可以播放','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_show_pay_form(<?php echo $post_id;?>)"><?php _e('马上购买','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div id="jinsom-video-<?php echo $post_id;?>" post_id="<?php echo $post_id;?>"></div>
<script type="text/javascript">
jinsom_post_video(<?php echo $post_id;?>,'<?php echo $video_url;?>','<?php echo jinsom_video_cover($post_id);?>',false);
</script>
<?php }?>
<?php }else if($post_power==2){//密码类型
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要密码才可以播放','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_music_password_form(<?php echo $post_id;?>)"><?php _e('输入密码','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div id="jinsom-video-<?php echo $post_id;?>" post_id="<?php echo $post_id;?>"></div>
<script type="text/javascript">
jinsom_post_video(<?php echo $post_id;?>,'<?php echo $video_url;?>','<?php echo jinsom_video_cover($post_id);?>',false);
</script>
<?php }?>
<?php }else if($post_power==4){//VIP可见类型
if(!is_vip($user_id)&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、VIP用户
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要开通会员才可以播放','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_recharge_vip_form()"><?php _e('马上开通','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div id="jinsom-video-<?php echo $post_id;?>" post_id="<?php echo $post_id;?>"></div>
<script type="text/javascript">
jinsom_post_video(<?php echo $post_id;?>,'<?php echo $video_url;?>','<?php echo jinsom_video_cover($post_id);?>',false);
</script>
<?php }?>
<?php }else if($post_power==5){//登录可见类型
if(!is_user_logged_in()){//没有登录的用户
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要登录才可以播放','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_pop_login_style()"><?php _e('马上登录','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div id="jinsom-video-<?php echo $post_id;?>" post_id="<?php echo $post_id;?>"></div>
<script type="text/javascript">
jinsom_post_video(<?php echo $post_id;?>,'<?php echo $video_url;?>','<?php echo jinsom_video_cover($post_id);?>',false);
</script>
<?php }?>
<?php }else if($post_power==6){//回复
if($user_id!=$author_id&&!jinsom_is_comment($user_id,$post_id)&&!jinsom_is_admin($user_id)){//没有回复
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content comment-see">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要回复之后才可以播放','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_comment_toggle(this)"><?php _e('马上回复','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div id="jinsom-video-<?php echo $post_id;?>" post_id="<?php echo $post_id;?>"></div>
<script type="text/javascript">
jinsom_post_video(<?php echo $post_id;?>,'<?php echo $video_url;?>','<?php echo jinsom_video_cover($post_id);?>',false);
</script>
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
<div id="jinsom-video-<?php echo $post_id;?>" post_id="<?php echo $post_id;?>"></div>
<script type="text/javascript">
jinsom_post_video(<?php echo $post_id;?>,'<?php echo $video_url;?>','<?php echo jinsom_video_cover($post_id);?>',false);
</script>
<?php }?>
<?php }else if($post_power==8){//关注
if($user_id!=$author_id&&!jinsom_is_follow_author($author_id,$user_id)&&!jinsom_is_admin($user_id)){//没有关注
?>
<div class="jinsom-video-tips" style="background-image:url(<?php echo $video_img;?>);">
<div class="jinsom-video-tips-shade"></div>
<div class="jinsom-video-tips-content follow-see" data="<?php echo $permalink;?>">
<div class="text"><i class="jinsom-icon jinsom-niming"></i> <?php _e('该视频需要关注作者才可以播放','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_follow(<?php echo $author_id;?>,this)"><i class="jinsom-icon jinsom-guanzhu"></i> <?php _e('关注','jinsom');?></div>
</div>
</div>
<?php }else{?>
<div id="jinsom-video-<?php echo $post_id;?>" post_id="<?php echo $post_id;?>"></div>
<script type="text/javascript">
jinsom_post_video(<?php echo $post_id;?>,'<?php echo $video_url;?>','<?php echo jinsom_video_cover($post_id);?>',false);
</script>
<?php }?>
<?php }else{?>
<div id="jinsom-video-<?php echo $post_id;?>" post_id="<?php echo $post_id;?>"></div>
<script type="text/javascript">
jinsom_post_video(<?php echo $post_id;?>,'<?php echo $video_url;?>','<?php echo jinsom_video_cover($post_id);?>',false);
</script>
<?php }?>

</div><!-- jinsom-post-video -->


<?php 
if($title){//标题
$commend_html='';
if($sticky_post){$commend_html.='<span class="jinsom-mark jinsom-top"></span>';}
if($commend_post){$commend_html.='<span class="jinsom-mark jinsom-commend-icon"></span>';}
if(!is_single()&&!is_page()){
echo '<h2><a href="'.$permalink.'" target="_blank">'.$title.'</a>'.$commend_html.'</h2>';
}else{
echo '<h1>'.$title.$commend_html.'</h1>';
}  
}
?>


<?php if(is_single()){

jinsom_title_bottom_hook();//标题下方的钩子

if($video_lists){//合集
echo '<div class="jinsom-single-video-lists">';
$video_lists_arr=explode(",",$video_lists);
foreach ($video_lists_arr as $data) {
$data_arr=explode("|",$data);
if($data_arr[1]==$post_id){$on='class="on"';}else{$on='';}
$video_lists_power=get_post_meta($data_arr[1],'post_power',true);
if($video_lists_power==1){
$video_lists_power='<i class="jinsom-icon jinsom-fufei" title="'.__('付费可见','jinsom').'"></i>';
}else if($video_lists_power==2){
$video_lists_power='<i class="jinsom-icon jinsom-mima" title="'.__('密码可见','jinsom').'"></i>';
}else if($video_lists_power==4){
$video_lists_power='<i class="jinsom-icon jinsom-vip-type" title="'.__('会员可见','jinsom').'"></i>';
}else if($video_lists_power==5){
$video_lists_power='<i class="jinsom-icon jinsom-denglu" title="'.__('登录可见','jinsom').'"></i>';
}else{
$video_lists_power='';
}
echo '<a href="'.get_the_permalink($data_arr[1]).'" '.$on.'>'.$video_lists_power.$data_arr[0].'</a>';
}
echo '</div>';
}
?>




<div class="jinsom-single-video-author-info">
<div class="avatarimg">
<?php echo jinsom_vip_icon($author_id);?>
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
<?php echo jinsom_verify($author_id);?>
</div>
<div class="info">
<div class="name">
<?php echo jinsom_nickname_link($author_id);?>
<?php echo jinsom_lv($author_id);?>
<?php echo jinsom_vip($author_id);?>
<?php echo jinsom_honor($author_id);?>
</div>
<div class="time"><?php _e('发表于','jinsom');?> <?php echo $time;?></div>
</div>
<?php echo jinsom_follow_button_home($author_id);?>

<div class="jinsom-post-setting">
<i class="jinsom-icon jinsom-gengduo2"></i>
<div  class="jinsom-post-setting-box">
<?php if(!is_single()&&!is_page()){?>
<li onclick="jinsom_post_link(this);" data="<?php echo $permalink; ?>"><?php _e('查看全文','jinsom');?></li>
<?php }?>
<li onclick="jinsom_post_link(this);" data="<?php echo jinsom_userlink($author_id);?>"><?php _e('查看作者','jinsom');?></li>
<?php 
if (is_user_logged_in()) { 
if(jinsom_is_admin($user_id)){

if($sticky_post){
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"sticky-all",this)\'>'.__('取消全局','jinsom').'</li>';
}else{
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"sticky-all",this)\'>'.__('全局置顶','jinsom').'</li>';
}

if($commend_post){
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"commend-post",this)\'>'.__('取消推荐','jinsom').'</li>';
}else{
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"commend-post",this)\'>'.__('推荐内容','jinsom').'</li>';
}

}

if($user_id!=$author_id){
if(jinsom_is_blacklist($user_id,$author_id)){
echo '<li onclick=\'jinsom_add_blacklist("remove",'.$author_id.',this)\'>'.__('取消拉黑','jinsom').'</li>';	
}else{
echo '<li onclick=\'jinsom_add_blacklist("add",'.$author_id.',this)\'>'.__('拉黑名单','jinsom').'</li>';	
}
}

if(jinsom_is_admin_x($user_id)||$user_id==$author_id){
$edit_time=(int)jinsom_get_option('jinsom_edit_time_max');
$single_time=strtotime(get_the_time('Y-m-d H:i:s'));
if(time()-$single_time<=60*60*$edit_time||jinsom_is_admin_x($user_id)){
echo '<li onclick=\'jinsom_editor_form("'.$post_type.'",'.$post_id.')\'>'.__('编辑内容','jinsom').'</li>';	
}

echo '<li onclick="jinsom_delete_post('.$post_id.',this)">'.__('删除内容','jinsom').'</li>';
}
}?>
</div>
</div>


</div>
<?php }?>




<div class="jinsom-post-content <?php if($content_number>$fold_number&&!is_single()&&!is_page()){ echo 'hidden';} ?>">
<?php        
//内容
if(!is_single()&&(!is_page()||is_page()&&get_page_template_slug(get_queried_object_id())=='page/layout-sns.php')){
echo'<a class="post_list_link" href="'.$permalink.'" target="_blank">'.do_shortcode(convert_smilies(wpautop(jinsom_autolink($content)))).'</a>';
}else{
echo do_shortcode(convert_smilies(wpautop(jinsom_autolink($content))));
}
?>



</div>




<?php 
//阅读更多
if($content_number>$fold_number&&!is_single()&&!is_page()){
echo"<div class='jinsom-post-read-more'>查看全文</div>";
}
?>

<?php 
require($require_url.'/post/topic-list.php');//话题列表
if(is_single()){
jinsom_single_content_end_hook();//自定义内容结束钩子
}
require($require_url.'/post/bar.php' );//内容底部栏
jinsom_post_like_list($post_id);//喜欢列表
