<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_sign_ranking', array(
'title'       => 'LightSNS_签到排行榜',
'classname'   => 'jinsom-widget-sign-ranking',
'description' => '侧栏显示签到的排名信息',
'fields'      => array(

array(
'id'      => 'title_a',
'type'    => 'text',
'title'   => '今日签到标题',
'default'   => '今日签到',
),

array(
'id'      => 'title_b',
'type'    => 'text',
'title'   => '累计签到标题',
'default'   => '累计签到',
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


if(!function_exists('jinsom_widget_sign_ranking')){

function jinsom_widget_sign_ranking_html($user_id,$desc,$number,$target){
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
<div class="number">累计<i>'.$number.'</i>天</div>
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';	
}

function jinsom_widget_sign_ranking($args,$instance){
echo $args['before_widget'];
$title_a = $instance['title_a']; 
$title_b = $instance['title_b'];
$number = $instance['number'];  
$target = $instance['target']; 
$style=$instance['style'];
if($target){$target='target="_blank"';}else{$target='';}

// $user_query_a = new WP_User_Query( array ( 
// 'orderby' => 'meta_value',
// 'order' => 'ASC',
// 'count_total'=>false,
// 'number'=> $number,
// 'meta_key' => 'sign_microtime',
// 'meta_query' => array(
// array(
// 'key' => 'daily_sign',
// 'value' =>date('Y-m-d'),
// 'compare'=>'LIKE',	
// )
// )
// ));
global $wpdb;
$table_name=$wpdb->prefix.'jin_sign';
$today=date('Y-m-d',time());
$new_data=$wpdb->get_results("SELECT * FROM $table_name WHERE date='$today' ORDER BY strtotime limit $number;");


$user_query_b = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'count_total'=>false,
'meta_key' => 'sign_c',
'number'=> $number,
));	


echo '
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title" style="text-align: center;">
<li class="layui-this">'.$title_a.'</li>
<li>'.$title_b.'</li>
</ul>
<div class="layui-tab-content">';

echo '<div class="layui-tab-item layui-show">';
echo '<div class="jinsom-sidebar-user-list sign-a '.$style.'">';
if($new_data){
foreach ($new_data as $data){
$sign_user_id=$data->user_id;
$sign_time=__('今天','jinsom').date('H:i',$data->strtotime);
$sign_c=get_user_meta($sign_user_id,'sign_c',true);//累计
jinsom_widget_sign_ranking_html($sign_user_id,$sign_time,$sign_c,$target);
}
}else{
echo jinsom_empty();
}
echo '</div></div>';

echo '<div class="layui-tab-item">';
echo '<div class="jinsom-sidebar-user-list sign-b '.$style.'">';
if (!empty($user_query_b->results)){
foreach ($user_query_b->results as $user){
$user_id=$user->ID;
$desc=get_user_meta($user_id,'description',true);
$number=get_user_meta($user_id,'sign_c',true);
jinsom_widget_sign_ranking_html($user_id,$desc,$number,$target);
}
}else{
echo jinsom_empty();
}
echo '</div></div>';

echo '</div>';//layui-tab-content

if(count($new_data)>=$instance['number']&&jinsom_get_option('jinsom_sign_page_url')){
echo '<a href="'.jinsom_get_option('jinsom_sign_page_url').'" class="more-sign" target="_blank">查看更多</a>';
}

echo '</div>';//layui-tab
echo $args['after_widget'];
}
}




