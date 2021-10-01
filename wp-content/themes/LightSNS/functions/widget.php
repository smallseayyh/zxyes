<?php
if (function_exists('register_sidebar')){
register_sidebar(array(
'name' => '首页',
'id' => 'sidebar_index',
'before_widget' => '<div class="jinsom-sidebar-box %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
'description'   => '首页侧栏的小工具设置',
));
register_sidebar(array(
'name' => '动态内容页面',
'id' => 'sidebar_content_words',
'before_widget' => '<div class="jinsom-sidebar-box %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
register_sidebar(array(
'name' => '文章内容页面',
'id' => 'sidebar_content_single',
'before_widget' => '<div class="jinsom-sidebar-box %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
register_sidebar(array(
'name' => '音乐内容页面',
'id' => 'sidebar_content_music',
'before_widget' => '<div class="jinsom-sidebar-box %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
register_sidebar(array(
'name' => '视频内容页面',
'id' => 'sidebar_content_video',
'before_widget' => '<div class="jinsom-sidebar-box %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
register_sidebar(array(
'name' => '普通页面',
'id' => 'sidebar_page',
'before_widget' => '<div class="jinsom-sidebar-box %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
register_sidebar(array(
'name' => '话题页面',
'id' => 'sidebar_tag',
'before_widget' => '<div class="jinsom-sidebar-box %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
register_sidebar(array(
'name' => '搜索页面',
'id' => 'sidebar_search',
'before_widget' => '<div class="jinsom-sidebar-box %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
$custom_sidebar_number=(int)jinsom_get_option('jinsom_custom_sidebar_number');
for ($i=1; $i <=$custom_sidebar_number ; $i++) { 
register_sidebar(array(
'name' => '自定义小工具-'.$i,
'id' => 'custom_'.$i,
'before_widget' => '<div class="jinsom-sidebar-box %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
}

}





require($require_url.'/widget/sign-ranking.php');
require($require_url.'/widget/user-list.php');
require($require_url.'/widget/user-login.php');
require($require_url.'/widget/images.php');
require($require_url.'/widget/bbs-info.php');
require($require_url.'/widget/bbs-admin-list.php');
require($require_url.'/widget/bbs-commend-list.php');
require($require_url.'/widget/share.php');
require($require_url.'/widget/percent.php');
require($require_url.'/widget/donate.php');
require($require_url.'/widget/credit-exp-ranking.php');
require($require_url.'/widget/income-reward-ranking.php');
require($require_url.'/widget/bbs-comment-ranking.php');
require($require_url.'/widget/html.php');
require($require_url.'/widget/topic-ranking.php');
require($require_url.'/widget/content-sort.php');
require($require_url.'/widget/single-list.php');
require($require_url.'/widget/video-list.php');
require($require_url.'/widget/bbs-list.php');
require($require_url.'/widget/slider.php');
require($require_url.'/widget/ad.php');

//自定义模块小工具
$widget_option=get_option('LightSNS_Module_pc/widget');
if($widget_option){
$widget_option_arr=explode(',',$widget_option);//分割
foreach ($widget_option_arr as $data) {
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/pc/widget/'.$data.'/index.php')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/pc/widget/'.$data.'/index.php');
}
}
}