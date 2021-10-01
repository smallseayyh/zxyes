<?php 
$theme_url=get_template_directory_uri();

//一级、二级菜单、头像下拉菜单
$jinsom_menu_one_font_size = jinsom_get_option('jinsom_menu_one_font_size');
$jinsom_menu_two_font_size = jinsom_get_option('jinsom_menu_two_font_size');
$jinsom_menu_one = jinsom_get_option('jinsom_menu_one');
$jinsom_menu_two = jinsom_get_option('jinsom_menu_two');
$jinsom_avatar_menu = jinsom_get_option('jinsom_avatar_menu');

//全站背景和导航栏背景
$jinsom_body_bg_on_off = jinsom_get_option('jinsom_body_bg_on_off');
$body_bg = jinsom_get_option('jinsom_body_bg');
$jinsom_header_bg_on_off = jinsom_get_option('jinsom_header_bg_on_off');
$header_bg = jinsom_get_option('jinsom_header_bg');

//搜索、消息、发布按钮
$header_search_color = jinsom_get_option('jinsom_header_search_color');
$header_notice_color = jinsom_get_option('jinsom_header_notice_color');
$header_publish_color = jinsom_get_option('jinsom_header_publish_color');

//登录区
$header_login_on_off = jinsom_get_option('jinsom_header_login_on_off');
$header_username_color = jinsom_get_option('jinsom_header_username_color');
$jinsom_header_login_btn = jinsom_get_option('jinsom_header_login_btn');
$jinsom_header_reg_btn = jinsom_get_option('jinsom_header_reg_btn');


//内容折叠高度
$fold_height = jinsom_get_option('jinsom_publish_posts_cnt_fold_height');

//幻灯片
$jinsom_slider_height = jinsom_get_option('jinsom_slider_height');//高度
$jinsom_slider_default_style = jinsom_get_option('jinsom_slider_default_style');//类型
$jinsom_slider_overflow_on_off = jinsom_get_option('jinsom_slider_overflow_on_off');//是否溢出


//偏好设置
$layout_style = jinsom_get_option('jinsom_index_default_style');
$post_style = jinsom_get_option('jinsom_post_list_type');
$space_style = jinsom_get_option('jinsom_post_space_default_style');
$sidebar_style = jinsom_get_option('jinsom_sidebar_style');
if(empty($_COOKIE["layout-style"])){$layout_style=$layout_style.'.css';}else{$layout_style=$_COOKIE["layout-style"];}//单双栏
if(empty($_COOKIE["post-style"])){$post_style=$post_style.'.css';}else{$post_style=$_COOKIE["post-style"];}//动态列表样式
if(empty($_COOKIE["space-style"])){$post_space=$space_style.'.css';}else{$post_space=$_COOKIE["space-style"];}//帖子间隔
if(empty($_COOKIE["sidebar-style"])){$sidebar_style=$sidebar_style.'.css';}else{$sidebar_style=$_COOKIE["sidebar-style"];}//侧栏位置
?>

<style type="text/css">
<?php 


// 幻灯片
if($jinsom_slider_default_style=='m'){//窄屏
echo '.jinsom-slider{margin-top: 10px;width:var(--jinsom-width);}';
if($jinsom_slider_overflow_on_off&&jinsom_get_option('jinsom_slider_type')!='fade'){//开启允许溢出
echo '.jinsom-slider{overflow: visible !important;}';    
}
}
if($jinsom_slider_default_style!='l'){
echo '.jinsom-slider .swiper-slide{border-radius:var(--jinsom-border-radius);}';	
}
if($jinsom_slider_default_style=='s'){
echo '.jinsom-slider{margin-bottom: 10px;}';
}
if($jinsom_slider_default_style!='m'){//小屏或全屏
echo '.jinsom-slider{width: 100% !important;}';
}
echo '.jinsom-slider{height: '.$jinsom_slider_height.'px;}';
?>

/*全站宽度*/
:root{
	--jinsom-width:<?php echo jinsom_get_option('jinsom_main_width');?>px;
	--jinsom-color:<?php echo jinsom_get_option('jinsom_main_color');?>;
	--jinsom-border-radius:<?php echo jinsom_get_option('jinsom_border_radius');?>px;
}


/*折叠高度*/
.jinsom-post-content.hidden{max-height: <?php echo $fold_height;?>px;}


<?php 


//全站背景样式
if($jinsom_body_bg_on_off){
$body_bg='background-image:url('.$body_bg['background-image'].');background-repeat:'.$body_bg["background-repeat"].';background-attachment:'.$body_bg["background-attachment"].' ;background-position:'.$body_bg["background-position"].';background-color:'.$body_bg["background-color"].';background-size:'.$body_bg["background-size"].';';
echo 'body{'.$body_bg.'}';
}


//菜单背景样式
if($jinsom_header_bg_on_off){
$header_bg='background-image:url('.$header_bg['background-image'].');background-repeat:'.$header_bg["background-repeat"].';background-attachment:'.$header_bg["background-attachment"].';background-position:'.$header_bg["background-position"].';background-color:'.$header_bg["background-color"].';background-size:'.$header_bg["background-size"].';';
echo '.jinsom-header{'.$header_bg.'}';
}

?>

/*一级菜单导航*/
.jinsom-menu ul li a {font-size: <?php echo $jinsom_menu_one_font_size;?>px;color:<?php echo $jinsom_menu_one['normal'];?>;}
.jinsom-menu ul li.current-menu-item a {color: <?php echo $jinsom_menu_one['current'];?>;}
.jinsom-menu ul li.menu-item a:hover {color: <?php echo $jinsom_menu_one['hover']; ?>;}



/*右上角按钮颜色*/
.jinsom-header-right .search i {color: <?php echo $header_search_color;?>;}
.jinsom-notice i {color: <?php echo $header_notice_color;?>;}
.jinsom-header-right .publish {color: <?php echo $header_publish_color;?>;}



<?php 


//用户名颜色
echo '.jinsom-header-menu-avatar>p{color:'.$header_username_color.';}';

//登录按钮颜色&&侧栏同步
if($header_login_on_off){
echo '.jinsom-header-right .login{color:'.$jinsom_header_login_btn['font'].';background:'.$jinsom_header_login_btn['bg'].';}';
echo '.jinsom-header-right .reg{color:'.$jinsom_header_reg_btn['font'].';background:'.$jinsom_header_reg_btn['bg'].';}';
}


//二级菜单设置
echo '.jinsom-menu ul li.menu-item-has-children ul li a,.jinsom-menu>ul>li.menu-item-has-children>ul>li:hover>ul>li>a{color:'.$jinsom_menu_two['normal'].';font-size:'.$jinsom_menu_two_font_size.'px}';
echo '.jinsom-menu ul li ul li:hover{background-color:'.$jinsom_menu_two['bg'].';}';
echo '.jinsom-menu ul li.menu-item ul li a:hover, .jinsom-menu>ul>li.menu-item-has-children>ul>li:hover a, .jinsom-menu>ul>li.menu-item-has-children>ul>li:hover>ul>li:hover>a{color:'.$jinsom_menu_two['hover'].';}';
echo '.jinsom-menu ul li.menu-item-has-children ul li.current-menu-item a,.jinsom-menu ul li.menu-item-has-children ul li ul li.current-menu-item a {color:'.$jinsom_menu_two['current'].';}';

//头像下拉菜单
echo '.jinsom-header-menu-avatar>ul li a{color:'.$jinsom_avatar_menu['normal'].';}';
echo '.jinsom-header-menu-avatar>ul li:hover a{color:'.$jinsom_avatar_menu['hover'].';background-color:'.$jinsom_avatar_menu['bg'].';}';


//自定义头部导航
if(jinsom_get_option('jinsom_header_type')=='custom'){
echo '.jinsom-menu-fixed{padding-top:0;}';
}
?>


<?php echo jinsom_get_option('jinsom_custom_css');?>
</style>