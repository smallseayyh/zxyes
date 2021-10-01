<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_bbs_commend_list', array(
'title'       => 'LightSNS_'.__('论坛展示/申请入口','jinsom'),
'classname'   => 'jinsom-widget-bbs-commend-list',
'description' => __('用于展示论坛列表和申请论坛开通入口','jinsom'),
'fields'      => array(

array(
'id' => 'title',
'type' => 'text',
'default' => false,
'title' => __('标题','jinsom'),
'placeholder' => __('留空则不显示','jinsom'),
),

array(
'id'                 => 'type',
'type'               => 'radio',
'title'              => __('显示类型','jinsom'),
'options'            => array(
'custom'              => __('自定义手动输入','jinsom'),
'follow'              => __('我关注的论坛','jinsom'),
'no'              => __('不显示论坛','jinsom'),
),
'default'       =>'custom',
),

array(
'id' => 'bbs_ids',
'type' => 'textarea',
'default' => false,
'dependency' => array('type','==','custom'),
'title' => __('展示的论坛ID','jinsom'),
'placeholder' => __('请输入论坛ID，多个ID请用英文逗号隔开，留空则不显示论坛列表','jinsom'),
),

array(
'id'      => 'number',
'type'       => 'spinner',
'dependency' => array('type','==','follow'),
'title'   => __('显示数量','jinsom'),
'default'   => 6,
),


array(
'id'                 => 'style',
'type'               => 'radio',
'title'              => __('显示风格','jinsom'),
'dependency' => array('type','!=','no'),
'options'            => array(
'a'              => __('列表布局-a','jinsom'),
'b'              => __('列表布局-b','jinsom'),
),
'default'       =>'a',
),

array(
'id' => 'apply_on_off',
'type' => 'switcher',
'default' => false,
'title' => __('是否显示申请入口','jinsom'),
),

array(
'id' => 'apply_title',
'type' => 'text',
'default' => false,
'dependency' => array('apply_on_off','==','true'),
'title' => __('申请入口名称','jinsom'),
'default' => __('申请开通论坛','jinsom'),
),

array(
'id' => 'apply_color',
'type' => 'color',
'default' => false,
'dependency' => array('apply_on_off','==','true'),
'title' => __('申请入口背景颜色','jinsom'),
'default' => '#5fb878',
),

)
));


if(!function_exists('jinsom_widget_bbs_commend_list')){
function jinsom_widget_bbs_commend_list($args,$instance){
echo $args['before_widget'];
if (!empty($instance['title'])){
echo $args['before_title'] . apply_filters('widget_title',$instance['title']).$args['after_title'];
}
global $current_user;
$user_id=$current_user->ID;
$apply=$instance['apply_on_off'];
$apply_title=$instance['apply_title'];
$apply_color=$instance['apply_color'];
$style=$instance['style'];
$type=$instance['type'];

if($type!='no'){
echo '<div class="jinsom-sidebar-user-list bbs-commend-list '.$style.'">';


if($type=='follow'){//关注的
$number=$instance['number'];
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
$bbs_id_arr = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id ORDER BY ID DESC limit $number;");
}else{//手动输入
$bbs_ids=$instance['bbs_ids'];
$bbs_id_arr=explode(",",$bbs_ids);
}

if(($type=='custom'&&$bbs_ids)||($type=='follow'&&$bbs_id_arr)){
foreach($bbs_id_arr as $bbs_id){
if($type=='follow'){
$bbs_id=$bbs_id->bbs_id;
$bbs_link=get_category_link($bbs_id);
$follow_btn='<a href="'.$bbs_link.'" target="_blank" class="opacity visit">点击进入</a>';
}else{
$bbs_link=get_category_link($bbs_id);
$follow_btn=jinsom_bbs_like_btn($user_id,$bbs_id);
}


echo '
<li class="clear">
<div class="avatarimg">
<a href="'.$bbs_link.'" target="_blank">'.jinsom_get_bbs_avatar($bbs_id,0).'</a>
</div>
<div class="info">
<a href="'.$bbs_link.'" target="_blank">'.get_category($bbs_id)->name.'</a>
<div class="time">'.strip_tags(get_term_meta($bbs_id,'desc',true)).'</div>
</div>
<div class="follow">
'.$follow_btn.'
</div>
</li>';	
}
}else{
echo jinsom_empty();
}


echo '</div>';
}
if($apply&&is_user_logged_in()){
echo '<div class="apply opacity" onclick="jinsom_apply_bbs_form()" style="background-color:'.$apply_color.'">'.$apply_title.'</div>';
}

echo $args['after_widget'];
}
}
