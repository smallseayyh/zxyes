<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_credit_exp_rangking', array(
'title'       => 'LightSNS_等级/财富排行榜',
'classname'   => 'jinsom-widget-credit-exp-rangking',
'description' => '显示用户等级或财富的排行榜',
'fields'      => array(

array(
'id'      => 'title_a',
'type'    => 'text',
'title'   => '财富排行标题',
'default'   => '财富排行榜',
),

array(
'id'      => 'title_b',
'type'    => 'text',
'title'   => '等级排行标题',
'default'   => '等级排行榜',
),

array(
'id'                 => 'style',
'type'               => 'radio',
'title'              => '显示风格',
'options'            => array(
'a'              => '列表布局-a',
'b'              => '列表布局-b',
),
'default'       =>'a',
),


array(
'id'      => 'number',
'type'       => 'spinner',
'title'   => '显示数量',
'default'   => 10,
),

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口打开',
), 

)
));


if(!function_exists('jinsom_widget_credit_exp_rangking')){

function jinsom_widget_credit_exp_rangking_html($user_id,$desc,$number){
echo '
<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'40',avatar_type($user_id)).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.$desc.'</div>
</div>
<div class="follow">
<div class="number">'.$number.'</div>
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';	
}

function jinsom_widget_credit_exp_rangking($args,$instance){
$title_a = $instance['title_a']; 
$title_b = $instance['title_b']; 
$number = $instance['number'];  
$style=$instance['style'];
if($instance['target']){$target='target="_blank"';}else{$target='';}


$user_query_a = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'meta_key' => 'credit',
'count_total'=>false,
'number' =>$number
));

$user_query_b = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'meta_key' => 'exp',
'count_total'=>false,
'number' =>$number
));	


echo $args['before_widget'];

echo '
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title" style="text-align: center;">
<li class="layui-this">'.$title_a.'</li>
<li>'.$title_b.'</li>
</ul>
<div class="layui-tab-content">';
echo '<div class="layui-tab-item layui-show">';
echo '<div class="jinsom-sidebar-user-list credit '.$style.'">';


if (!empty($user_query_a->results)){
foreach ($user_query_a->results as $user){
$user_id=$user->ID;
$user_info = get_userdata($user_id);
$desc=$user_info->description;
if(empty($desc)){$desc=jinsom_get_option('jinsom_user_default_desc_b');}
$number=	'<i>'.get_user_meta($user_id,'credit',true).'</i>'.jinsom_get_option('jinsom_credit_name');
jinsom_widget_credit_exp_rangking_html($user_id,$desc,$number);
}
}

echo '</div></div>';

echo '<div class="layui-tab-item">';
echo '<div class="jinsom-sidebar-user-list exp '.$style.'">';
if (!empty($user_query_b->results)){
foreach ($user_query_b->results as $user){
$user_id=$user->ID;
$user_info = get_userdata($user_id);
$desc=$user_info->description;
if(empty($desc)){$desc=$default_desc;}
$number=	get_user_meta($user_id,'exp',true).' 经验';
jinsom_widget_credit_exp_rangking_html($user_id,$desc,$number);
}
}

echo '</div></div>';


echo '</div>';//layui-tab-content
echo '</div>';//layui-tab
echo $args['after_widget'];
}
}

