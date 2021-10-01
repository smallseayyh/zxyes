<?php
$require_url=get_template_directory();
require($require_url.'/header/ip.php');//ip封禁

//维护模式
if(jinsom_get_option('jinsom_maintenance_mode_on_off')&&!isset($_COOKIE[jinsom_get_option('jinsom_maintenance_mode_cookie')])){
require($require_url.'/header/tdk.php');
echo '<div style="display:none">'.get_stylesheet().'</div>'.jinsom_get_option('jinsom_maintenance_mode_html');
exit();
}


//是否开启强制登录模式
if(jinsom_get_option('jinsom_login_on_off')&&!is_user_logged_in()){
$jinsom_reg_doc_add=jinsom_get_option('jinsom_reg_doc_add');
$doc_arr=array();
if($jinsom_reg_doc_add){
foreach ($jinsom_reg_doc_add as $data) {
if($data['url']){
array_push($doc_arr,$data['url']);
}
}
}

$jinsom_invite_code_get_url=jinsom_get_option('jinsom_invite_code_get_url');
$curpageurl=home_url(add_query_arg(array()));
if($curpageurl!=$jinsom_invite_code_get_url&&!in_array($curpageurl,$doc_arr)){
require(get_template_directory().'/page/login.php');
exit();
}
}



//记录访问，用于后台统计
$visit_number=(int)get_option('visit_number');
update_option('visit_number',$visit_number+1);


//定义全局
global $current_user,$get_info;wp_get_current_user();
$user_id=$current_user->ID;




//推广
if(jinsom_get_option('jinsom_referral_link_on_off')){
$jinsom_referral_link_name = jinsom_get_option('jinsom_referral_link_name');
if(isset($_GET[$jinsom_referral_link_name])&&$_GET[$jinsom_referral_link_name]!=''){
$referral_user_id=$_GET[$jinsom_referral_link_name];
if($user_id!=$referral_user_id){
jinsom_referral_link($referral_user_id);
}
}
}

?>
<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
<link rel="shortcut icon" href="<?php echo jinsom_get_option('jinsom_icon');?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
<?php 
require($require_url.'/header/tdk.php');//TDK模块
wp_head();
require($require_url.'/header/style.php');//头部样式
?>
</head>
<body type="<?php if(isset($_GET['info'])){echo $_GET['info'];}?>" <?php echo body_class();?>>
<?php jinsom_body_star_hook();?>
<?php if(jinsom_get_option('jinsom_preference_bg_add')){?>
<link id="jinsom-bg-style" rel="stylesheet" type="text/css" href="<?php echo $_COOKIE["preference-bg"];?>">
<?php }?>

<?php if(!is_author()){?>

<?php if((!is_single()&&!is_page())||is_page_template('page/layout-sns.php')){?>
<link id="jinsom-post-style" rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/style/<?php echo $post_style;?>">
<?php }?>
<?php if(!is_category()&&!is_page()){?>
<link id="jinsom-layout-style" rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/style/<?php echo $layout_style;?>">
<?php }?>
<link id="jinsom-space-style" rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/style/<?php echo $post_space;?>">
<link id="jinsom-sidebar-style" rel="stylesheet" type="text/css" href="<?php echo $theme_url; ?>/assets/style/<?php echo $sidebar_style;?>">
<?php }?>
<?php echo do_shortcode(jinsom_get_option('jinsom_header_custom_code'));?>
<?php 
if(get_option('LightSNS_Module_pc/header')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/pc/header/index.php');
}else{
require($require_url.'/header/menu.php');//头部导航菜单
}
