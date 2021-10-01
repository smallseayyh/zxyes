<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_images', array(
'title'       => 'LightSNS_图片展示',
'classname'   => 'jinsom-widget-images',
'description' => '用于展示图片',
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '标题',
'default'   => '图片展示',
),


array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '图片地址',
'placeholder' => 'https://'
),

array(
'id'      => 'link',
'type'    => 'text',
'title'   => '链接',
'default'   => '#',
'placeholder' => 'https://'
),

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口',
), 

)
));


if(!function_exists('jinsom_widget_images')){
function jinsom_widget_images($args,$instance){
echo $args['before_widget'];
if (!empty($instance['title'])){
echo $args['before_title'] . apply_filters('widget_title',$instance['title']).$args['after_title'];
}
$target=$instance['target'];
if($target){$target='target="_blank"';}else{$target='';}
echo '<a href="'. $instance['link'] .'" '.$target.' ><img src="'. $instance['images'] .'" /></a>';
echo $args['after_widget'];

}
}

