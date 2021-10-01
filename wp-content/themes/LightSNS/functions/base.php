<?php


//注册菜单功能
register_nav_menus(array('header-menu' => '头部菜单栏(电脑)'));
// add_filter('wp_nav_menu_items','do_shortcode',7);//菜单添加短代码


//脚本地址
function jinsom_script_parameter(){
global $current_user,$version;
$user_id=$current_user->ID;
$user_info=get_userdata($user_id);

$object = array();
$permalink_structure=get_option('permalink_structure');//固定连接


if(is_single()){
$post_id=get_the_ID();
//获取帖子的最父级论坛id
$category_a = get_the_category();
if(count($category_a)>1){
$category=get_category($category_a[0]->term_id);
$cat_parents=$category->parent;
if($cat_parents==0){//判断该论坛是否有父级
$bbs_id=$category_a[0]->term_id;
}else{
$bbs_id=$category_a[1]->term_id;    
}
}else{
$bbs_id=$category_a[0]->term_id;   
}

$object['post_id']=$post_id;

if($permalink_structure){//自定义固定连接
$object['post_url']=get_the_permalink($post_id);
}else{
$object['post_url']='no';	
}
$object['post_type']=get_post_meta($post_id,'post_type',true);
$object['wp_post_type']=get_post_type($post_id);
$object['post_reprint']=get_post_meta($post_id,'reprint_post_id',true);
$object['is_bbs_post']=is_bbs_post($post_id);
$object['bbs_id']=$bbs_id;
}

//普通页面
if(is_page()){
if($permalink_structure){//自定义固定连接
$object['post_url']=get_the_permalink($post_id);
}else{
$object['post_url']='no';	
}
$object['post_id']=get_the_ID();
}

//论坛页面
if(is_category()){
$bbs_id = get_query_var('cat');//论坛ID

$object['bbs_id']=$bbs_id;
if($permalink_structure){//自定义固定连接
$object['bbs_url']=get_category_link($bbs_id);
}else{
$object['bbs_url']='no';	
}
}

//话题页面
if(is_tag()){
$topic_name = single_tag_title('', false);
$topic_data=get_term_by('name',$topic_name,'post_tag');
$topic_id=$topic_data->term_id;
$object['topic_id']=$topic_id;
if($permalink_structure){//自定义固定连接
$object['topic_url']=get_tag_link($topic_id);
}else{
$object['topic_url']='no';	
}
}

//话题页面
if(is_search()){
$object['search_keywords']=get_search_query();
}

//个人主页
if(is_author()){
global $wp_query;
$curauth = $wp_query->get_queried_object();
$author_id=$curauth->ID;
$object['author_id'] =$author_id;

if($permalink_structure){//自定义固定连接
$object['author_url'] =jinsom_userlink($author_id);
}else{//朴素
$object['author_url']='no';	
}

}
$object['site_name'] = jinsom_get_option('jinsom_site_name');
$object['ajax_url'] = admin_url('admin-ajax.php');
$object['admin_url'] = admin_url();
$object['home_url'] = home_url();
$object['member_url'] = jinsom_userlink($user_id);
if($permalink_structure){//自定义固定连接
$object['permalink_structure'] = 1;
$member_url_permalink=jinsom_userlink($user_id).'?';
}else{//朴素
$object['permalink_structure'] = 0;
$member_url_permalink=jinsom_userlink($user_id).'&';
}
$object['member_url_permalink'] =$member_url_permalink;
$object['theme_url'] = get_template_directory_uri();
$jinsom_js_cdn_url=jinsom_get_option('jinsom_js_cdn_url');
$object['cdn_url'] =$jinsom_js_cdn_url?$jinsom_js_cdn_url:'https://cdn.jsdelivr.net/gh/jinsom/LightSNS-CDN@'.$version;
$object['page_template'] = get_page_template_slug();
$object['user_url'] = jinsom_userlink($user_id);
$object['current_url'] = home_url(add_query_arg(array()));
$object['permalink'] = get_the_permalink();
$object['jinsom_ajax_url'] = get_template_directory_uri().'/module';
$object['mobile_ajax_url'] = get_template_directory_uri().'/mobile/module';
$object['module_url'] = get_template_directory_uri().'/module';
$object['content_url']=content_url();
$object['module_link']=content_url('/module');
$object['user_id'] = $current_user->ID;
$object['ip'] = $_SERVER['REMOTE_ADDR'];
$object['nickname'] = jinsom_nickname($user_id);
$object['nickname_base'] = get_user_meta($user_id,'nickname',true);
$object['nickname_link'] = jinsom_nickname_link($user_id);
$object['current_user_name'] = $current_user->user_login;
$object['user_name'] = jinsom_username();
$object['user_on_off'] = 1;
$object['is_vip'] = is_vip($user_id)?1:0;
$object['is_author'] = is_author()?1:0;
$object['is_single'] = is_single()?1:0;
$object['is_tag'] = is_tag()?1:0;
$object['is_search'] = is_search()?1:0;
$object['is_page'] = is_page()&&(!is_front_page())?1:0;
$object['is_home'] = is_home()?1:0;
$object['is_category'] = is_category()?1:0;

if(is_tax('cars')){
$queried_object=get_queried_object();
$object['is_category_cars']=1;
$object['car_term_id']=$queried_object->term_id;
$object['car_term_name']=$queried_object->name;
$object['car_term_url']=get_category_link($queried_object->term_id);
}else{
$object['is_category_cars']=0;
}
$object['is_car_single'] =is_singular('car')?1:0;



$object['is_login']=is_user_logged_in()?1:0;
$object['is_black'] = jinsom_is_black($user_id)?1:0;
$object['app'] = get_template_directory_uri().'/mobile/';
$object['api'] = get_template_directory_uri().'/api/';
//加载
$object['loading'] = '<div class="jinsom-load"><div class="jinsom-loading"><i></i><i></i><i></i></div></div>';
$object['loading_post'] = '<div class="jinsom-load-post"><div class="jinsom-loading-post"><i></i><i></i><i></i><i></i><i></i></div></div>';
$object['loading_info'] = '<div class="jinsom-info-card-loading"><img src="'.admin_url().'/images/spinner.gif"><p>资料加载中...</p></div>';
$object['empty'] = jinsom_empty();

//身份类
$object['verify'] = jinsom_verify($user_id);
$object['vip'] = jinsom_vip($user_id);
$object['vip_icon'] = jinsom_vip_icon($user_id);
$object['lv'] = jinsom_lv($user_id);
$object['exp'] = jinsom_get_user_exp($user_id);
$object['honor'] = jinsom_honor($user_id);

$object['avatar'] = jinsom_avatar($user_id, '100' , avatar_type($user_id));
$object['is_admin'] = jinsom_is_admin($user_id)?1:0;
$object['credit'] = get_user_meta($user_id,'credit',true);
$object['wechat_cash'] = get_user_meta($user_id,'wechat_cash_img',true)?1:0;
$object['alipay_cash'] = get_user_meta($user_id,'alipay_cash_img',true)?1:0;
$object['user_data'] = get_user_meta(1,'user_data',true)?1:0;
$object['user_verify'] = get_user_meta($user_id, 'verify', true );
$object['credit_name']=jinsom_get_option('jinsom_credit_name');
$object['cash_ratio'] = jinsom_get_option('jinsom_cash_ratio');
$object['cash_mini_number'] = jinsom_get_option('jinsom_cash_mini_number');//充值比例
// $object['qq_login'] = jinsom_get_option('jinsom_qq_on_off');//QQ登录
// $object['weibo_login'] = (int)jinsom_get_option('jinsom_weibo_on_off');//微博登录
// $object['wechat_login'] = (int)jinsom_get_option('jinsom_wechat_on_off');//微信登录
$object['login_on_off']=(int)jinsom_get_option('jinsom_login_on_off');
$object['phone_on_off']=(int)jinsom_get_option('jinsom_bind_phone_on_off');
$object['email_on_off']=(int)jinsom_get_option('jinsom_bind_email_on_off');
$object['is_phone']=get_user_meta($user_id,'phone',true)?1:0;
$object['is_email']=$user_info->user_email?1:0;




$object['bbs_name']=jinsom_get_option('jinsom_bbs_name');//论坛名称
// $object['topic_name']=jinsom_get_option('jinsom_topic_name');//话题名称

if(wp_is_mobile()){
$object['mobile_gif_size_max'] = jinsom_get_option('jinsom_mobile_gif_size_max');
$object['publish_img_quality'] = jinsom_get_option('jinsom_mobile_publish_img_quality');
$object['comment_img_quality'] = jinsom_get_option('jinsom_mobile_comment_img_quality');

$object['mobile_tab'] =json_encode(jinsom_get_option('jinsom_mobile_tab_add'));//移动端tab页面
}

//推广链接名称
$object['referral_link_name'] = jinsom_get_option('jinsom_referral_link_name');


//动态最大上传图片数量
$object['words_images_max'] = jinsom_get_option('jinsom_publish_words_add_images_max');

//人机验证 appid
$object['machine_verify_appid'] = jinsom_get_option('jinsom_machine_verify_appid');

//首页分页加载模式
$object['sns_home_load_type'] = jinsom_get_option('jinsom_sns_home_load_type');


//瀑布流外边距
$object['waterfull_margin']=jinsom_get_option('jinsom_waterfull_margin');

//sns首页默认排序
$object['sort']=jinsom_get_option('jinsom_sns_home_default_sort');


//表情
$object['smile_url']=jinsom_get_option('jinsom_smile_url');
$object['smile_add'] =json_encode(jinsom_get_option('jinsom_smile_add'));

//文件类型
$object['upload_video_type'] =jinsom_get_option('jinsom_upload_publish_video_type');
$object['upload_file_type'] =jinsom_get_option('jinsom_upload_file_type');
$object['upload_music_type'] =jinsom_get_option('jinsom_upload_publish_music_type');

$object_json = json_encode($object);
return $object_json;
}







//禁止访问后台
if(is_admin()&&(!defined('DOING_AJAX')||!DOING_AJAX )){
function jinsom_stop_visit_admin(){
if(!current_user_can('administrator')){
wp_redirect( home_url() );
die();
}
}
add_action('admin_init', 'jinsom_stop_visit_admin', 1 );
}


// 登录保护
function jinsom_login_protection(){  	
$a=jinsom_get_option('jinsom_login_safe_a');
$b=jinsom_get_option('jinsom_login_safe_b');
if(empty($a)){$a='abc';}
if(empty($b)){$b='123';}
if($_GET[$a] != $b){
header('Location: '.home_url());
exit();
}
}
add_action('login_enqueue_scripts','jinsom_login_protection');  




// 用户中心路径
function jinsom_author_link($link, $author_id){
$jinsom_member_url = jinsom_get_option('jinsom_member_url');
if(empty($jinsom_member_url)){$jinsom_member_url='author';}
global $wp_rewrite;
$author_id = (int)$author_id;
$link = $wp_rewrite->get_author_permastruct();
if(empty($link)){
$file = home_url('/');
$link = $file.'?'.$jinsom_member_url.'='.$author_id;
}else{
$link = str_replace('%author%', $author_id, $link);
$link = home_url(user_trailingslashit($link));
}
return $link;
}
add_filter('author_link','jinsom_author_link',10,2);

function jinsom_author_link_request($query_vars){
if(array_key_exists('author_name', $query_vars)){
global $wpdb;
$author_id = $query_vars['author_name'];
if($author_id){
$query_vars['author'] = $author_id;
unset($query_vars['author_name']);
}
}
return $query_vars;
}
add_filter('request','jinsom_author_link_request');

function jinsom_change_author_base() {
$jinsom_member_url = jinsom_get_option('jinsom_member_url');
if(empty($jinsom_member_url)){$jinsom_member_url='author';}
global $wp_rewrite;
$author_slug = $jinsom_member_url; 
$wp_rewrite->author_base = $author_slug;
}
add_action('init', 'jinsom_change_author_base');





// 移除wp自带工具栏
add_filter('show_admin_bar', '__return_false');


