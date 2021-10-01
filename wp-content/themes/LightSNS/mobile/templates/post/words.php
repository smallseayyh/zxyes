<?php
//动态
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
$author_id=get_the_author_meta('ID');
$post_id=get_the_ID();
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);

$post_price=get_post_meta($post_id,'post_price',true);
$post_power=get_post_meta($post_id,'post_power',true);
$hide_content=get_post_meta($post_id,'pay_cnt',true);//隐藏内容
$post_img=get_post_meta($post_id,'post_img',true);
$reprint_post_id=get_post_meta($post_id,'reprint_post_id',true);	



$content=strip_tags(jinsom_get_post_content($post_id));
$content_number=mb_strlen($content,'utf-8');
$fold_number = jinsom_get_option('jinsom_mobile_content_more_fold_number');

?>
<div class="jinsom-post-words jinsom-post-<?php echo $post_id;?> jinsom-post-author-<?php echo $author_id;?>">

<?php require( get_template_directory() . '/mobile/templates/post/info.php' );?>
<div class="content <?php if($content_number>$fold_number){ echo 'hidden';} ?>">
<?php if(!$reprint_post_id){?>
<h1>
<a href="<?php echo jinsom_mobile_post_url($post_id);?>" class="link"><?php the_title();?></a>
<?php 
if(isset($_GET['author_id'])||isset($_POST['author_id'])){
if(get_user_meta($author_id,'sticky',true)==$post_id){echo '<span class="sticky-member"></span>';}
}else{
if($sticky_post){echo '<span class="sticky"></span>';}	
}
if($commend_post){echo '<span class="commend"></span>';}
?>
</h1>
<?php }?>
<a href="<?php echo jinsom_mobile_post_url($post_id);?>" class="link" style="display: block;">
<?php echo do_shortcode(convert_smilies(wpautop(jinsom_autolink(get_the_content()))));?>
</a>
</div>
<?php if($content_number>$fold_number){echo"<div class='jinsom-post-read-more' onclick='jinsom_moren_content(this)'>".__('查看全文','jinsom')."</div>";}?>
<?php 
if($reprint_post_id){
$reprint_user_id=jinsom_get_user_id_post($reprint_post_id);//获取转载的用户id
$reprint_content = strip_tags(jinsom_get_post_content($reprint_post_id));
$reprint_content = preg_replace("/\[file[^]]+\]/", "[".__('附件','jinsom')."]",$reprint_content);
$reprint_content = preg_replace("/\[video[^]]+\]/", "[".__('视频','jinsom')."]",$reprint_content);
$reprint_content = preg_replace("/\[music[^]]+\]/", "[".__('音乐','jinsom')."]",$reprint_content);
$reprint_content= do_shortcode(convert_smilies(mb_substr($reprint_content,0,100,'utf-8'))).'...';
?>
<div class="jinsom-reprint">
<?php if(get_post_status($reprint_post_id)){?>
<div class="jinsom-reprint-author">
<a href="<?php echo jinsom_mobile_author_url($reprint_user_id);?>" class="link">
<span class="jinsom-reprint-author-name">@<?php echo jinsom_nickname($reprint_user_id);?>:</span>
</a>
</div>
<?php }?>
<a href="<?php echo jinsom_mobile_post_url($reprint_post_id);?>" class="link" style="display: block;">
<?php if(get_the_title($reprint_post_id)!=''){
echo '<div class="jinsom-reprint-title">'.get_the_title($reprint_post_id).'</div>';}?>
<div class="jinsom-reprint-content">
<?php 
if(get_post_status($reprint_post_id)){
echo $reprint_content;
}else{
echo '<p class="jinsom-reprint-had-delete">'.__('此内容已被原作者删除','jinsom').'</p>';
}
?>
</div>
</a>
<?php 
$reprint_post_img=get_post_meta($reprint_post_id,'post_img',true);
if($reprint_post_img){
echo '<div class="jinsom-post-images-list clear">';
echo jinsom_words_img($reprint_post_id,1,9);
echo '</div>';	
}
?>
</div>
<?php }?>


<?php 
if($post_power==1){//付费可见类型
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
?>
<div class="jinsom-tips jinsom-tips-<?php echo $post_id;?>">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要付费才可以看见','jinsom');?></p>
<div class="jinsom-btn opacity" onclick="jinsom_buy_post_form(<?php echo $post_id;?>)"><?php _e('马上购买','jinsom');?></div>
</div>
<?php 
}else{//已经购买||作者
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';
}

}else if($post_power==2){//密码类型
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人
?>
<div class="jinsom-tips jinsom-tips-<?php echo $post_id;?>">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要输入密码才可以看见','jinsom');?></p>
<div class="jinsom-post-password">
<input id="jinsom-post-password">
<span class="opacity" onclick="jinsom_get_password_posts(<?php echo $post_id;?>,this);"><?php _e('查看','jinsom');?></span>
</div>
</div>
<?php }else{ 
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
} 
}else if($post_power==4){//VIP可见类型
if(!is_vip($user_id)&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、VIP用户
?>
<div class="jinsom-tips jinsom-tips-<?php echo $post_id;?>">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要开通会员才可以看见','jinsom');?></p>
<div class="jinsom-btn opacity" onclick="jinsom_recharge_vip_type_form()"><?php _e('开通会员','jinsom');?></div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
}

}else if($post_power==5){//登录可见类型
if(!is_user_logged_in()){//没有登录的用户
?>
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> <?php _e('隐藏内容需要登录才可以看见','jinsom');?></p>
<div class="jinsom-btn open-login-screen opacity"><?php _e('马上登录','jinsom');?></div>
</div>
<?php }else{
echo '<div class="jinsom-hide-content">'.convert_smilies(wpautop(jinsom_autolink($hide_content))).'</div>';//隐藏内容
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

<?php
//图片
if(!empty($post_img)){
echo '<div class="jinsom-post-images-list clear">';
if($post_power==1){
if($pay_result>0||$author_id==$user_id||jinsom_is_admin($user_id)){
echo jinsom_words_img($post_id,1,9);
}else{
echo jinsom_words_img($post_id,0,9);
}
}else if($post_power==2){
if($password_result>0||$author_id==$user_id||jinsom_is_admin($user_id)){
echo jinsom_words_img($post_id,1,9);
}else{
echo jinsom_words_img($post_id,0,9);
}
}else if($post_power==4){
if(is_vip($user_id)||$author_id==$user_id||jinsom_is_admin($user_id)){
echo jinsom_words_img($post_id,1,9);
}else{
echo jinsom_words_img($post_id,0,9);
}
}else if($post_power==5){
if(is_user_logged_in()){
echo jinsom_words_img($post_id,1,9);
}else{
echo jinsom_words_img($post_id,0,9);
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
echo jinsom_words_img($post_id,1,9);
}
echo '</div>';
}
?>

<?php require( get_template_directory() . '/mobile/templates/post/bar.php' );?>
</div>