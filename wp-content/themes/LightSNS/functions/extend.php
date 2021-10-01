<?php 

//定时任务
function jinsom_cron(){
date_default_timezone_set(get_option('timezone_string'));
if(!wp_next_scheduled('jinsom_cron_daily_action')){
wp_schedule_event('1483200000','daily','jinsom_cron_daily_action');
}
}
add_action('wp','jinsom_cron');

//日事件
function jinsom_cron_daily(){
global $wpdb;
$table_name_a = $wpdb->prefix . 'jin_code';
$table_name_c = $wpdb->prefix . 'jin_referral_link';
$table_name_task = $wpdb->prefix . 'jin_task';
//publish_post_times 发布动态次数
//publish_bbs_times 发布帖子次数
//comment_post_times 评论动态次数
//comment_bbs_times 评论帖子次数
//like_post_times 喜欢内容次数
//comment_like_times 评论点赞次数
//send_msg_times 发送消息次数
//upload_times 上传次数
//upload_avatar_times 上传头像次数
//lottery_times  每天抽奖次数
//discount_times  会员用户每天折扣次数
//buy_times  每天购买付费内容次数
//reward_times 打赏次数
//gift_times 送礼次数
//follow_times 关注次数
//visit_times 访问他人主页次数
//draw_times 抽奖次数
//today_invite_number 当天邀请人数
//today_recharge_yuan 当天充值金额
//today_ad_number 当天点击广告次数
//today_pet_steal_times 今天偷取次数
//today_pet_times 今天孵化次数
//today_challenge_times 今天挑战次数
$wpdb->query( " DELETE FROM $wpdb->usermeta WHERE meta_key='publish_post_times' OR meta_key='publish_bbs_times' or meta_key='comment_post_times'  or meta_key='comment_bbs_times'  or meta_key='like_post_times' or meta_key='comment_like_times' or meta_key='send_msg_times' or meta_key='upload_times' or meta_key='upload_avatar_times' or meta_key='referral_times' or meta_key='today_income' or meta_key='lottery_times' or meta_key='discount_times' or meta_key='buy_times' or meta_key='reward_times' or meta_key='gift_times' or meta_key='follow_times' or meta_key='visit_times' or meta_key='draw_times' or meta_key='today_invite_number' or meta_key='today_recharge_yuan' or meta_key='today_ad_number' or meta_key='today_pet_steal_times' or meta_key='today_pet_times' or meta_key='today_challenge_times';" );

$wpdb->query("DELETE FROM $wpdb->termmeta WHERE meta_key='today_publish';");//删除论坛今日发布统计

delete_option('today_credit');//删除今日充值金额的统计
delete_option('visit_number');//删除今日访问量
delete_option('today_publish');//今日发布
delete_option('today_comment');//今日评论
delete_option('today_like');//今日喜欢
delete_option('today_follow');//今日关注
delete_option('today_msg');//今日消息
$wpdb->query("DELETE FROM $table_name_a ;");//删除临时验证表
$wpdb->query("DELETE FROM $table_name_c ;");//删除统计推广链接的表
$wpdb->query("DELETE FROM $table_name_task where type='day';");//删除任务表/只删除每日任务
}
add_action('jinsom_cron_daily_action','jinsom_cron_daily');



//移除 私密和密码保护标题前面的文字
add_filter('private_title_format','jinsom_private_title_format' );
add_filter('protected_title_format','jinsom_private_title_format' );
function jinsom_private_title_format($format){
return '%s';
}

//允许中文名
function jinsom_chinese_name ($username,$raw_username,$strict){
$username=wp_strip_all_tags( $raw_username );
$username=remove_accents( $username );
$username=preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
$username=preg_replace( '/&.+?;/', '', $username ); 
if ($strict) {
$username=preg_replace ('|[^a-z\p{Han}0-9 _.\-@]|iu', '', $username);
}
$username=trim( $username );
$username=preg_replace( '|\s+|', ' ', $username );
return $username;
}
add_filter('sanitize_user','jinsom_chinese_name',10,3);


//表情
if(jinsom_get_option('jinsom_smile_add')){
function jinsom_smile_data(){
global $wpsmiliestrans;
$wpsmiliestrans = array();
$key_arr = array();
$value_arr = array();
$a=1;
foreach (jinsom_get_option('jinsom_smile_add') as $data) {
for ($i=1; $i <=$data['number']; $i++) { 
if($a==1){
array_push($key_arr,'[s-'.$i.']');
}else{
array_push($key_arr,'[s-'.$a.'-'.$i.']');
}
array_push($value_arr,$data['smile_url'].'/'.$i.'.png');
}
$a++;
}

$wpsmiliestrans =array_combine($key_arr,$value_arr);
}
jinsom_smile_data();

//表情地址
function jinsom_smile_src($img_src,$img,$siteurl){
return jinsom_get_option('jinsom_smile_url').$img;
}
add_filter('smilies_src','jinsom_smile_src',1,10);
}

//禁止修订
function jinsom_revisions_to_keep($num,$post){
return 0;
}
add_filter('wp_revisions_to_keep','jinsom_revisions_to_keep',10,2);

//电脑端帖子添加图片灯箱
function jinsom_add_lightbox_bbs($content){
global $post;
$post_id=$post->ID;

//样式规则
$jinsom_upload_obj_style=jinsom_get_option('jinsom_upload_obj_style');
$upload_style='';
$upload_style_thum='';
if($jinsom_upload_obj_style){
$upload_style=jinsom_get_option('jinsom_upload_style_oss_bbs_single_content');
$upload_style_thum=jinsom_get_option('jinsom_upload_style_oss_bbs_single_content_thum');
}

$content = preg_replace_callback('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',function($r) use ($post_id,$upload_style,$upload_style_thum){
if(strpos($r[2],'x-oss-process')!==false||strpos($r[2],'wp-content')!==false||substr(strrchr($r[2],'.'),1)=='gif'){//包含
$upload_style='';
$upload_style_thum='';
}

return "<a data-fancybox='gallery-".$post_id."' href='".$r[2].$upload_style."' data-no-instant><img alt='".get_the_title($post_id)."' src='".$r[2].$upload_style_thum."'></a>";	

},$content);

return $content;
}
add_filter('the_content','jinsom_add_lightbox_bbs',0);



// 邮件系统配置
if(jinsom_get_option('jinsom_email_style')=='smtp'){
function jinsom_mail_smtp($phpmailer){
$email=jinsom_get_option('jinsom_mail_user');//邮箱
$phpmailer->IsSMTP();
$phpmailer->From=$email;
$phpmailer->SetFrom($phpmailer->From,$phpmailer->FromName);
$phpmailer->FromName=jinsom_get_option('jinsom_mail_name');//发件人名称
$phpmailer->AddReplyTo($phpmailer->From,$phpmailer->FromName);
$phpmailer->Host=jinsom_get_option('jinsom_mail_host');
if(jinsom_get_option('jinsom_mail_smtpsecure')!='none'){
$phpmailer->SMTPSecure=jinsom_get_option('jinsom_mail_smtpsecure');
$phpmailer->SMTPOptions=array(
'ssl'=>array(
'verify_peer'=>false,
'verify_peer_name'=>false,
'allow_self_signed'=>true,
),
);
}else{
$phpmailer->SMTPSecure='';
}
$phpmailer->SMTPAutoTLS=false;
$phpmailer->Port=jinsom_get_option('jinsom_mail_port');
$phpmailer->SMTPAuth=TRUE;
$phpmailer->Username=$email;
$phpmailer->Password=jinsom_get_option('jinsom_mail_password');
$phpmailer->Timeout=10;

}
add_action('phpmailer_init', 'jinsom_mail_smtp');
}




//移除自带的小工具
function jinsom_remove_widget() {
unregister_widget('WP_Widget_Pages');
unregister_widget('WP_Widget_Calendar');
unregister_widget('WP_Widget_Archives');
unregister_widget('WP_Widget_Links');
unregister_widget('WP_Widget_Meta');
unregister_widget('WP_Widget_Search');
unregister_widget('WP_Widget_Categories');
unregister_widget('WP_Widget_Recent_Posts');
unregister_widget('WP_Widget_Recent_Comments');
unregister_widget('WP_Widget_RSS');
unregister_widget('WP_Widget_Tag_Cloud');
unregister_widget('WP_Nav_Menu_Widget');
unregister_widget('WP_Widget_Media_Audio');
unregister_widget('WP_Widget_Media_Image');
unregister_widget('WP_Widget_Media_Video');
unregister_widget('WP_Widget_Custom_HTML');
unregister_widget('WP_Widget_Text');
unregister_widget('WP_Widget_Media_Gallery');
}
add_action( 'widgets_init', 'jinsom_remove_widget',11 );

//加载js和css
function jinsom_load_scripts(){
$theme_url=get_template_directory_uri();
global $version;
$jinsom_js_cdn_url=jinsom_get_option('jinsom_js_cdn_url');
$LightSNS_CDN_url=$jinsom_js_cdn_url?$jinsom_js_cdn_url:'https://cdn.jsdelivr.net/gh/jinsom/LightSNS-CDN@'.$version;

//移除
wp_dequeue_style('wp-block-library');
wp_deregister_script('jquery');

//公共
wp_enqueue_style('fancybox',$LightSNS_CDN_url.'/assets/css/jquery.fancybox.min.css',array(),$version);
wp_enqueue_style('awesome',$LightSNS_CDN_url.'/assets/css/font-awesome.min.css',array(),$version);
wp_enqueue_style('icon','https://at.alicdn.com/t/font_502180_xf7oqh5oqb.css',array(),$version);
if(jinsom_get_option('jinsom_machine_verify_on_off')){
wp_enqueue_script('Captcha','https://ssl.captcha.qq.com/TCaptcha.js',false,$version);
}

//西瓜视频
wp_enqueue_script('xgplayer',$LightSNS_CDN_url.'/assets/js/xgplayer.js',false,$version);
wp_enqueue_script('xgplayer-hls',$LightSNS_CDN_url.'/assets/js/xgplayer-hls.js',false,$version);
wp_enqueue_script('xgplayer-flv',$LightSNS_CDN_url.'/assets/js/xgplayer-flv.js',false,$version);

//jquery
wp_enqueue_script('jquery',$LightSNS_CDN_url.'/assets/js/jquery.min.js',false,$version);

//二维码
wp_enqueue_script('qrcode',$LightSNS_CDN_url.'/assets/js/jquery.qrcode.min.js',false,$version);

//瀑布流
wp_enqueue_script('masonry-min',$LightSNS_CDN_url.'/assets/js/masonry.min.js',false,$version);
wp_enqueue_script('masonry-imagesloaded',$LightSNS_CDN_url.'/assets/js/imagesloaded.min.js',false,$version);

wp_enqueue_script('ajaxSubmit',$LightSNS_CDN_url.'/assets/js/ajaxSubmit.js',false,$version,true);//上传

if(!wp_is_mobile()){//电脑端

//css
wp_enqueue_style('Swiper',$LightSNS_CDN_url.'/assets/css/swiper.min.css',array(),$version);
wp_enqueue_style('layui',$LightSNS_CDN_url.'/extend/layui/css/layui.css',array(),$version);
wp_enqueue_style('jinsom',$LightSNS_CDN_url.'/assets/css/jinsom.css',array(),$version);


//js
wp_enqueue_script('music-player',$LightSNS_CDN_url.'/assets/js/player.js',false,$version);//音乐播放器
wp_enqueue_script('layui',$LightSNS_CDN_url.'/extend/layui/layui.js',false,$version);//layui
wp_enqueue_script('jinsom',$LightSNS_CDN_url.'/assets/js/jinsom.js',false,$version);
if(is_single()||isset($_GET['info'])){//代码高亮、编辑器
wp_enqueue_script('editor-a-js',$theme_url.'/extend/editor/ueditor.config.js',false,$version);
wp_enqueue_script('editor-b-js',$LightSNS_CDN_url.'/extend/editor/ueditor.all.min.js',false,$version);
if(is_single()){
wp_enqueue_script('Highlighter-js',$LightSNS_CDN_url.'/extend/editor/third-party/SyntaxHighlighter/shCore.js',false,$version);
wp_enqueue_style( 'Highlighter-css',$LightSNS_CDN_url.'/extend/editor/third-party/SyntaxHighlighter/shCoreDefault.css', array(),$version);
}
}

//底部
wp_enqueue_script('fancybox',$LightSNS_CDN_url.'/assets/js/jquery.fancybox.min.js',false,$version,true);

wp_enqueue_script('clipboard',$LightSNS_CDN_url.'/assets/js/clipboard.min.js',false,$version,true);
wp_enqueue_script('swiper',$LightSNS_CDN_url.'/assets/js/swiper.min.js',false,$version,true);
wp_enqueue_script('SidebarFixed',$LightSNS_CDN_url.'/assets/js/SidebarFixed.js',false,$version,true);
wp_enqueue_script('base',$LightSNS_CDN_url.'/assets/js/base.js',false,$version,true);
wp_enqueue_script('upload',$LightSNS_CDN_url.'/assets/js/upload.js',false,$version,true);

}else{//移动端
wp_enqueue_style('LightSNS-min',$LightSNS_CDN_url.'/mobile/css/LightSNS.min.css',array(),$version);
wp_enqueue_style('slider',$LightSNS_CDN_url.'/assets/css/owl.carousel.min.css',array(),$version);//幻灯片
wp_enqueue_style('jinsom',$LightSNS_CDN_url.'/mobile/css/jinsom.css',array(),$version);
wp_enqueue_script('carousel',$LightSNS_CDN_url.'/assets/js/owl.carousel.min.js',false,$version);//移动端幻灯片
wp_enqueue_script('functions',$LightSNS_CDN_url.'/mobile/js/functions.js',false,$version);
wp_enqueue_script('layer-mobile',$LightSNS_CDN_url.'/mobile/js/layer.js',false,$version);


wp_enqueue_script('LightSNS',$LightSNS_CDN_url.'/mobile/js/LightSNS.min.js',false,$version,true);
wp_enqueue_script('jinsom',$LightSNS_CDN_url.'/mobile/js/jinsom.js',false,$version,true);
wp_enqueue_script('page',$LightSNS_CDN_url.'/mobile/js/page.js',false,$version,true);
wp_enqueue_script('base',$LightSNS_CDN_url.'/mobile/js/base.js',false,$version,true);
wp_enqueue_script('mavatar',$LightSNS_CDN_url.'/mobile/js/mavatar.js',false,$version,true);
wp_enqueue_script('ajaxSubmit',$LightSNS_CDN_url.'/assets/js/ajaxSubmit.js',false,$version,true);
wp_enqueue_script('clipboard',$LightSNS_CDN_url.'/assets/js/clipboard.min.js',false,$version,true);


wp_enqueue_script('html2canvas',$LightSNS_CDN_url.'/mobile/js/html2canvas.min.js',false,$version,true);//生成海报
}

echo '<script type="text/javascript">var jinsom='.jinsom_script_parameter().';</script>';
}
add_action('wp_enqueue_scripts','jinsom_load_scripts');



//移除wordpress自带功能
remove_action( 'wp_head', 'feed_links', 2 ); //移除feed
remove_action( 'wp_head', 'feed_links_extra', 3 ); //移除feed
remove_action( 'wp_head', 'rsd_link' ); //移除离线编辑器开放接口
remove_action( 'wp_head', 'wlwmanifest_link' );  //移除离线编辑器开放接口
remove_action( 'wp_head', 'index_rel_link' );//去除本页唯一链接信息
remove_action('wp_head', 'parent_post_rel_link', 10, 0 );//清除前后文信息
remove_action('wp_head', 'start_post_rel_link', 10, 0 );//清除前后文信息
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_generator' ); //移除WordPress版本
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'oembed_discovery_links', 10 );



//手机端屏蔽emoji转化
function jinsom_disable_emoji(){
if(wp_is_mobile()){
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
// add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
}
add_action('init','jinsom_disable_emoji');



//移除embed js 防止网站之间的引用
function jinsom_disable_embeds(){
global $wp;
$wp->public_query_vars = array_diff($wp->public_query_vars, array('embed'));
remove_action('rest_api_init','wp_oembed_register_route');
add_filter('embed_oembed_discover','__return_false');
remove_filter('oembed_dataparse','wp_filter_oembed_result', 10);
remove_action('wp_head','wp_oembed_add_discovery_links');
remove_action('wp_head','wp_oembed_add_host_js');
//add_filter('tiny_mce_plugins','jinsom_disable_embeds_tiny_mce_plugin');
add_filter('rewrite_rules_array','jinsom_disable_embeds_rewrites');
}
add_action('init','jinsom_disable_embeds',9999);


function jinsom_disable_embeds_rewrites($rules){
foreach ($rules as $rule => $rewrite) {
if (false !== strpos($rewrite, 'embed=true')) {
unset($rules[$rule]);
}
}
return $rules;
}

function jinsom_disable_embeds_remove(){
add_filter('rewrite_rules_array','disable_embeds_rewrites');
flush_rewrite_rules();
}
register_activation_hook(__FILE__,'jinsom_disable_embeds_remove');

function jinsom_disable_embeds_flush(){
remove_filter('rewrite_rules_array','disable_embeds_rewrites');
flush_rewrite_rules();
}
register_deactivation_hook(__FILE__,'jinsom_disable_embeds_flush');



function jinsom_remove_dns_prefetch($hints,$relation_type){
if('dns-prefetch' === $relation_type){
return array_diff(wp_dependencies_unique_hosts(),$hints);
}
return $hints;
}
add_filter('wp_resource_hints','jinsom_remove_dns_prefetch',10,2);



//取消自动过滤
remove_action('init', 'kses_init');   
remove_action('set_current_user', 'kses_init');


//用户查询——随机用户
add_filter('pre_user_query',function(&$query){
if($query->query_vars["orderby"]=='rand'){
$query->query_orderby= 'ORDER by RAND()';
}
});



//添加权限
// function jinsom_add_subscriber_caps() {
// $role=get_role('subscriber');
// $role->add_cap('edit_posts');
// $role->add_cap('edit_others_posts');
// $role->add_cap('read_private_pages');

// $role_a=get_role('contributor');
// $role_a->add_cap('edit_posts');
// $role_a->add_cap('edit_others_posts');
// $role_a->add_cap('read_private_pages');

// // $role->remove_cap('edit_others_posts');
// // $role->remove_cap('edit_posts'); 
// // $role->remove_cap('read_private_pages'); 
// }
// add_action('admin_init','jinsom_add_subscriber_caps');


