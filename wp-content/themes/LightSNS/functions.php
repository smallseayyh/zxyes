<?php 
$version=wp_get_theme()->Version;
date_default_timezone_set(get_option('timezone_string'));
$require_url=get_template_directory();
require($require_url.'/functions/jinsom.php');
require_once get_theme_file_path().'/admin/module/options.php';
require_once get_theme_file_path().'/admin/module/metabox.php';
require($require_url.'/functions/module-settings.php');

require($require_url.'/functions/base.php');
require($require_url.'/functions/extend.php');
require($require_url.'/functions/post-type.php');
require($require_url.'/functions/widget.php');//小工具
require($require_url.'/functions/hook.php');


if(is_admin()){
require($require_url.'/functions/table.php');//注册表
require($require_url.'/functions/admin-users-list.php');//用户列表
require($require_url.'/functions/admin-posts-list.php');//内容列表
}else{
require($require_url.'/functions/shortcode.php');//短代码


if(wp_is_mobile()){//手机端
require($require_url.'/functions/mobile.php');
}
}


//自定义函数
$function_option=get_option('LightSNS_Module_public/function');
if($function_option){
$function_option_arr=explode(',',$function_option);
foreach ($function_option_arr as $data) {
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/function/'.$data.'/index.php')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/function/'.$data.'/index.php');
}
}
}


//模版引入
require($require_url.'/functions/template.php');