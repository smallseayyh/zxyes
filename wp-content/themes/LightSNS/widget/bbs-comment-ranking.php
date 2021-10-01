<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_comment_adopt_ranking', array(
'title'       => 'LightSNS_'.__('回帖/采纳排行','jinsom'),
'classname'   => 'jinsom-widget-comment-adopt-ranking',
'description' => __('用户回帖和被采纳数目排行榜','jinsom'),
'fields'      => array(


array(
'id'      => 'title_a',
'type'    => 'text',
'title'   => __('回帖排行标题','jinsom'),
'default'   => __('回帖总排行','jinsom'),
),

array(
'id'      => 'title_b',
'type'    => 'text',
'title'   => __('采纳排行标题','jinsom'),
'default'   => __('采纳总排行','jinsom'),
),

array(
'id'                 => 'style',
'type'               => 'radio',
'title'              => '显示风格',
'options'            => array(
'a'              => '格子布局',
'b'              => '列表布局-a',
'c'              => '列表布局-b',
),
'default'       =>'a',
),

array(
'id'      => 'number',
'type'       => 'spinner',
'title'   => __('显示数量','jinsom'),
'default'   => 16,
),

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => __('新窗口打开','jinsom'),
), 

)
));


if(!function_exists('jinsom_widget_comment_adopt_ranking')){
function jinsom_widget_comment_adopt_ranking($args,$instance){
$title_a = $instance['title_a']; 
$title_b = $instance['title_b']; 
$number = $instance['number'];  
if($instance['target']){$target='target="_blank"';}else{$target='';}

$style=$instance['style'];
if($style=='b'){
$style_class='jinsom-sidebar-user-list a';
}else if($style=='c'){
$style_class='jinsom-sidebar-user-list b';
}else{
$style_class='jinsom-sidebar-lattice-list';	
}

$user_query_a = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'number' => $number,
'count_total'=>false,
'meta_key' => 'user_comment_number'
));
$user_query_b = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'number' => $number,
'count_total'=>false,
'meta_key' => 'user_adopt_number'
));	


echo $args['before_widget'];

echo '
<div class="layui-tab layui-tab-brief" style="margin-bottom:0;">
<ul class="layui-tab-title" style="text-align: center;">
<li class="layui-this">'.$title_a.'</li>
<li>'.$title_b.'</li>
</ul>
<div class="layui-tab-content">';

echo '<div class="layui-tab-item layui-show">';
echo '<div class="'.$style_class.' comment clear">'; 

if (!empty($user_query_a->results)){
foreach ($user_query_a->results as $user){
$user_id=$user->ID;
$number=sprintf(__( '%s次回帖','jinsom'),get_user_meta($user_id,'user_comment_number',true));
if($style=='b'||$style=='c'){
echo '
<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >'.jinsom_vip_icon($user_id).jinsom_avatar($user_id , '40' , avatar_type($user_id) ).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.$number.'</div>
</div>
<div class="follow">
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';
}else{
echo '<li title='.$number.'>
<a href="'.jinsom_userlink($user_id).'" '.$target.' >
'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'57',avatar_type($user_id)).'
<p>'.jinsom_nickname($user_id).'</p>
</a>
</li>';
}

}
}else{
echo jinsom_empty();	
}

echo '</div></div>';

echo '<div class="layui-tab-item">';
echo '<div class="'.$style_class.' adopt clear">';
if (!empty($user_query_b->results)){
foreach ($user_query_b->results as $user){
$user_id=$user->ID;
$number=sprintf(__( '%s次被采纳','jinsom'),get_user_meta($user_id,'user_adopt_number',true));
if($style=='b'||$style=='c'){
echo '
<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >'.jinsom_vip_icon($user_id).jinsom_avatar($user_id , '40' , avatar_type($user_id) ).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.$number.'</div>
</div>
<div class="follow">
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';
}else{
echo '<li title='.$number.'>
<a href="'.jinsom_userlink($user_id).'" '.$target.' >
'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'57',avatar_type($user_id)).'
<p>'.jinsom_nickname($user_id).'</p>
</a>
</li>';
}

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