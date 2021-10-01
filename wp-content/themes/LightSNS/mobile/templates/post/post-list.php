<?php 
//内容列表
$user_id=$current_user->ID;
$author_id=get_the_author_meta('ID');
$post_id=get_the_ID();
$post_type=get_post_meta($post_id,'post_type',true);
$post_power=get_post_meta($post_id,'post_power',true);//内容权限
$post_views=(int)get_post_meta($post_id,'post_views',true);
$is_bbs_post=is_bbs_post($post_id);
$load_type='list';
$require_url=get_template_directory();

if($post_power==3&&!jinsom_is_admin($user_id)&&$user_id!=$author_id){
//require($require_url.'/post/private-no-power.php');
}else{


//置顶、推荐
$commend_post=get_post_meta($post_id,'jinsom_commend',true);
$sticky_post=is_sticky();

$more_type='list';//用于区分当前页面是列表页面还是内页

if(is_page($post_id)){//页面
require(get_template_directory().'/mobile/templates/post/page.php');
}elseif($post_type=='music'){
require(get_template_directory().'/mobile/templates/post/music.php');
}elseif($post_type=='single'){
require(get_template_directory().'/mobile/templates/post/single.php');
}elseif($post_type=='video'){
require(get_template_directory().'/mobile/templates/post/video.php');	
}elseif($post_type=='redbag'){
require(get_template_directory().'/mobile/templates/post/redbag.php');	
}elseif($is_bbs_post){
require(get_template_directory().'/mobile/templates/post/bbs-power.php');
}else{//其他页面使用动态
require(get_template_directory().'/mobile/templates/post/words.php');
}


jinsom_upadte_user_online_time();//更新在线状态


}