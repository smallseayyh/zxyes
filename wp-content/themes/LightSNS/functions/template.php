<?php 
$templates_arr=array();

//自定义模块页面模版
$module_page_option=get_option('LightSNS_Module_public/page');//获取已经启用的页面模版
if($module_page_option){
$module_page_option_arr=explode(',',$module_page_option);//分割成数组
foreach ($module_page_option_arr as $data) {
$file=$_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/page/'.$data.'/index.php';
if(file_exists($file)){//判断已经启用的页面模块的文件是否存在
$module_path=file_get_contents($file);
preg_match_all('/\/\*[\s\S]*\*\//U',$module_path,$matches);
if(strpos($matches[0][0],'Module Name')){//index文件包含模块信息
$module_info=explode("\n",$matches[0][0]);
array_shift($module_info);
$list_arr=explode(':',$module_info[0]);//模块的名称信息
$templates_arr[$data]='[模块]-'.$list_arr[1];
}
}

}
}


if($templates_arr){
//增加模版类型、后台页面显示
function jinsom_theme_page_templates_filter($posts_templates){
global $templates_arr;
$posts_templates=array_merge($posts_templates,$templates_arr);
return $posts_templates;
}


//判断模版类型，引入对应的模版文件
function jinsom_template_include_filter($template){
global $post;
global $templates_arr;
if(!isset($post)) return $template;
$page_template=get_post_meta($post->ID,'_wp_page_template',true);

if(!isset($templates_arr[$page_template])){//判断当前页面的模版是否在数组里
return $template;
}

$template_file=$_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/page/'.$page_template.'/index.php';
if(file_exists($template_file)){
return $template_file;
}else{
return $template;
}
}

add_filter('theme_page_templates','jinsom_theme_page_templates_filter');
add_filter('template_include','jinsom_template_include_filter');

}