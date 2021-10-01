<?php 
//内容列表
$user_id=$current_user->ID;
$author_id=get_the_author_meta('ID');
$post_id=get_the_ID();
$post_type=get_post_meta($post_id,'post_type',true);
$post_power=get_post_meta($post_id,'post_power',true);//内容权限
$post_views=(int)get_post_meta($post_id,'post_views',true);
$is_bbs_post=is_bbs_post($post_id);
// $load_type='list';
$require_url=get_template_directory();

if($post_power==3&&!jinsom_is_admin($user_id)&&$user_id!=$author_id){
// require($require_url.'/post/private-no-power.php');
}else{


//置顶、推荐
$commend_post=get_post_meta($post_id,'jinsom_commend',true);
$sticky_post=is_sticky();


if(jinsom_is_like_post($post_id,$user_id)){
$like='<span class="like" onclick="jinsom_select_like('.$post_id.',this)"><i class="jinsom-icon jinsom-xihuan1 had"></i> <span>'.jinsom_count_post(0,$post_id).'</span></span>';	
}else{
$like='<span class="like" onclick="jinsom_select_like('.$post_id.',this)"><i class="jinsom-icon jinsom-xihuan2"></i> <span>'.jinsom_count_post(0,$post_id).'</span></span>';
}	

$content=get_the_content();
$title=get_the_title();
if(!$title){
$content=str_replace(array("\r\n","\r","\n"),"",$content); 
$title=mb_substr($content,0,26,'utf-8');
$title=convert_smilies($content);
// $content=str_replace(" ","",$content);

}

//封面
$img=get_template_directory_uri().'/images/default-cover.jpg';
if(is_bbs_post($post_id)){//帖子
$img=jinsom_bbs_cover($content);
}else{
if($post_type=='words'){
$post_img=get_post_meta($post_id,'post_img',true);
if($post_img){
$post_img_arr=explode(",",$post_img);
$img=$post_img_arr[0];	
}else{
$words_cover=jinsom_get_option('jinsom_publish_words_cover');
if($words_cover){
$img=$words_cover;
}
}
}else if($post_type=='video'){
$img=jinsom_video_cover($post_id);	
}else if($post_type=='single'){
$img=jinsom_single_cover($content);	
}else if($post_type=='music'){
$music_cover=jinsom_get_option('jinsom_publish_music_cover');
if($music_cover){
$img=$music_cover;
}
}else if($post_type=='redbag'){
$redbag_cover=jinsom_get_option('jinsom_publish_redbag_cover');
if($redbag_cover){
$img=$redbag_cover;
}
}
}
$img_style=jinsom_get_option('jinsom_upload_style_oss_bbs_list');//图片样式规则
if($img_style){
$img=$img.$img_style;
}

//打开权限
$post_link=jinsom_mobile_post_url($post_id);
if($is_bbs_post){
require($require_url.'/mobile/templates/post/post-list-waterfall-bbs-power.php');
}else{
if($post_type=='video'){
$child_name='<span class="bbs-name">视频</span>';
}else{
$child_name='';	
}
}


$post_price=(int)get_post_meta($post_id,'post_price',true);
if($post_price){
$post_price='<span class="price">'.$post_price.jinsom_get_option('jinsom_credit_name').'</span>';
}else{
$post_price='';
}

echo '
<li class="'.$post_type.' waterfall">
'.$post_price.$child_name.'
<div class="img"><a href="'.$post_link.'" class="link"><img src="'.$img.'"></a></div>
<div class="title"><a href="'.$post_link.'" class="link">'.$title.'</a></div>
<div class="info">
<span class="avatarimg">'.jinsom_avatar($author_id,'40',avatar_type($author_id)).'</span>
<span class="nickname">'.jinsom_nickname_link($author_id).'</span>
'.$like.'
</div>
</li>
';





jinsom_upadte_user_online_time();//更新在线状态
}