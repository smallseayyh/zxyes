<?php 

//注册短代码
function jinsom_register_shortcodes(){
add_shortcode('user_info', 'jinsom_shortcodes_user_info');//获取用户的信息
add_shortcode('power', 'jinsom_shortcodes_power');//权限可见类
add_shortcode('link', 'jinsom_shortcodes_link');//链接类
add_shortcode('page', 'jinsom_shortcodes_page');//页面模版
add_shortcode('post_info', 'jinsom_shortcodes_post_info');//获取文章相关信息

add_shortcode('site_name', 'jinsom_shortcodes_site_name');//网站名称
add_shortcode('bbs_name', 'jinsom_shortcodes_bbs_name');//论坛名称
add_shortcode('bbs_link', 'jinsom_shortcodes_bbs_link');//论坛链接
add_shortcode('search_name', 'jinsom_shortcodes_search_name');
add_shortcode('topic_name', 'jinsom_shortcodes_topic_name');
add_shortcode('topic_link', 'jinsom_shortcodes_topic_link');

add_shortcode('file', 'jinsom_shortcodes_file');
add_shortcode('video', 'jinsom_shortcodes_video');
add_shortcode('music', 'jinsom_shortcodes_music');
add_shortcode('img', 'jinsom_shortcodes_img');
add_shortcode('btn', 'jinsom_shortcodes_btn');
add_shortcode('goods', 'jinsom_shortcodes_goods');
}
add_action( 'init', 'jinsom_register_shortcodes');


//获取当前用户的信息
function jinsom_shortcodes_user_info($atts){
extract(shortcode_atts(array('type' =>'','id' =>''), $atts));
if($id){
if($id=='member'){//当前主页的用户信息
global $wp_query;
$curauth = $wp_query->get_queried_object();
$current_user_id=$curauth->ID;
}else{//指定的用户信息
$current_user_id=$id;
}
}else{//当前登录的用户信息
global $current_user;
$current_user_id=$current_user->ID;
}

if($type=='id'){//ID
return $current_user_id;
}else if($type=='avatar'){//头像
return jinsom_avatar($current_user_id,'40',avatar_type($current_user_id));
}else if($type=='avatar_url'){//头像地址
return jinsom_avatar_url($current_user_id,avatar_type($current_user_id));
}else if($type=='nickname'){//昵称
return get_user_meta($current_user_id,'nickname',true);	
}else if($type=='description'){//签名
return get_user_meta($current_user_id,'description',true);	
}else if($type=='nickname_link'){//带主页链接的昵称
return jinsom_nickname_link($current_user_id);	
}else if($type=='link'){//主页链接
if(wp_is_mobile()){
return jinsom_mobile_author_url($current_user_id);
}else{
return jinsom_userlink($current_user_id);
}
}else if($type=='small_img'){//背景图-小
return jinsom_member_bg($current_user_id,'big_img');	
}else if($type=='exp'){//经验
return get_user_meta($current_user_id,'exp',true);	
}else if($type=='vip_number'){//成长值
return get_user_meta($current_user_id,'vip_number',true);	
}else if($type=='charm'){//魅力值
return get_user_meta($current_user_id,'charm',true);	
}else if($type=='task_times'){//累计完成的任务数
return get_user_meta($current_user_id,'task_times',true);	
}else if($type=='credit'){//金币
return get_user_meta($current_user_id,'credit',true);	
}else if($type=='follower'){//粉丝数
return jinsom_follower_count($current_user_id);
}else if($type=='following'){//关注数
return jinsom_following_count($current_user_id);
}else if($type=='lv'){//等级标志
return jinsom_lv($current_user_id);
}else if($type=='vip'){//vip标志
return jinsom_vip($current_user_id);
}else if($type=='verify'){//认证标志
return jinsom_verify($current_user_id);
}else if($type=='honor'){//头衔
return jinsom_honor($current_user_id);
}else if($type=='notice'){//未读消息
if($current_user_id){
$tips_number=(int)jinsom_all_notice_number();
$jinsom_chat_notice=(int)jinsom_get_my_all_unread_msg();
$all_tip_number=$tips_number+$jinsom_chat_notice;
if($all_tip_number){
return '<span class="badge bg-red tips">'.$all_tip_number.'</span>';
}
}
}
}


//权限可见类
function jinsom_shortcodes_power($atts,$content){
extract(shortcode_atts(array('type' =>''), $atts));
if($type=='login'){//登录可见
if(is_user_logged_in()){
return $content;	
}
}else if($type=='nologin'){//没登录可见
if(!is_user_logged_in()){
return $content;	
}
}else if($type=='vip'){//VIP可见
global $current_user;
if(is_vip($current_user->ID)){
return $content;	
}
}else if($type=='novip'){//非VIP可见
global $current_user;
if(!is_vip($current_user->ID)){
return $content;	
}
}else if($type=='verify'){//认证用户可见
global $current_user;
if(get_user_meta($current_user->ID,'verify',true)){
return $content;	
}
}else if($type=='noverify'){//非认证用户可见
global $current_user;
if(!get_user_meta($current_user->ID,'verify',true)){
return $content;	
}
}else if($type=='nosign'){//没签到的用户可见
global $current_user;
if(!jinsom_is_sign($current_user->ID,date('Y-m-d',time()))){
return $content;	
}
}else if($type=='superadmin'){//超级管理员可见
if(current_user_can('level_10')){
return $content;	
}
}else if($type=='admin'){//网站管理+超级管理可见
global $current_user;
if(jinsom_is_admin($current_user->ID)){
return $content;	
}
}else if($type=='home'){//首页可见
if(is_home()){
return $content;	
}
}else if($type=='nohome'){//不是首页可见
if(!is_home()){
return $content;	
}
}else if($type=='single'){//文章页面可见
if(is_single()||is_page()){
return $content;	
}
}else if($type=='nosingle'){//非文章页面可见
if(!(is_single()||is_page())){
return $content;	
}
}else if($type=='author'){//用户主页可见
if(is_author()){
return $content;	
}
}else if($type=='mobile'){//移动端可见
if(wp_is_mobile()){
return $content;	
}
}else if($type=='pc'){//电脑端可见
if(!wp_is_mobile()){
return $content;	
}
}
}



//页面路径
function jinsom_shortcodes_link($atts){
extract(shortcode_atts(array('type' =>''), $atts));
if($type=='module_public_page'){
return content_url('/module/public/page');
}else if($type=='module_mobile_page'){
return content_url('/module/mobile/page');
}else if($type=='module'){
return content_url('/module');
}else if($type=='theme'){
return get_template_directory_uri();
}else if($type=='mobile_page'){
return get_template_directory_uri().'/mobile/templates/page';	
}else if($type=='home'){
return home_url();	
}else if($type=='require_home'){
return $_SERVER['DOCUMENT_ROOT'];	
}
}

//页面模版
function jinsom_shortcodes_page($atts){
extract(shortcode_atts(array('id' =>''), $atts));
if($id){
if(wp_is_mobile()){
if(get_option('permalink_structure')){
$url=get_the_permalink($id);	
}else{
$url='no';	
}

$page_templates=get_post_meta($id,'_wp_page_template',true);
return get_template_directory_uri().'/mobile/templates/page/post-page.php?post_id='.$id.'&url='.$url.'&page_template='.$page_templates;

}else{
return get_the_permalink($id);
}
}else{
return '';
}
}



//获取文章信息
function jinsom_shortcodes_post_info($atts){
extract(shortcode_atts(array('type' =>'','id' =>''), $atts));
if($id){
$current_post_id=$id;
}else{
global $post;
$current_post_id=$post->ID;
}

if($type=='id'){
return $current_post_id;
}else if($type=='title'){
$title=get_the_title($current_post_id);
if(!$title){
if($id){
$content=jinsom_get_post_content($id);
}else{
$content=$post->post_content;
}
$content=strip_tags($content);
$title=mb_substr($content,0,30,'utf-8');
}
return $title;
}else if($type=='link'){//文章地址
if(wp_is_mobile()){
return jinsom_mobile_post_url($current_post_id);
}else{
return get_the_permalink($current_post_id);
}
}else if($type=='author_nickname'){
if($id){
$author_id=jinsom_get_post_author_id($id);//根据文章id或者作者id
}else{
$author_id=$post->post_author;	
}
return get_user_meta($author_id,'nickname',true);
}else if($type=='author_link'){//作者主页url
if($id){
$author_id=jinsom_get_post_author_id($id);//根据文章id或者作者id
}else{
$author_id=$post->post_author;	
}
if(wp_is_mobile()){
return jinsom_mobile_author_url($author_id);
}else{
return jinsom_userlink($author_id);
}
}


}



//获取网站名称
function jinsom_shortcodes_site_name(){
return jinsom_get_option('jinsom_site_name');
}


//获取论坛名称
function jinsom_shortcodes_bbs_name(){
global $post;
$post_id=$post->ID;	
$category_a = get_the_category($post_id);
if(count($category_a)>1){
$category=get_category($category_a[0]->term_id);
$cat_parents=$category->parent;
if($cat_parents==0){//判断该分类是否有父级
return $category_a[0]->name;
}else{
return $category_a[1]->name;    
}
}else{
return $category_a[0]->name;   
}
}


//论坛链接短代码
function jinsom_shortcodes_bbs_link($atts){
extract(shortcode_atts(array('id' =>''), $atts));
if($id){
if(wp_is_mobile()){
return jinsom_mobile_bbs_url($id);
}else{
return get_category_link($id);
}
}else{
return '';
}
}

//话题链接短代码
function jinsom_shortcodes_topic_link($atts){
extract(shortcode_atts(array('id' =>''), $atts));
if($id){
if(wp_is_mobile()){
return jinsom_mobile_topic_id_url($id);
}else{
return get_tag_link($id);
}
}else{
return '';
}
}


//获取搜索关键词
function jinsom_shortcodes_search_name(){
return get_search_query();
}
//获取话题名称
function jinsom_shortcodes_topic_name(){
return single_tag_title('', false);
}





//附件
function jinsom_shortcodes_file($atts){
extract(shortcode_atts(array(
'url' => home_url(),
'name' => '文件名.rar',
'pass' => '暂无介绍',
'type' => 1,
), $atts));

$url=str_replace('&quot;','',$url);

return '<div class="jinsom-file-download">
<div class="icon"><i class="jinsom-icon jinsom-fujian1"></i></div>
<div class="info">
<div class="name">'.$name.'</div>
<div class="pass">'.$pass.'</div>
</div>
<a class="btn" href="'.$url.'" target="_blank" download><i class="jinsom-icon jinsom-xiazai"></i></a>
</div>';
}

function jinsom_shortcodes_video($atts){
extract(shortcode_atts(array('url' => home_url(),'cover' => ''), $atts));
$rand=rand(100000,9999999);
if($cover){
$poster=$cover;	
}else{
$poster=jinsom_get_option('jinsom_publish_video_cover');
}

if(!wp_is_mobile()){
return '
<div class="jinsom-post-video"><div id="jinsom-video-'.$rand.'" post_id="'.$rand.'"></div></div>
<script type="text/javascript">
jinsom_post_video('.$rand.',"'.$url.'","'.$poster.'",false);
</script>
';
}else{
return '
<div onclick=\'jinsom_play_video('.$rand.',"'.$url.'",this)\' class="jinsom-video-img custom" style=\'background-image:url("'.$poster.'");\'>
<i class="jinsom-icon jinsom-bofang-"></i>
</div>
';	
}

}


//音乐短代码
function jinsom_shortcodes_music($atts){
extract(shortcode_atts(array('url' => home_url()), $atts));
$rand=rand(100000,9999999);
if(!wp_is_mobile()){
return '
<div id="jinsom-pop-music-'.$rand.'" class="aplayer"></div>
<script>
var jinsom_music_player = new APlayer({
element: document.getElementById("jinsom-pop-music-'.$rand.'"),
narrow: false,
autoplay: false,
mutex: true,
showlrc: false,
preload: "none",
music: {url: "'.$url.'"}});
</script>
';
}else{
return '
<div onclick=\'jinsom_play_music_custom("'.$url.'",this)\' class="jinsom-music-voice custom"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>
';
}

}

//图片短代码
function jinsom_shortcodes_img($atts){
extract(shortcode_atts(array('src' => ''), $atts));
$src=str_replace('&quot;','',$src);
return '<img src='.$src.'>';
}



//按钮短代码
function jinsom_shortcodes_btn($atts){
extract(shortcode_atts(array('name' =>'访问','url' =>'','target' =>true), $atts));
if($target){
$target='target="_blank"';
}
return '<a href="'.$url.'" '.$target.' class="jinsom-shortcodes-btn opacity">'.$name.'</a>';
}



//商品短代码
function jinsom_shortcodes_goods($atts){
extract(shortcode_atts(array('id' =>''), $atts));
if($id){

return '商品：<a href="'.jinsom_mobile_post_url($id).'" class="link" target="_blank">'.get_the_title($id).'</a>';


}
}