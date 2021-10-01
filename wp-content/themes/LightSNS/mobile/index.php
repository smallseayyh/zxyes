<?php 
$login_on_off=jinsom_get_option('jinsom_login_on_off');//强制登录开关
if(isset($_GET['v'])){echo '<p style="position: absolute;z-index: 9999999999;">'.get_stylesheet().'</p>';}//获取LightSNS当前版本
$require_url=get_template_directory();
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
require($require_url.'/header/ip.php');//ip封禁

//维护模式
if(jinsom_get_option('jinsom_maintenance_mode_on_off')&&!isset($_COOKIE[jinsom_get_option('jinsom_maintenance_mode_cookie')])){
echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">';
require($require_url.'/header/tdk.php');
echo jinsom_get_option('jinsom_maintenance_mode_html');
exit();
}

//微信公众号自动登录
$jinsom_social_login_tab=jinsom_get_option('jinsom_social_login_tab');
$wechat_mp_auto_login=$jinsom_social_login_tab['jinsom_wechat_mp_auto_login_on_off'];
if(!is_user_logged_in()&&jinsom_is_login_type('wechat_mp')){
if($wechat_mp_auto_login){
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')){
header("Location:".jinsom_oauth_url('wechat_mp'));
}
}
}

$jinsom_icon=jinsom_get_option('jinsom_icon');//头部icon图标
$avatar=jinsom_avatar($user_id,'80',avatar_type($user_id));

//推广获得奖励
if(isset($_COOKIE['user_id'])){wp_set_auth_cookie($_COOKIE['user_id'],true);}//如果存在推广的用户cookies，则设置记录本地推广ID
if(jinsom_get_option('jinsom_referral_link_on_off')){//判断推广是否开启
$jinsom_referral_link_name = jinsom_get_option('jinsom_referral_link_name');
if(isset($_GET[$jinsom_referral_link_name])&&$_GET[$jinsom_referral_link_name]!=''){//匹配推广信息
$referral_user_id=$_GET[$jinsom_referral_link_name];
if($user_id!=$referral_user_id){
jinsom_referral_link($referral_user_id);//记录推广
}
}
}

?>
<!DOCTYPE html>
<html <?php language_attributes();?> class="pixel-ratio-1 watch-active-state">
<head>
<link rel="shortcut icon" href="<?php echo $jinsom_icon;?>"/>
<link rel="apple-touch-icon-precomposed" href="<?php echo $jinsom_icon;?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<?php require( get_template_directory() .'/header/tdk.php');?>
<?php wp_head();?>
<style type="text/css">
<?php if(jinsom_is_systerm()=='ios'){?>
body, html {
width: 100%;
height: 100%;
position: fixed;
top: 0;
left: 0;
}
[data-page="post-video"] .navbar{
background-color: rgba(255, 255, 255, 0);
}
[data-page="post-video"] .page-content{
padding-top: 0;
}
<?php }?>



/*内容折叠*/
.jinsom-post-words .content.hidden {max-height:<?php echo jinsom_get_option('jinsom_mobile_content_more_fold_height');?>vw;}

/*背景图*/
<?php 
$jinsom_mobile_login_bg = jinsom_get_option('jinsom_mobile_login_bg');
if($jinsom_mobile_login_bg){
echo '.login-screen-content,.jinsom-login-page-content{background-image: url('.$jinsom_mobile_login_bg.');background-size:cover;
    background-position:center;}';
}
;?>

/*统一风格*/
:root {--jinsom-color:<?php echo jinsom_get_option('jinsom_mobile_default_color');?>;--jinsom-content-bg-color:<?php echo jinsom_get_option('jinsom_mobile_default_bg_color');?>;}

/*幻灯片高度*/
#jinsom-sns-slider,#jinsom-sns-slider .item a{height:<?php echo jinsom_get_option('jinsom_mobile_sns_slider_height');?>vw;}
#jinsom-find-slider,#jinsom-find-slider .item a{height:<?php echo jinsom_get_option('jinsom_mobile_find_slider_height');?>vw;}

<?php echo jinsom_get_option('jinsom_mobile_custom_css');?>/*//自定义css*/ 
</style>
<?php echo do_shortcode(jinsom_get_option('jinsom_mobile_header_custom_code'));?>
</head>
<body <?php echo body_class();?>>
<?php jinsom_body_star_hook();?>

<?php if(!$login_on_off||is_user_logged_in()){?>
<div class="panel-overlay"></div>
<div class="panel panel-left panel-<?php echo jinsom_get_option('jinsom_mobile_left_panel_type');?>">
<?php 
if(get_option('LightSNS_Module_mobile/left_sidebar')){
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/mobile/left_sidebar/index.php')){//存在模块
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/mobile/left_sidebar/index.php');
}
}else{
echo do_shortcode(jinsom_get_option('jinsom_mobile_left_panel_html'));
}
?>
</div>

<div class="panel panel-right panel-<?php echo jinsom_get_option('jinsom_mobile_right_panel_type');?>">
<?php 
if(get_option('LightSNS_Module_mobile/right_sidebar')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/mobile/right_sidebar/index.php');
}else{
echo do_shortcode(jinsom_get_option('jinsom_mobile_right_panel_html'));
}
?>
</div>
<?php }?>

<div class="views tabs">

<?php 
//首页Tab页面
$jinsom_mobile_tab_add=jinsom_get_option('jinsom_mobile_tab_add');
if($jinsom_mobile_tab_add){
if($jinsom_mobile_tab_add&&(!$login_on_off||is_user_logged_in())){
//页面
$index_i=0;
foreach ($jinsom_mobile_tab_add as $data){
$tab_type=$data['jinsom_mobile_tab_type'];
if($index_i==0){$active='active';}else{$active='';}
if($tab_type!='publish'){
if($data['hide_navbar']){$hide_navbar_class='hide-navbar-on-scroll';}else{$hide_navbar_class='';}
if($data['hide_toolbar']){$hide_toolbar_class='hide-tabbar-on-scroll';}else{$hide_toolbar_class='';}

if($tab_type=='custom'){
if($data['jinsom_mobile_tab_custom_type']=='html'){//自定义显示类型
require(get_template_directory().'/mobile/templates/index/'.$tab_type.'.php');
}else if($data['jinsom_mobile_tab_custom_type']=='require'){
require(do_shortcode($data['jinsom_mobile_tab_custom_require']));
}
}else{
if($tab_type!='bbs'&&$tab_type!='video'&&$tab_type!='topic'){
if(get_option('LightSNS_Module_mobile/'.$tab_type)&&file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/mobile/'.$tab_type.'/index.php')){//存在模块
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/mobile/'.$tab_type.'/index.php');
}else{
require(get_template_directory().'/mobile/templates/index/'.$tab_type.'.php');
}
}else{
require(get_template_directory().'/mobile/templates/index/'.$tab_type.'.php');
}
}
}

$index_i++;
}//foreach


//底部工具栏
echo '
<div class="toolbar tabbar tabbar-labels jinsom-footer-toolbar">
<div class="toolbar-inner">';
$tab_i=0;
foreach ($jinsom_mobile_tab_add as $data){
$tab_type=$data['jinsom_mobile_tab_type'];

if($tab_type!='publish'){
if($tab_i==0){
$active='active';
}else{
$active='';	
}
if($data['is_login']||$tab_type=='mine'||$tab_type=='notice'){
if(is_user_logged_in()){
if($tab_type=='custom'){
if($data['jinsom_mobile_tab_custom_type']=='link'){
$link=do_shortcode($data['jinsom_mobile_tab_custom_link']);
}else{
$link='#jinsom-view-custom-'.$tab_i;
}
}else{
$link='#jinsom-view-'.$tab_type.'-'.$tab_i;
}
// $login_class='';
}else{
$link='javascript:myApp.loginScreen();';
// $login_class='open-login-screen';
}
}else{
if($tab_type=='custom'){
if($data['jinsom_mobile_tab_custom_type']=='link'){
$link=do_shortcode($data['jinsom_mobile_tab_custom_link']);
}else{
$link='#jinsom-view-custom-'.$tab_i;
}
}else{
$link='#jinsom-view-'.$tab_type.'-'.$tab_i;
}
// $login_class='';
}

if($tab_type=='sns'){
$default_icon='<i class="jinsom-icon jinsom-home_light"></i>';
}else if($tab_type=='notice'){
$default_icon='<i class="jinsom-icon jinsom-xiaoxizhongxin"></i>';	
}else if($tab_type=='find'){
$default_icon='<i class="jinsom-icon jinsom-zhinanzhen1"></i>';	
}else if($tab_type=='mine'){
$default_icon='<i class="jinsom-icon jinsom-my_light"></i>';	
}else if($tab_type=='bbs'){
$default_icon='<i class="jinsom-icon jinsom-luntan2"></i>';	
}else if($tab_type=='video'){
$default_icon='<i class="jinsom-icon jinsom-bofang"></i>';	
}else if($tab_type=='topic'){
$default_icon='<i class="jinsom-icon jinsom-huati1"></i>';	
}else{
$default_icon='<i class="jinsom-icon jinsom-yingyong3"></i>';	
}
if($data['icon']){$icon=$data['icon'];}else{$icon=$default_icon;}

echo '<a href="'.$link.'" class="link tab-link '.$tab_type.' '.$active.'">
'.$icon.'
<span class="tabbar-label">'.$data['name'].'</span>
</a>';
}else{
$publish_icon=$data['publish_icon'];//发布图标
if(is_user_logged_in()){
$jinsom_publish_add=jinsom_get_option('jinsom_publish_add');
if($jinsom_publish_add){
if(count($jinsom_publish_add)==1){
$first_publish_type=$jinsom_publish_add[0]['jinsom_publish_add_type'];
if($first_publish_type=='bbs'){
$first_publish_bbs_id=$jinsom_publish_add[0]['bbs_id'];
}else{
$first_publish_bbs_id=0;
}
$footer_publish='<a href=\'javascript:jinsom_publish_power("'.$first_publish_type.'",'.$first_publish_bbs_id.',"")\' class="link tab-link publish"><i class="jinsom-icon '.$publish_icon.'"></i></a>';
$sidebar_publish='javascript:jinsom_publish_power("'.$first_publish_type.'",'.$first_publish_bbs_id.',"")';
}else{
$footer_publish='<a href="#" class="link tab-link open-popup publish" data-popup=".jinsom-publish-type-form"><i class="jinsom-icon '.$publish_icon.'"></i></a>';
$sidebar_publish='javascript:myApp.popup(".jinsom-publish-type-form");';
}
}else{
$footer_publish='<a href="#" class="link tab-link open-popup publish" data-popup=".jinsom-publish-type-form"><i class="jinsom-icon '.$publish_icon.'"></i></a>';
$sidebar_publish='javascript:myApp.popup(".jinsom-publish-type-form");';
}
}else{
$footer_publish='<a href="#" class="link tab-link open-login-screen publish"><i class="jinsom-icon '.$publish_icon.'"></i></a>';
$sidebar_publish='javascript:myApp.loginScreen();';
}
if(!$data['hide_publish']){
echo $footer_publish;
}
$publish_header_html=$data['publish_header_html'];
}


$tab_i++;
}//foreach

echo '</div></div>';//toolbar

//右侧工具栏
$jinsom_mobile_right_bar_add=jinsom_get_option('jinsom_mobile_right_bar_add');
if($jinsom_mobile_right_bar_add){
$right_bar_height=count($jinsom_mobile_right_bar_add)*17-5;
}else{
$right_bar_height=5;	
}

echo '<div class="jinsom-right-bar home" style="height:'.$right_bar_height.'vw">';
if($jinsom_mobile_right_bar_add){
foreach ($jinsom_mobile_right_bar_add as $data) {
$sidebar_type=$data['jinsom_mobile_right_bar_type'];
if($sidebar_type!='html'){
if($sidebar_type=='publish'){
$link=$sidebar_publish;
$default_icon='<i class="jinsom-icon jinsom-fabiao1"></i>';	
}else if($sidebar_type=='top'){
$link="javascript:jinsom_totop()";
$default_icon='<i class="jinsom-icon jinsom-totop"></i>';	
}else if($sidebar_type=='search'){
$link='javascript:myApp.getCurrentView().router.load({url:"'.$theme_url.'/mobile/templates/page/search.php"});';
$default_icon='<i class="jinsom-icon jinsom-sousuo1"></i>';	
}else if($sidebar_type=='reload'){
$link='javascript:window.location.reload();';
$default_icon='<i class="jinsom-icon jinsom-shuaxin"></i>';	
}else if($sidebar_type=='left'){
$link='javascript:myApp.openPanel("left");';
$default_icon='<i class="jinsom-icon jinsom-arrow-right"></i>';	
}else if($sidebar_type=='right'){
$link='javascript:myApp.openPanel("right");';
$default_icon='<i class="jinsom-icon jinsom-arrow-left"></i>';	
}else if($sidebar_type=='close'){
$link='javascript:jinsom_hide_right_bar()';
$default_icon='<i class="jinsom-icon jinsom-guanbi"></i>';	
}else{
$link=$data['jinsom_mobile_right_bar_type_custom'];
$default_icon='<i class="jinsom-icon jinsom-yingyongkuai"></i>';	
}
if($data['icon']){$icon=$data['icon'];}else{$icon=$default_icon;}
echo '<li class="'.$sidebar_type.'"><a href='.$link.' class="link">'.$icon.'</a></li>';
}else{
echo do_shortcode($data['jinsom_mobile_right_bar_type_html']);//自定义html代码
}
}
}

//音乐播放器
echo '<li class="music jinsom-pop-music-player open-popup rotate-fast" data-popup=".jinsom-music-player"><i class="jinsom-icon jinsom-yinle"></i></li></div>';

}else{//强制登录 没有登录的情况
echo '<div id="jinsom-view-sns-0" class="view tab active" data-page="view-sns"><div class="navbar"><div class="navbar-inner"></div></div><div class="pages navbar-through"><div data-page="jinsom-home-page" class="page"><div class="page-content"></div></div></div></div>';
}
}else{
echo '请到后台-主题配置-移动设置-基本设置-移动端首页Tab页面添加';
}

echo '</div>';//views tabs

require(get_template_directory().'/mobile/templates/index/popup.php');//弹层类
jinsom_upadte_user_online_time();//更新在线状态
wp_footer();//WordPress底部支持引入

//公共模块-页面模版js文件引入
$module_pc_page=get_option('LightSNS_Module_public/page');
if($module_pc_page){
$module_pc_page_arr=explode(',',$module_pc_page);
foreach ($module_pc_page_arr as $data) {
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/page/'.$data.'/mobile.js')){
echo '<script type="text/javascript" src="'.content_url('/module/public/page/'.$data.'/mobile.js').'?ver='.time().'"></script>';
}
}
}


//移动端内页-js引入
$module_mobile_page=get_option('LightSNS_Module_mobile/page');
if($module_mobile_page){
$module_mobile_page_arr=explode(',',$module_mobile_page);
foreach ($module_mobile_page_arr as $data) {
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/mobile/page/'.$data.'/mobile.js')){
echo '<script type="text/javascript" src="'.content_url('/module/mobile/page/'.$data.'/mobile.js').'?ver='.time().'"></script>';
}
}
}

echo do_shortcode(jinsom_get_option('jinsom_mobile_footer_custom_code'));//底部自定义代码区域
jinsom_body_end_hook();//body结尾标签钩子
?>

<!-- 统计 -->
<div style="display: none;">
<?php 
$statistics_type=jinsom_get_option('jinsom_statistics_type');
$cnzz_id=jinsom_get_option('jinsom_statistics_cnzz_id');
if($statistics_type=='cnzz'){?>
<script type="text/javascript" src="https://s95.cnzz.com/z_stat.php?id=<?php echo $cnzz_id;?>&web_id=<?php echo $cnzz_id;?>"></script>
<?php }else if($statistics_type=='baidu'){?>
<script>var _hmt = _hmt || [];(function() {var hm = document.createElement("script");hm.src = "https://hm.baidu.com/hm.js?<?php echo jinsom_get_option('jinsom_statistics_baidu_id');?>";var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hm, s);})();
</script>
<?php }?>
</div>
</body>
</html>