<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_income_reward_ranking', array(
'title'       => 'LightSNS_打赏/收益排行',
'classname'   => 'jinsom-widget-income-reward-ranking',
'description' => '显示用户当天收益或打赏别人的排行榜',
'fields'      => array(


array(
'id'      => 'title_b',
'type'    => 'text',
'title'   => '打赏排行标题',
'default'   => '总打赏排行',
),


array(
'id'      => 'title_a',
'type'    => 'text',
'title'   => '今日收益标题',
'default'   => '今日收益排行',
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


if(!function_exists('jinsom_widget_income_reward_ranking')){

function jinsom_widget_income_reward_ranking_html($user_id,$desc,$number){
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

function jinsom_widget_income_reward_ranking($args,$instance){
$title_a = $instance['title_a']; 
$title_b = $instance['title_b']; 
$number = $instance['number'];  
$style=$instance['style'];
if($instance['target']){$target='target="_blank"';}else{$target='';}

$user_query_a = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'count_total'=>false,
'meta_key' => 'today_income',
'number'=> $number
));

$user_query_b = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'meta_key' => 'reward',
'count_total'=>false,
'number'=> $number
));	
$credit_name=jinsom_get_option('jinsom_credit_name');
$default_desc=jinsom_get_option('jinsom_user_default_desc_b');

echo $args['before_widget'];

echo '
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title" style="text-align: center;">
<li class="layui-this">'.$title_b.'</li>
<li>'.$title_a.'</li>
</ul>
<div class="layui-tab-content">';

echo '<div class="layui-tab-item layui-show">'; 
echo '<div class="jinsom-sidebar-user-list reward '.$style.'">';
if (!empty($user_query_b->results)){//打赏排行
foreach ($user_query_b->results as $user){
$user_id=$user->ID;
$user_info = get_userdata($user_id);
$desc=$user_info->description;
if(empty($desc)){$desc=$default_desc;}
$number='打赏了<i>'.get_user_meta($user_id,'reward',true).'</i>'.$credit_name;
jinsom_widget_income_reward_ranking_html($user_id,$desc,$number);
}
}else{
echo jinsom_empty();
}

echo '</div></div>';

echo '<div class="layui-tab-item">';
echo '<div class="jinsom-sidebar-user-list income '.$style.'">';
if (!empty($user_query_a->results)){//收益排行
foreach ($user_query_a->results as $user){
$user_id=$user->ID;
$user_info = get_userdata($user_id);
$desc=$user_info->description;
if(empty($desc)){$desc=$default_desc;}
$number='今日获得<i>'.get_user_meta($user_id,'today_income',true).'</i>'.$credit_name;
jinsom_widget_income_reward_ranking_html($user_id,$desc,$number);
}
}else{
echo jinsom_empty();
}
echo '</div></div>';

echo '</div>';//layui-tab-content
echo '</div>';//layui-tab
echo $args['after_widget'];
}
}
