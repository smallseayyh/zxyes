<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_single_list', array(
'title'       => 'LightSNS_文章列表',
'classname'   => 'jinsom-widget-single-list',
'description' => '展示文章类型的内容列表',
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '标题',
'default'   => '最新内容',
),

array(
'id'                 => 'type',
'type'               => 'radio',
'title'              => '数据类型',
'options'            => array(
'new'              => '最新的文章',
'comment'              => '评论最多的文章',
'views'         => '浏览量最多的文章',
'rand'         => '随机的文章',
'custom'         => '指定话题的文章',
'pay'         => '付费类型文章',
'password'         => '密码类型文章',
'vip'         => '会员可见文章',
'login'         => '登录可见文章',
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
'no-img'              => '无图',
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


if(!function_exists('jinsom_widget_single_list')){
function jinsom_widget_single_list($args,$instance){
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
'meta_value'=>'single',
'showposts' => $number,
'post_parent'=>0,
'ignore_sticky_posts' => 1,
);	
}else if($type=='comment'){
$single_args = array(
'post_status' =>'publish',
'meta_key' => 'post_type',
'meta_value'=>'single',
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
'value' => 'single',
)
),
);		
}else if($type=='rand'){
$single_args = array(
'post_status' =>'publish',
'meta_key' => 'post_type',
'meta_value'=>'single',
'showposts' => $number,
'post_parent'=>0,
'ignore_sticky_posts' => 1,
'orderby'   => 'rand', 
);		
}else if($type=='custom'){
$single_args = array(
'post_status' =>'publish',
'meta_key' => 'post_type',
'meta_value'=>'single',
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
'value' => 'single',
),
array(
'key' => 'post_power',
'value' => $power,
)
),
);		
}



echo '<div class="jinsom-widget-single-video-list '.$style.' single-special clear">';

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
<div class="desc"><span title="'.get_the_time('Y-m-d H:i:s').'">'.get_the_time('m月d日').'</span><span class="name">'.get_user_meta($author_id,'nickname',true).'</span></div>
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

