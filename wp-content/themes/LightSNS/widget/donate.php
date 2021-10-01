<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_donate', array(
'title'       => 'LightSNS_赞助、打赏、捐助',
'classname'   => 'jinsom-widget-donate',
'description' => '侧栏显示各种二维码',
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '标题',
'default'   => '捐助',
),


array(
'id' => 'add',
'type' => 'group',
'title' => '添加二维码',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '二维码标题',
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '图片地址',
'placeholder' => 'https://',
'subtitle'=>'建议尺寸：300px*300px',
),

)
),

array(
'id'      => 'content',
'type'    => 'textarea',
'title'   => '显示内容',
'placeholder'   => '支持HTML',
),

)
));


if(!function_exists('jinsom_widget_donate')){
function jinsom_widget_donate($args,$instance){
echo $args['before_widget'];
if (!empty($instance['title'])){
echo $args['before_title'].apply_filters('widget_title',$instance['title']).$args['after_title'];
}

if($instance['add']){
$code_title='';
$code_img='';
$i=1;
foreach ($instance['add'] as $data) {
if($i==1){
$code_title.='<li class="layui-this">'.$data['title'].'</li>';
$code_img.='<div class="layui-tab-item layui-show""><img src="'.$data['images'].'"></div>';
}else{
$code_title.='<li>'.$data['title'].'</li>';
$code_img.='<div class="layui-tab-item"><img src="'.$data['images'].'"></div>';
}
$i++;
}

echo '
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title">'.$code_title.'</ul>
<div class="layui-tab-content">'.$code_img.'</div>
</div>';

if($instance['content']){
echo '<div class="jinsom-widget-donate-content">'.$instance['content'].'</div>';
}

}else{
echo jinsom_empty('请在小工具里添加二维码');
}


echo $args['after_widget'];
}
}

