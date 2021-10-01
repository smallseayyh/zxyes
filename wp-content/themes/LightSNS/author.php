<?php 
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');
}else{
if(!get_option('LightSNS_Module_pc/member')){
get_header();
}
if(isset($_GET['info'])&&$_GET['info']=='publish-single'){
require( get_template_directory() . '/module/publish/single-form.php'); 
}else if(isset($_GET['info'])&&$_GET['info']=='editor-single'){
require( get_template_directory() . '/module/editor/single-form.php' ); 
}else if(isset($_GET['info'])&&$_GET['info']=='editor-bbs'){
require( get_template_directory() . '/module/editor/bbs-form.php' ); 
}else if(isset($_GET['info'])&&$_GET['info']=='publish-bbs'){
require( get_template_directory() . '/module/publish/bbs-form.php' ); 
}else{
if(get_option('LightSNS_Module_pc/member')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/pc/member/index.php');
}else{
require( get_template_directory() . '/header/member.php' );	
}
}
get_footer();
}
