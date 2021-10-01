<?php 
$require_url=get_template_directory();
$user_id=$current_user->ID;
$post_id=get_the_ID();
$author_id=get_the_author_meta('ID');
$title=get_the_title();
$content_source=get_the_content();
$content=$content_source;
//$content = apply_filters('the_content', $content);
$permalink=get_the_permalink();
$post_time=get_the_time('Y-m-d H:i:s');
$post_status=get_post_status();

//人机验证
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
$jinsom_machine_verify_appid=jinsom_get_option('jinsom_machine_verify_appid');

$post_power=get_post_meta($post_id,'post_power',true);//内容权限
$post_type=get_post_meta($post_id,'post_type',true);//内容类型
if(!$post_type){$post_type='words';}
$reprint=get_post_meta($post_id,'reprint_post_id',true);
$post_views=(int)get_post_meta($post_id,'post_views',true);//浏览量
$is_bbs_post=is_bbs_post($post_id);
if($is_bbs_post){
$post_type='normal';
}


if($post_power==3&&!jinsom_is_admin($user_id)&&$user_id!=$author_id){
//require($require_url.'/post/private-no-power.php');
}else{



//推荐 置顶
$commend_post=get_post_meta($post_id,'jinsom_commend',true);
$sticky_post=is_sticky();
$fold_number = (int)jinsom_get_option('jinsom_publish_posts_cnt_fold_number');//内容折叠字数


if(is_single()){
if($post_status=='pending'){
echo '<div class="jinsom-no-power-tips"><i class="fa fa-warning"></i> '.__('该内容处于审核中状态','jinsom').'</div>';
}else if($post_status=='draft'){
echo '<div class="jinsom-no-power-tips"><i class="fa fa-warning"></i> '.__('内容处于驳回状态，需重新编辑进行提交审核','jinsom').'</div>';	
}
}
?>
<div  class="jinsom-posts-list <?php echo $post_type;?> power-<?php echo $post_power;?>" data="<?php echo $post_id;?>" id="jinsom-post-<?php echo $post_id;?>">

<?php if($post_type=='single'&&is_single()&&$post_status=='publish'){?>
<div class="jinsom-single-left-bar">
<?php if(jinsom_is_like_post($post_id,$user_id)){?>
<li onclick="jinsom_single_sidebar_like(<?php echo $post_id;?>,this)" class="jinsom-had-like"><i class="jinsom-icon jinsom-xihuan1"></i></li>
<?php }else{?>
<li onclick="jinsom_single_sidebar_like(<?php echo $post_id;?>,this)" class="jinsom-no-like"><i class="jinsom-icon jinsom-xihuan2"></i></li>
<?php }?>
<li id="jinsom-single-title-list"><i class="jinsom-icon jinsom-mulu1"></i><div class="jinsom-single-title-list-content"><ul></ul></div></li>
<?php if (is_user_logged_in()) {?>
<li onclick="$('.jinsom-post-comments').focus();"><i class="jinsom-icon jinsom-pinglun2"></i></li>
<?php }else{?>
<li onclick="jinsom_pop_login_style();"><i class="jinsom-icon jinsom-pinglun2"></i></li>
<?php }?>
<li onclick='jinsom_reprint_form(<?php echo $post_id;?>);'><i class="jinsom-icon jinsom-zhuanzai"></i></li>
</div>
<?php }?>

<?php 

if(empty($post_power)){$post_power=0;}

if(is_page($post_id)){//页面
require($require_url.'/post/page.php' );
}elseif($post_type=='music'){
require($require_url.'/post/music.php' );
}elseif($post_type=='single'){
require($require_url.'/post/single.php' );	
}elseif($post_type=='video'){
require($require_url.'/post/video.php' );	
}elseif($post_type=='redbag'){
require($require_url.'/post/redbag.php' );	
}elseif($is_bbs_post){
require($require_url.'/post/bbs.php' );//论坛
}else{//其他页面使用动态
require($require_url.'/post/words.php' );
}

if(($post_type!='single'||is_single()||is_page())&&!$is_bbs_post){
$comment_on_off=get_post_meta($post_id,'comment_on_off',true);//允许评论
if(empty($comment_on_off)){$comment_on_off='true';}
if($comment_on_off=='true'&&$post_status=='publish'){
require($require_url.'/module/stencil/comments.php');//引人评论模块 
}
}

?>

<?php if(!is_page()&&!$is_bbs_post&&$post_type!='single'){?>
<div class="jinsom-post-footer-bar">
<span title="<?php echo $post_time;?>">
<?php echo $time;?>
</span>
<?php 
echo jinsom_post_from($post_id);//来自

if($reprint){
echo '<i class="jinsom-icon jinsom-zhuanzai" title="'.__('转载内容','jinsom').'"></i>';
}else{
if($post_power==1){
echo '<i class="jinsom-icon jinsom-fufei" title="'.__('付费内容','jinsom').'"></i>';
}else if($post_power==2){
echo '<i class="jinsom-icon jinsom-mima" title="'.__('密码可见内容','jinsom').'"></i>';
}else if($post_power==3){
echo '<i class="jinsom-icon jinsom-biyan" title="'.__('私密内容','jinsom').'"></i>';
}else if($post_power==4){
echo '<i class="jinsom-icon jinsom-vip-type" title="'.__('VIP可见内容','jinsom').'"></i>';
}else if($post_power==5){
echo '<i class="jinsom-icon jinsom-denglu" title="'.__('登录可见内容','jinsom').'"></i>';
}else{
echo '<i class="jinsom-icon jinsom-gongkai1" title="'.__('公开内容','jinsom').'"></i>';
}
}
?>
</div>
<?php }?>

</div><!-- posts_list -->


<?php }?>