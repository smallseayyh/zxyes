<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_topic_rank', array(
'title'       => 'LightSNS_话题排行榜',
'classname'   => 'jinsom-widget-topic-rank',
'description' => '话题排行榜',
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '标题',
'default'   => '话题排行榜',
),

array(
'id'      => 'number',
'type'       => 'spinner',
'title'   => '显示数量',
'default'   => 10,
),

array(
'id'      => 'more-title',
'type'    => 'text',
'title'   => '更多话题标题',
'default'   => '更多话题',
),

array(
'id'      => 'more-link',
'type'    => 'text',
'title'   => '更多话题链接',
'desc'   => '为空则不显示“查看更多话题”按钮',
'placeholder' => 'https://'
),

)
));


if(!function_exists('jinsom_widget_topic_rank')){
function jinsom_widget_topic_rank($args,$instance){
echo $args['before_widget'];
if (!empty($instance['title'])){
echo $args['before_title'] . apply_filters('widget_title',$instance['title']).$args['after_title'];
}
echo '<div class="jinsom-publish-topic-content">';
$terms_args=array(
'number'=>$instance['number'],
'taxonomy'=>'post_tag',//话题
'meta_key'=>'topic_views',
'orderby'=>'meta_value_num',
'hide_empty'=>false,
'order'=>'DESC'
);
$tag_arr=get_terms($terms_args);
if(!empty($tag_arr)){
$i=1;
foreach ($tag_arr as $tag) {
if($i==1){
$number='a';
}else if($i==2){
$number='b';
}else if($i==3){
$number='c';
}else{
$number='';	
}
$topic_id=$tag->term_id;
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
echo '<li class="'.$number.'" title="'.$tag->name.'"><a href="'.get_tag_link($topic_id).'" target="_blank"># '.$tag->name.' #<span><i class="jinsom-icon hot jinsom-huo"></i> '.$topic_views.'</span></a></li>';
$i++;
}
}
echo '</div>';
$link=$instance['more-link'];
if($link){
echo '<div class="footer"><a href="'.$link.'" target="_blank">'.$instance['more-title'].'</a></div>';
}
echo $args['after_widget'];
}
}

