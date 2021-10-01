<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_slider', array(
'title'       => 'LightSNS_幻灯片',
'classname'   => 'jinsom-widget-slider',
'description' => '图片幻灯片展示',
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '标题',
'placeholder' => '留空则不显示'
),


array(
'id' => 'slider_add',
'type' => 'group',
'title' => '添加幻灯片',
'button_title' => '添加',
'fields' => array(


array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '图片地址',
'subtitle'=>'宽度：300px,高度看你需求',
'placeholder' => 'https://'
),

array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'subtitle'=>'可以为空',
'placeholder' => 'https://'
) ,


)
), //结束


array(
'id' => 'height',
'type' => 'spinner',
'default' => 200,
'title' => '幻灯片高度',
),

array(
'id'                 => 'type',
'type'               => 'radio',
'title'              => '展示类型',
'options'            => array(
'left'              => '左右切换',
'top'              => '上下切换',
),
'default'       =>'left',
),

array(
'id' => 'autoplay',
'type' => 'switcher',
'default' => false,
'title' => '自动播放',
),

array(
'id' => 'indicator',
'type' => 'switcher',
'default' => false,
'title' => '显示指示器',
),

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口',
),


)
));


if(!function_exists('jinsom_widget_slider')){
function jinsom_widget_slider($args,$instance){
echo $args['before_widget'];
if (!empty($instance['title'])){
echo $args['before_title'] . apply_filters('widget_title',$instance['title']).$args['after_title'];
}
$slider_add=$instance['slider_add'];
$target=$instance['target'];
$type=$instance['type'];
$height=$instance['height'];
$autoplay=$instance['autoplay'];
$indicator=$instance['indicator'];
$rand=rand(1000,99999);
if($autoplay){$autoplay='true';}else{$autoplay='';}
if($indicator){$indicator='inside';}else{$indicator='none';}
if($type=='left'){$type='default';}else{$type='updown';}
if($target){$target='target="_blank"';}else{$target='';}
echo '<div class="layui-carousel" id="jinsom-widget-slider-'.$rand.'"><div carousel-item>';
if($slider_add){
foreach ($slider_add as $data){
echo '<a href="'.$data['link'].'" '.$target.'><img src="'.$data['images'].'"></a>';
}

}


echo '</div></div>';
?>
<script>
layui.use('carousel', function(){
var carousel = layui.carousel;
carousel.render({
elem: '#jinsom-widget-slider-<?php echo $rand;?>',
width: '100%',
height: <?php echo $height;?>,
arrow: 'hover',
anim: '<?php echo $type;?>',
autoplay:'<?php echo $autoplay;?>',
indicator:'<?php echo $indicator;?>',
});
});
</script>
<?php 
echo $args['after_widget'];
}
}

