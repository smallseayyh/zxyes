<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_video_list', array(
'title'       => 'LightSNS_视频列表',
'classname'   => 'jinsom-widget-video-list',
'description' => '展示视频类型的内容列表',
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '标题',
'default'   => '最新视频',
),

array(
'id'                 => 'type',
'type'               => 'radio',
'title'              => '视频类型',
'options'            => array(
'new'              => '最新的视频',
'comment'              => '评论最多的视频',
'views'         => '浏览量最多的视频',
'rand'         => '随机的视频',
'custom'         => '指定话题的视频',
'pay'         => '付费类型视频',
'password'         => '密码类型视频',
'vip'         => '会员可见视频',
'login'         => '登录可见视频',
),
'default'       =>'new',
),


array(
'id'         => 'topic_names',
'type'       => 'textarea',
'title'      => '指定话题',
'desc'      =>'只需要输入话题ID，多个话题请用英文逗号隔开',
'placeholder' => '1,2,3,4,5,6',
'dependency' => array('type','==','custom') ,
),


array(
'id'                 => 'style',
'type'               => 'radio',
'title'              => '展示样式',
'options'            => array(
'left'              => '单列左右结构',
'one'              => '单列上下结构',
'two'              => '两列上下结构',
),
'default'       =>'one',
),

array(
'id'      => 'number',
'type'       => 'spinner',
'title'   => '显示数量',
'default'   => 6,
),

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口',
), 

)
));


if(!function_exists('jinsom_widget_video_list')){
function jinsom_widget_video_list($args,$instance){
echo $args['before_widget'];
if (!empty($instance['title'])){
echo $args['before_title'] . apply_filters('widget_title',$instance['title']).$args['after_title'];
}
$number=$instance['number'];
$target=$instance['target'];
$type=$instance['type'];
$topic_names=$instance['topic_names'];
$style=$instance['style'];
if($target){$target='target="_blank"';}else{$target='';}
if($type=='new'){
$single_args = array(
'post_status' =>'publish',
'meta_key' => 'post_type',
'meta_value'=>'video',
'showposts' => $number,
'post_parent'=>0,
'ignore_sticky_posts' => 1,
);	
}else if($type=='comment'){
$single_args = array(
'post_status' =>'publish',
'meta_key' => 'post_type',
'meta_value'=>'video',
'showposts' => $number,
'post_parent'=>0,
'ignore_sticky_posts' => 1,
'orderby'   => 'comment_count', 
);		
}else if($type=='views'){
$single_args = array(
'post_status' =>'publish',
'meta_key' => 'post_views',
'showposts' => $number,
'post_parent'=>0,
'ignore_sticky_posts' => 1,
'orderby'   => 'meta_value_num', 
'meta_query' => array(
array(
'key' => 'post_type',
'value' => 'video',
)
),
);		
}else if($type=='rand'){
$single_args = array(
'post_status' =>'publish',
'meta_key' => 'post_type',
'meta_value'=>'video',
'showposts' => $number,
'post_parent'=>0,
'ignore_sticky_posts' => 1,
'orderby'   => 'rand', 
);		
}else if($type=='custom'){
$single_args = array(
'post_status' =>'publish',
'meta_key' => 'post_type',
'meta_value'=>'video',
'showposts' => $number,
'post_parent'=>0,
'ignore_sticky_posts' => 1,
'tag__in' =>explode(",",$topic_names)
);		
}else if($type=='pay'||$type=='vip'||$type=='password'||$type=='login'){
if($type=='pay'){
$power=1;
}else if($type=='password'){
$power=2;	
}else if($type=='vip'){
$power=4;	
}else if($type=='login'){
$power=5;	
}
$single_args = array(
'post_status' =>'publish',
'showposts' => $number,
'post_parent'=>0,
'ignore_sticky_posts' => 1,
'meta_query' => array(
array(
'key' => 'post_type',
'value' => 'video',
),
array(
'key' => 'post_power',
'value' => $power,
)
),
);		
}



echo '<div class="jinsom-widget-single-video-list '.$style.' clear">';
$single_args['no_found_rows']=true;
query_posts($single_args);
if (have_posts()) {
while ( have_posts() ) : the_post();
$post_id=get_the_ID();
$author_id=get_the_author_meta('ID');
$video_title=get_the_title();
if($video_title==''){$video_title=get_the_content();}
$video_post_views=(int)get_post_meta($post_id,'post_views',true);
$video_time=get_post_meta($post_id,'video_time',true);
if($video_time){
$video_time='<span class="topic">'.jinsom_secToTime($video_time).'</span>';
}else{
$video_time='';
}
if($style=='two'||$style=='one'){
echo '<li>
<a href="'.get_the_permalink().'" '.$target.'>
<div class="cover opacity"><i class="jinsom-icon jinsom-bofang- play-icon"></i><img src="'.jinsom_video_cover($post_id).'" alt="'.strip_tags($video_title).'">'.$video_time.'</div>
<div class="title">'.$video_title.'</div>
</a>
</li>';	
}else{
echo '<li>
<a href="'.get_the_permalink().'" '.$target.'>
<div class="cover opacity"><img src="'.jinsom_video_cover($post_id).'" alt="'.strip_tags($video_title).'"></div>
<div class="info">
<div class="title">'.$video_title.'</div>
<div class="desc"><span><i class="jinsom-icon jinsom-bofang2"></i> '.jinsom_views_show($video_post_views).'</span><span class="name">'.get_user_meta($author_id,'nickname',true).'</span></div>
</div>
</a>
</li>';	
}


endwhile;
}else{
echo jinsom_empty();	
}
wp_reset_query();
echo '</div>';
echo $args['after_widget'];
}
}

