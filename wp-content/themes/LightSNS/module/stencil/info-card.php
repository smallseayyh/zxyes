<?php
//资料卡片
require( '../../../../../wp-load.php' );
$jinsom_user_default_desc_a = jinsom_get_option('jinsom_user_default_desc_a');
$jinsom_user_default_desc_b = jinsom_get_option('jinsom_user_default_desc_b');
$user_id=$current_user->ID;
$author_id = $_POST['author_id'];
$user_info = get_userdata($author_id);
?>
<div class="jinsom-info-card">
<div class="jinsom-info-card-bg" style="background-image: url(<?php echo jinsom_member_bg($author_id,'small_img');?>);">
<div class="jinsom-info-card-avatar">
<?php echo jinsom_avatar($author_id,'50',avatar_type($author_id) );?>
<?php echo jinsom_verify($author_id);?>
</div>
<div class="name">
<a href="<?php echo jinsom_userlink($author_id);?>" target="_blank">
<?php echo jinsom_nickname_link($author_id);?>
<?php echo jinsom_sex($author_id); ?>
<?php echo jinsom_honor($author_id);?>
</a>
</div>
<div class="desc">
<?php 
if($user_info->verify==0){
echo '个人说明：';
if($user_id==$author_id){
$description =$user_info->description;
echo $description?$description:$jinsom_user_default_desc_a;
}else{
$description=$user_info->description;
echo $description?$description:$jinsom_user_default_desc_b;
}
}else{
if($user_info->verify==1){
echo '个人认证：';  
}else if($user_info->verify==2){
echo '企业认证：'; 
}else if($user_info->verify==3){
echo '女神认证：'; 
}else if($user_info->verify==4){
echo '达人认证：'; 
}
echo $user_info->verify_info; 
}?>
</div>
</div>
<div class="bar">
<span>关注<i><?php echo jinsom_following_count($author_id);?></i></span> 
<span>粉丝<i><?php echo jinsom_follower_count($author_id);?></i></span> 
<span>喜欢<i><?php echo jinsom_count_post($author_id,'all');?></i></span>
<span>内容<i><?php echo count_user_posts($author_id,'post');?></i></span>
</div>
<div class="city">
<?php if(jinsom_get_option('jinsom_location_on_off')!='no'){?>
<i class="fa fa-map-marker "></i>
<?php if($user_info->city==''){echo '未知';}else{echo $user_info->city;}?>
<?php }?>
</div>

<div class="btn">
<?php 
if($user_id!=$author_id){
if (is_user_logged_in()) { 
echo jinsom_follow_button_home($author_id); ?>
<span onclick="jinsom_open_user_chat(<?php echo $author_id;?>,this);" class="opacity chat"><i class="jinsom-icon jinsom-liaotian"></i> 聊天</span>
 <?php }else{?>
<span class="follow no opacity" onclick='jinsom_pop_login_style();'><i class="jinsom-icon jinsom-guanzhu"></i>关注</span>
<span onclick='jinsom_pop_login_style();' class="opacity"><i class="jinsom-icon jinsom-liaotian"></i> 聊天</span>
<?php }}?>
</div>


</div>