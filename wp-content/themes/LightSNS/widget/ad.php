<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_ad', array(
'title'       => 'LightSNS_广告位',
'classname'   => 'jinsom-widget-ad',
'description' => '广告位展示，到期自动屏蔽',
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '标题',
'placeholder' => '留空则不显示'
),

array(
'id'      => 'join_title',
'type'    => 'text',
'title'   => '加入标题',
'placeholder' => '留空则不显示'
),

array(
'id'      => 'join_link',
'type'    => 'text',
'title'   => '加入链接',
'placeholder' => 'https://'
),


array(
'id' => 'ad_add',
'type' => 'group',
'title' => '添加广告',
'button_title' => '添加',
'fields' => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '广告名称',
'placeholder' => '不显示，仅仅为备注'
),


array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '广告图片',
'subtitle'=>'默认100px*300px 可以根据自己需求修改尺寸',
'placeholder' => 'https://'
),

array(
'id' => 'link',
'type' => 'text',
'title' => '广告链接',
'subtitle'=>'新窗口打开、nofollow',
'placeholder' => 'https://'
),

array(
'id'      => 'date',
'type'    => 'date',
'title'   => '广告到期时间',
'default'   => '2020-12-30',
'placeholder' => '到期之后，将自动屏蔽该广告'
),


)
), //结束


array(
'id'      => 'desc',
'type'    => 'textarea',
'title'   => '底部说明',
'subtitle'   => '一般用户简单的说明几句话',
'placeholder' => '留空则显示'
),



)
));


if(!function_exists('jinsom_widget_ad')){
function jinsom_widget_ad($args,$instance){
echo $args['before_widget'];
$title=$instance['title'];
$join_title=$instance['join_title'];
$join_link=$instance['join_link'];

if($title||$join_title){
echo '<div class="jinsom-widget-ad-header">';	
if($title){
echo '<div class="title">'.$title.'</div>';
}
if($join_title){
echo '<div class="join-title"><a href="'.$join_link.'" target="_blank">'.$join_title.'</a></div>';
}
echo '</div>';
}

echo '<div class="jinsom-widget-ad-content">';
$ad_add=$instance['ad_add'];

$desc=$instance['desc'];;
if($ad_add){
foreach ($ad_add as $data) {
if(time()<strtotime($data['date'])){
echo '<li onclick="jinsom_click_ad()"><a href="'.$data['link'].'" rel="nofollow" target="_blank"><img class="opacity" src="'.$data['images'].'" time='.$data['date'].'></a></li>';
}
}
}else{
echo jinsom_empty();
}

if($desc){
echo '<div class="desc">'.$desc.'</div>';
}

echo '</div>';

echo $args['after_widget'];
}
}

