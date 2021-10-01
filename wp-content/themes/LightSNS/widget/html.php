<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_html', array(
'title'       => 'LightSNS_html代码',
'classname'   => 'jinsom-widget-html',
'description' => '支持html代码、php代码、短代码',
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '备注',
'placeholder'   => '仅用于备注说明，不在小工具不显示',
),

array(
'id'      => 'content',
'type'    => 'code_editor',
'title'   => 'HTML代码',
'settings' => array(
'mode'   => 'htmlmixed',
),
'default'  => '<h1>Hello world</h1>',
),

)
));


if(!function_exists('jinsom_widget_html')){
function jinsom_widget_html($args,$instance){
echo $args['before_widget'];
$html=do_shortcode($instance['content']);
if(strpos($html,"<"."?php")!==false){
ob_start();
eval("?".">".$html);
$html=ob_get_contents();
ob_end_clean();
}
echo '<div class="jinsom-widget-html">'.$html.'</div>';
echo $args['after_widget'];
}
}

