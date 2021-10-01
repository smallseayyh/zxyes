<?php 

$type_arr=array('pc/home','pc/header','pc/footer','pc/member','pc/topic','pc/login_page','mobile/sns','mobile/notice','mobile/find','mobile/mine','mobile/left_sidebar','mobile/right_sidebar');

foreach ($type_arr as $type){
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/settings.php')){
if(get_option('LightSNS_Module_'.$type)){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/settings.php');
}
}
}


//页面模版-设置选项文件
$module_pc_page=get_option('LightSNS_Module_public/page');
if($module_pc_page){
$module_pc_page_arr=explode(',',$module_pc_page);
foreach ($module_pc_page_arr as $data) {
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/page/'.$data.'/settings.php')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/page/'.$data.'/settings.php');
}
}
}

//移动端内页-设置选项文件
$module_mobile_page=get_option('LightSNS_Module_mobile/page');
if($module_mobile_page){
$module_mobile_page_arr=explode(',',$module_mobile_page);
foreach ($module_mobile_page_arr as $data) {
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/mobile/page/'.$data.'/settings.php')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/mobile/page/'.$data.'/settings.php');
}
}
}

//小部件-设置选项文件
$module_public_gadget=get_option('LightSNS_Module_public/gadget');
if($module_public_gadget){
$module_public_gadget_arr=explode(',',$module_public_gadget);
foreach ($module_public_gadget_arr as $data) {
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/gadget/'.$data.'/index.php')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/public/gadget/'.$data.'/index.php');
}
}
}