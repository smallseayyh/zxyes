<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_bbs_list', array(
'title'       => 'LightSNS_'.__('帖子列表','jinsom'),
'classname'   => 'jinsom-widget-single-list',
'description' => __('展示帖子类型的内容列表','jinsom'),
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => __('标题','jinsom'),
'default'   => __('最新内容','jinsom'),
),

array(
'id'                 => 'type',
'type'               => 'radio',
'title'              => __('数据类型','jinsom'),
'options'            => array(
'new'              => __('最新的帖子','jinsom'),
'comment'              => __('评论最多的帖子','jinsom'),
'views'         => __('浏览量最多的帖子','jinsom'),
'rand'         => __('随机的帖子','jinsom'),
'custom'         => __('指定话题的帖子','jinsom'),
'pay'         => __('付费类型帖子','jinsom'),
'bbs'         => __('指定论坛的帖子','jinsom'),
'current_bbs_new'         => __('当前论坛最新帖子','jinsom').'<font style="color:#f00;">('.__('只能在论坛/论坛内页用','jinsom').')</font>',
'current_bbs_rand'        => __('当前论坛随机帖子','jinsom').'<font style="color:#f00;">('.__('只能在论坛/论坛内页用','jinsom').')</font>',
),
'default'       =>'new',
),


array(
'id'         => 'topic_names',
'type'       => 'textarea',
'title'      => __('指定话题ID','jinsom'),
'desc'      =>__('多个话题ID请用英文逗号隔开','jinsom'),
'placeholder' => '1,2,3,4,5,6',
'dependency' => array('type','==','custom') ,
),

array(
'id'         => 'custom_bbs',
'type'       => 'textarea',
'title'      => __('指定论坛ID','jinsom'),
'desc'      =>__('多个论坛ID请用英文逗号隔开','jinsom'),
'placeholder' => '1,2,3,4,5,6',
'dependency' => array('type','==','bbs') ,
),


array(
'id'                 => 'style',
'type'               => 'radio',
'title'              => __('展示样式','jinsom'),
'options'            => array(
'left'              => __('单列左右结构','jinsom'),
'one'              => __('单列上下结构','jinsom'),
'two'              => __('两列上下结构','jinsom'),
'no-img'              => __('无图','jinsom'),
),
'default'       =>'one',
),

array(
'id'      => 'number',
'type'       => 'spinner',
'title'   => __('显示数量','jinsom'),
'default'   => 6,
),

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => __('新窗口打开','jinsom'),
), 

)
));


if(!function_exists('jinsom_widget_bbs_list')){
function jinsom_widget_bbs_list($args,$instance){
echo $args['before_widget'];
if (!empty($instance['title'])){
echo $args['before_title'] . apply_filters('widget_title',$instance['title']).$args['after_title'];
}
$number=$instance['number'];
$target=$instance['target'];
$type=$instance['type'];
$topic_names=$instance['topic_names'];
$custom_bbs=$instance['custom_bbs'];
$style=$instance['style'];
if($target){$target='target="_blank"';}else{$target='';}
if($type=='new'){
$single_args = array(
'post_status' =>'publish',
'post_parent'=>999999999,
'showposts' => $number,
);	
}else if($type=='comment'){
$single_args = array(
'post_status' =>'publish',
'post_parent'=>999999999,
'showposts' => $number,
'orderby'   => 'comment_count', 
);		
}else if($type=='views'){
$single_args = array(
'post_status' =>'publish',
'meta_key' => 'post_views',
'showposts' => $number,
'orderby'   => 'meta_value_num', 
'post_parent'=>999999999,
);		
}else if($type=='rand'){
$single_args = array(
'post_status' =>'publish',
'post_parent'=>999999999,
'showposts' => $number,
'orderby'   => 'rand', 
);		
}else if($type=='custom'){
$single_args = array(
'post_status' =>'publish',
'post_parent'=>999999999,
'showposts' => $number,
'tag__in' =>explode(",",$topic_names)
);		
}else if($type=='bbs'){
$single_args = array(
'post_status' =>'publish',
'post_parent'=>999999999,
'showposts' => $number,
'cat' =>explode(",",$custom_bbs)
);		
}else if($type=='pay'){
$single_args = array(
'post_status' =>'publish',
'showposts' => $number,
'post_parent'=>999999999,
'meta_key' => 'post_type',
'meta_value'=>'pay_see',
);		
}else if($type=='current_bbs_new'){
global $bbs_id;
$single_args = array(
'post_status' =>'publish',
'post_parent'=>999999999,
'showposts' => $number,
'cat' =>array($bbs_id)
);		
}else if($type=='current_bbs_rand'){
global $bbs_id;
$single_args = array(
'post_status' =>'publish',
'post_parent'=>999999999,
'showposts' => $number,
'orderby'   => 'rand', 
'cat' =>array($bbs_id)
);		
}



echo '<div class="jinsom-widget-single-video-list '.$style.' single-special clear">';
$single_args['ignore_sticky_posts']=1;
$single_args['no_found_rows']=true;
query_posts($single_args);
if (have_posts()) {
$i=1;
while ( have_posts() ) : the_post();
$author_id=get_the_author_meta('ID');
$title=get_the_title();
if($style=='one'){
echo '<li>
<a href="'.get_the_permalink().'" '.$target.'>
<div class="cover opacity">
<img src="'.jinsom_single_cover(get_the_content()).'" alt="'.strip_tags($title).'">
<div class="title">'.$title.'</div>
</div>
</a>
</li>';	
}else if($style=='two'){
echo '<li>
<a href="'.get_the_permalink().'" '.$target.'>
<div class="cover opacity"><img src="'.jinsom_single_cover(get_the_content()).'" alt="'.strip_tags($title).'"></div>
<div class="title">'.$title.'</div>
</a>
</li>';	
}else if($style=='no-img'){
echo '<li><a href="'.get_the_permalink().'" '.$target.'><i>'.$i.'</i>'.$title.'</a></li>';	
}else{
echo '<li>
<a href="'.get_the_permalink().'" '.$target.'>
<div class="cover opacity"><img src="'.jinsom_single_cover(get_the_content()).'" alt="'.strip_tags($title).'"></div>
<div class="info">
<div class="title">'.$title.'</div>
<div class="desc"><span title="'.get_the_time('Y-m-d H:i:s').'">'.get_the_time('m-d').'</span><span class="name">'.get_user_meta($author_id,'nickname',true).'</span></div>
</div>
</a>
</li>';	
}

$i++;
endwhile;
}else{
echo jinsom_empty();	
}
wp_reset_query();
echo '</div>';
echo $args['after_widget'];
}
}

