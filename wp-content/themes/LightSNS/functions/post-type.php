<?php 

//商城
function jinsom_add_post_type_shop(){
$name='商品';
register_post_type('goods',
array(
'labels' => array(
'name' => '商城',
'singular_name' => '所有'.$name,
'add_new' => '添加'.$name,
'add_new_item' => '添加'.$name,
'edit' => '编辑',
'edit_item' => '编辑'.$name,
'new_item' => '添加'.$name,
'view' => '查看'.$name,
'view_item' => '查看'.$name,
'search_items' => '搜索'.$name,
'not_found' => '没有找到相关'.$name,
),
'menu_icon'=> 'dashicons-store',
'exclude_from_search'=>false,
'public' => true,
'show_in_rest'        => true,
'menu_position' => 6,
// 'supports' => array( 'title', 'editor','comments'),
// 'taxonomies' => array(''), //分类
'has_archive' => true
// 'taxonomies'=> array(''), //标签
)
);
}
add_action('init','jinsom_add_post_type_shop');

//商城分类
function jinsom_post_type_goods_cat(){
register_taxonomy(
'shop', //分类slug
'goods', //type
array(
'labels' => array(
'name' => '商品分类',
'add_new_item' => '添加分类',
'new_item_name' => "新分类"
),
'show_ui' => true,
'show_in_rest'      => true,
'rest_base'    => 'category',
'rest_controller_class' => 'WP_REST_Terms_Controller',
'show_tagcloud' => false,
'hierarchical' => true,
// 'rewrite' => array('slug' =>'shops', 'with_front' => true),
)
);
}
add_action('init','jinsom_post_type_goods_cat',0);



//匿名
function jinsom_add_post_type_secret(){
$name='匿名';
register_post_type('secret',
array(
'labels' => array(
'name' => '匿名',
'singular_name' => '所有'.$name,
'add_new' => '添加'.$name,
'add_new_item' => '添加'.$name,
'edit' => '编辑',
'edit_item' => '编辑'.$name,
'new_item' => '添加'.$name,
'view' => '查看'.$name,
'view_item' => '查看'.$name,
'search_items' => '搜索'.$name,
'not_found' => '没有找到相关'.$name,
),
'menu_icon'=> 'dashicons-format-status',
'exclude_from_search'=>false,
'public' => true,
'menu_position' => 6,
)
);
}
add_action('init','jinsom_add_post_type_secret');




//自定义文章类型=分类=固定链接
$custom_post_type = array(
'goods' => jinsom_get_option('jinsom_shop_goods_slug_name'),//type=>slug
);

function jinsom_custom_shop_link($link,$post=0){
global $custom_post_type;
if(in_array($post->post_type,array_keys($custom_post_type))){
return home_url($custom_post_type[$post->post_type].'/'.$post->ID.'.html');
}else{
return $link;
}
}
add_filter('post_type_link','jinsom_custom_shop_link',1,3);

function jinsom_custom_shop_rewrites_init(){
global $custom_post_type;
foreach($custom_post_type as $k => $v){
add_rewrite_rule(
$v.'/([0-9]+)?.html$',
'index.php?post_type='.$k.'&p=$matches[1]',
'top');
}
}
add_action('init','jinsom_custom_shop_rewrites_init');