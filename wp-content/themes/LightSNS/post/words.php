<?php 
//动态列表

$post_img=get_post_meta($post_id,'post_img',true);//图数据
$hide_content=get_post_meta($post_id,'pay_cnt',true);//隐藏内容
$content_number=mb_strlen(strip_tags($content),'utf-8');//内容

update_post_meta($post_id,'post_views',($post_views+1));//更新内容浏览量

if(($sticky_post||$commend_post)&&!$title){//如果不存在标题 才显示时光轴的知道和推荐图标
if($sticky_post&&$commend_post){
echo '<div class="jinsom-post-top-time"><span class="top"></span><span class="commend"></span></div>';
}else if($sticky_post) {
echo '<div class="jinsom-post-top-time"><span class="top"></span></div>';
}else{
echo '<div class="jinsom-post-top-time"><span class="commend"></span></div>';	
}
}

require($require_url.'/post/info.php' );//引入头部信息

if($title){//标题
$commend_html='';
// if($sticky_post){$commend_html.='<span class="jinsom-mark jinsom-top"></span>';}

if(isset($_GET['author_id'])||isset($_POST['author_id'])||$is_author){
if(get_user_meta($author_id,'sticky',true)==$post_id){$commend_html.='<span class="jinsom-mark jinsom-member-top"></span>';}
}else{
if($sticky_post){$commend_html.='<span class="jinsom-mark jinsom-top"></span>';}	
}

if($commend_post){$commend_html.='<span class="jinsom-mark jinsom-commend-icon"></span>';}
if(!is_single()&&!is_page()){
echo '<h2><a href="'.$permalink.'" target="_blank">'.$title.'</a>'.$commend_html.'</h2>';
}else{
echo '<h1>'.$title.$commend_html.'</h1>';
}  
}

?>


<div class="jinsom-post-content <?php if($content_number>$fold_number&&!is_single()&&!is_page()){ echo 'hidden';} ?>">
<?php 
if(is_single()){
jinsom_title_bottom_hook();//标题下方的钩子
}

if(!is_single()&&(!is_page()||is_page()&&get_page_template_slug(get_queried_object_id())=='page/layout-sns.php')){
echo'<a class="post_list_link" href="'.$permalink.'" target="_blank">'.do_shortcode(convert_smilies(wpautop(jinsom_autolink($content)))).'</a>';
}else{
if(is_page()){
echo do_shortcode(convert_smilies(wpautop($content)));
}else{
echo do_shortcode(convert_smilies(wpautop(jinsom_autolink($content))));
}
}

?>
</div>
<?php
//阅读更多
if($content_number>$fold_number&&!is_single()&&!is_page()){
echo'<div class="jinsom-post-read-more">'.__('查看全文','jinsom').'</div>';
}
?>


<?php 
if($post_power==1){//付费可见类型
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要付费才可以看见','jinsom');?></p>
<div class="jinsom-btn opacity" onclick="jinsom_show_pay_form(<?php echo $post_id;?>);"><?php _e('马上购买','jinsom');?></div>
</div>
<?php 
}else{//已经购买||作者
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';
}

}else if($post_power==2){//密码类型
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要输入密码才可以看见','jinsom');?></p>
<div class="jinsom-post-password">
<input id="jinsom-post-password">
<span class="opacity" onclick="jinsom_get_password_posts(<?php echo $post_id;?>,'page',this);"><?php _e('查看','jinsom');?></span>
</div>
</div>
<?php }else{ 
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
} 
}else if($post_power==4){//VIP可见类型
if(!is_vip($user_id)&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、VIP用户
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要开通会员才可以看见','jinsom');?></p>
<div class="jinsom-btn opacity" onclick="jinsom_recharge_vip_form()"><?php _e('开通会员','jinsom');?></div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
}

}else if($post_power==5){//登录可见类型
if(!is_user_logged_in()){//没有登录的用户
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要登录才可以看见','jinsom');?></p>
<div class="jinsom-btn opacity" onclick="jinsom_pop_login_style();"><?php _e('马上登录','jinsom');?></div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
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



<?php 
if($reprint){
$reprint_user_id=jinsom_get_user_id_post($reprint);//获取转载的用户id
$reprint_content = strip_tags(jinsom_get_post_content($reprint));
$reprint_content = preg_replace("/\[file[^]]+\]/", "[附件]",$reprint_content);
$reprint_content = preg_replace("/\[video[^]]+\]/", "[视频]",$reprint_content);
$reprint_content = preg_replace("/\[music[^]]+\]/", "[音乐]",$reprint_content);

$source_post='<a class="jinsom-reprint-more" href="'.get_the_permalink($reprint).'" target="_blank">（'.__('查看原文','jinsom').'）</a>';
$reprint_content= convert_smilies(mb_substr($reprint_content,0,140,'utf-8')).$source_post;
?>
<div class="jinsom-reprint">
<?php if(get_post_status($reprint)){?>
<div class="jinsom-reprint-author">
<a href="<?php echo jinsom_userlink($reprint_user_id);?>" target="_blank">
<span class="jinsom-reprint-author-name">@<?php echo get_user_meta($reprint_user_id,'nickname',true);?></span>
<?php echo jinsom_lv($reprint_user_id);?>
<?php echo jinsom_vip($reprint_user_id);?>
<?php echo jinsom_honor($reprint_user_id);?>
</a>
</div>
<?php }?>
<?php if(get_the_title($reprint)!=''){
echo '<div class="jinsom-reprint-title">'.get_the_title($reprint).'</div>';}?>
<div class="jinsom-reprint-content">
<?php 
if(get_post_status($reprint)&&get_post_status($reprint)!='trash'&&get_post_status($reprint)!='private'){
echo $reprint_content;
}else{
if((current_user_can('level_10')||$author_id==$user_id)&&get_post_status($reprint)=='private'){
echo $reprint_content;
}else{
echo '<p class="jinsom-reprint-had-delete">'.__('此内容已被原作者删除或设为私密','jinsom').'</p>';	
}
}
?>

</div>
<?php 
$reprint_post_img=get_post_meta($reprint,'post_img',true);
if($reprint_post_img){
echo '<div class="jinsom-post-images-list clear">';
echo jinsom_words_img($reprint,1,99);
echo '</div>';	
}
?>
</div>
<?php }?>


<?php 


//图片
if(!empty($post_img)){
echo '<div class="jinsom-post-images-list clear">';
if($post_power==1){
if($pay_result>0||$author_id==$user_id||jinsom_is_admin($user_id)){
echo jinsom_words_img($post_id,1,99);
}else{
echo jinsom_words_img($post_id,0,99);
}
}else if($post_power==2){
if($password_result>0||$author_id==$user_id||jinsom_is_admin($user_id)){
echo jinsom_words_img($post_id,1,99);
}else{
echo jinsom_words_img($post_id,0,99);
}
}else if($post_power==4){
if(is_vip($user_id)||$author_id==$user_id||jinsom_is_admin($user_id)){
echo jinsom_words_img($post_id,1,99);
}else{
echo jinsom_words_img($post_id,0,99);
}
}else if($post_power==5){
if(is_user_logged_in()){
echo jinsom_words_img($post_id,1,99);
}else{
echo jinsom_words_img($post_id,0,99);
}
}else if($post_power==6){//回复
if($user_id==$author_id||jinsom_is_comment($user_id,$post_id)||jinsom_is_admin($user_id)){
echo jinsom_words_img($post_id,1,99);
}else{
echo jinsom_words_img($post_id,0,99);
}
}else if($post_power==7){//认证
if($user_id==$author_id||get_user_meta($user_id,'verify',true)||jinsom_is_admin($user_id)){
echo jinsom_words_img($post_id,1,99);
}else{
echo jinsom_words_img($post_id,0,99);
}
}else if($post_power==8){//粉丝
if($user_id==$author_id||jinsom_is_follow_author($author_id,$user_id)||jinsom_is_admin($user_id)){
echo jinsom_words_img($post_id,1,99);
}else{
echo jinsom_words_img($post_id,0,99);
}
}else{
echo jinsom_words_img($post_id,1,99);
}
echo '</div>';
}

require($require_url.'/post/topic-list.php');//话题列表

if(is_single()){
jinsom_single_content_end_hook();//自定义内容结束钩子
}

require($require_url.'/post/bar.php' );//内容底部栏
jinsom_post_like_list($post_id);//喜欢列表



