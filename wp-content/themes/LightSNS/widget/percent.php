<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_percent', array(
'title'       => 'LightSNS_进度条',
'classname'   => 'jinsom-widget-percent',
'description' => '侧栏显示进度条小工具',
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '标题',
'default'   => '进度',
),

array(
'id'      => 'number',
'type'       => 'spinner',
'unit'     => '%',
'title'   => '百分比',
'default'   => 10,
),

array(
'id'      => 'content',
'type'    => 'textarea',
'title'   => '显示内容',
'placeholder'   => '支持HTML',
),

)
));


if(!function_exists('jinsom_widget_percent')){
function jinsom_widget_percent($args,$instance){
echo $args['before_widget'];
if (!empty($instance['title'])){
echo $args['before_title'] . apply_filters('widget_title',$instance['title']).$args['after_title'];
}
echo '
<div class="layui-progress" lay-showpercent="yes">
<div class="layui-progress-bar" lay-percent="'.$instance['number'].'%" style="width: '.$instance['number'].'%;"><span class="layui-progress-text">'.$instance['number'].'%</span></div>
</div>
<div class="content">'.$instance['content'].'</div>';
echo $args['after_widget'];
}
}

