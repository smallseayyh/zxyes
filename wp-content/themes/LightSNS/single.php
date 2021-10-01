<?php 
$require_url=get_template_directory();
if(wp_is_mobile()){
require($require_url.'/mobile/index.php');
}else{
get_header();
$post_id=get_the_ID();
$user_id=$current_user->ID;
$author_id=(int)get_the_author_meta('ID');
$post_type=get_post_meta($post_id,'post_type',true);
$is_bbs_post=is_bbs_post($post_id);
$post_status=get_post_status();
if($is_bbs_post){//是帖子

//获取帖子的最父级分类id
$category_a = get_the_category();
$bbs_id_a=$category_a[0]->term_id;
$bbs_id_b=$category_a[1]->term_id;
if(count($category_a)>1){
$category=get_category($category_a[0]->term_id);
$cat_parents=$category->parent;
if($cat_parents==0){//判断该分类是否有父级
$bbs_id=$category_a[0]->term_id;
$child_cat_id=$category_a[1]->term_id; 
}else{
$bbs_id=$category_a[1]->term_id;
$child_cat_id=$category_a[0]->term_id;     
}
}else{
$bbs_id=$category_a[0]->term_id; 
$child_cat_id=$category_a[0]->term_id;  
}

$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
if(empty($admin_a)){$admin_a=9909999;}
if(empty($admin_b)){$admin_b=9909999;}
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);

$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))&&is_user_logged_in()?1:0;

//权限判断
$bbs_visit_power=get_term_meta($bbs_id,'bbs_visit_power',true);
if($bbs_visit_power==1){
if(!is_vip($user_id)&&!$is_bbs_admin){
require($require_url.'/post/bbs-no-power.php');  
exit();
}  
}else if($bbs_visit_power==2){
$user_verify=get_user_meta($user_id, 'verify', true );
if(!$user_verify&&!$is_bbs_admin){
require($require_url.'/post/bbs-no-power.php');  
exit();  
}
}else if($bbs_visit_power==3){
if(!$is_bbs_admin){
require($require_url.'/post/bbs-no-power.php');
exit();  
}
}else if($bbs_visit_power==4){
$user_honor=get_user_meta($user_id,'user_honor',true);
if(!$user_honor&&!$is_bbs_admin){
require($require_url.'/post/bbs-no-power.php'); 
exit();  
}
}else if($bbs_visit_power==5){
//先判断用户是否已经输入密码
if(!jinsom_is_bbs_visit_pass($bbs_id,$user_id)&&!$is_bbs_admin){
require($require_url.'/post/bbs-no-power.php');
exit();
}
}else if($bbs_visit_power==6){//满足经验的用户
$current_user_lv=jinsom_get_user_exp($user_id);//当前用户经验
$bbs_visit_exp=(int)get_term_meta($bbs_id,'bbs_visit_exp',true);
if($current_user_lv<$bbs_visit_exp&&!$is_bbs_admin){
require($require_url.'/post/bbs-no-power.php');
exit();
}
}else if($bbs_visit_power==7){//指定用户
$bbs_visit_user=get_term_meta($bbs_id,'bbs_visit_user',true);
$bbs_visit_user_arr=explode(",",$bbs_visit_user);
if(!in_array($user_id,$bbs_visit_user_arr)&&!$is_bbs_admin){
require(get_template_directory() . '/post/bbs-no-power.php');
exit();
}
}else if($bbs_visit_power==8){//登录用户
if(!is_user_logged_in()){
require(get_template_directory() . '/post/bbs-no-power.php');
exit();
}
}else if($bbs_visit_power==9){//指定头衔
if(!$is_bbs_admin){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$bbs_visit_honor=get_term_meta($bbs_id,'bbs_visit_honor',true);
$bbs_visit_honor_arr=explode(",",$bbs_visit_honor);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($bbs_visit_honor_arr,$user_honor_arr)){
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}else{
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}
}else if($bbs_visit_power==10){//指定认证
if(!$is_bbs_admin){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$bbs_visit_verify=get_term_meta($bbs_id,'bbs_visit_verify',true);
$bbs_visit_verify_arr=explode(",",$bbs_visit_verify);
if(!in_array($user_verify_type,$bbs_visit_verify_arr)){
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}else{
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}
}else if($bbs_visit_power==11){//付费访问
if(!$is_bbs_admin){
$bbs_pay_user_list=get_term_meta($bbs_id,'visit_power_pay_user_list',true);
$bbs_pay_user_list_arr=explode(",",$bbs_pay_user_list);
if($bbs_pay_user_list){
if(!in_array($user_id,$bbs_pay_user_list_arr)){
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}else{
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}
}



$bbs_class="single-bbs";
}else{ //判断是否为帖子
$bbs_class='';
}

$bbs_type=get_term_meta($bbs_id,'bbs_type',true);//论坛类型


//论坛自定义css样式
$custom_sidebar=get_term_meta($bbs_id,'single_sidebar',true);//内页右侧栏
$bbs_css=get_term_meta($bbs_id,'bbs_css',true);//论坛自定义css
echo '<style type="text/css">';
echo $bbs_css;
if($custom_sidebar=='no'&&$bbs_type!='download'){//全屏布局
echo '.jinsom-content-right{display:none;}.jinsom-content-left {width:100% !important;margin-right:0;}.jinsom-bbs-comment-floor-list li .floor-right{max-width:calc(100% - 52px);}';
if($_COOKIE['sidebar-style']=='sidebar-style-left.css'){
echo '.jinsom-single-left-bar{margin-left:-60px !important;}';
}
}
echo '</style>';


echo '<div class="jinsom-main-content '.$bbs_class.' '.$post_type.' '.$bbs_type.' single clear">';//主内容

if($bbs_type=='download'){
require($require_url.'/post/bbs-content-download.php');//下载类型
}else{//默认类型

if(($post_type=='words'&&!is_active_sidebar('sidebar_content_words'))||($post_type=='single'&&!is_active_sidebar('sidebar_content_single'))||($post_type=='video'&&!is_active_sidebar('sidebar_content_video'))||($post_type=='music'&&!is_active_sidebar('sidebar_content_music'))){
$full='full';
}else{
$full='';
}

echo '<div class="jinsom-content-left '.$full.'">';//左侧标签
if(is_single()&&$post_type=='single'){
echo do_shortcode(jinsom_get_option('jinsom_single_content_header_html'));//文章页头部自定义
}

while (have_posts()):the_post();
if($is_bbs_post){
require($require_url.'/post/bbs-content.php' );
}else{
require($require_url.'/post/post-list.php');	
}
endwhile;
if(is_single()&&$post_type=='single'){
echo do_shortcode(jinsom_get_option('jinsom_single_content_footer_html'));//文章页底部自定义
}
echo '</div>';//jinsom-content-left
}//download ==


if(is_page()){//普通页面
require($require_url.'/sidebar/sidebar-page.php');
}else{
if(!$is_bbs_post){//帖子内页
if($post_type=='single'||$post_type=='music'||$post_type=='video'){
require($require_url.'/sidebar/sidebar-content-'.$post_type.'.php');
}else{
require($require_url.'/sidebar/sidebar-content-words.php');
}
}else{

if($bbs_type!='download'){
if($custom_sidebar!='no'){
require(get_template_directory().'/sidebar/sidebar-custom.php');//引入右侧栏
}
}

}
}


echo '</div>';//jinsom-main-content


// }//bbs_type

get_footer();

}//非移动端
