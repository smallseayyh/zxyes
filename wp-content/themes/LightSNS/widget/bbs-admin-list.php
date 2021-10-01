<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_bbs_admin_list', array(
'title'       => 'LightSNS_'.__('大小版主展示','jinsom'),
'classname'   => 'jinsom-widget-bbs-admin-list',
'description' => __('此小工具必须只能放在论坛页面或者帖子页面','jinsom'),
'fields'      => array(

array(
'type'    => 'content',
'content' => '<p><font style="color:#f00">'.__('这个小工具必须使用在论坛页面的侧栏或帖子页面的侧栏','jinsom').'</font></p>',
),

array(
'id'                 => 'style',
'type'               => 'radio',
'title'              => __('显示风格','jinsom'),
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
'default' => __('申请加入','jinsom'),
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


if(!function_exists('jinsom_widget_bbs_admin_list')){
function jinsom_widget_bbs_admin_list($args,$instance){
echo $args['before_widget'];
global $bbs_id;
if(is_category()||is_single()){
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);

//版主名称
$admin_a_name=get_term_meta($bbs_id,'admin_a_name',true);
$admin_b_name=get_term_meta($bbs_id,'admin_b_name',true);
if($admin_a_name==''){$admin_a_name=__('大版主','jinsom');}
if($admin_b_name==''){$admin_b_name=__('小版主','jinsom');}

$apply=$instance['apply_on_off'];
$apply_title=$instance['apply_title'];
$apply_color=$instance['apply_color'];
$style=$instance['style'];

echo '
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title" style="text-align: center;">
<li class="layui-this">'.$admin_a_name.'</li>
<li>'.$admin_b_name.'</li>
</ul>
<div class="layui-tab-content">';
echo '<div class="layui-tab-item layui-show">';
echo '<div class="jinsom-sidebar-user-list bbs-admin-list '.$style.'">';


if($admin_a){
foreach($admin_a_arr as $user_id){
$user_info = get_userdata($user_id);
$desc=$user_info->description;
echo '
<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'">'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'40',avatar_type($user_id)).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.$desc.'</div>
</div>
<div class="follow">
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';	
}
}else{
echo jinsom_empty();
}

echo '</div></div>';

echo '<div class="layui-tab-item">';
echo '<div class="jinsom-sidebar-user-list bbs-admin-list '.$style.'">';
if($admin_b){
foreach($admin_b_arr as $user_id){
$user_info = get_userdata($user_id);
$desc=$user_info->description;
if(empty($desc)){$desc=$default_desc;}
echo '
<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'">'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'40',avatar_type($user_id)).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.$desc.'</div>
</div>
<div class="follow">
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';	
}
}else{
echo jinsom_empty();
}

echo '</div></div>';

if($apply&&is_user_logged_in()){
echo '<div class="apply opacity" onclick="jinsom_apply_bbs_admin_form('.$bbs_id.')" style="background-color:'.$apply_color.'">'.$apply_title.'</div>';
}

echo '</div>';//layui-tab-content
echo '</div>';//layui-tab
}else{
echo __('此小工具必须只能放在论坛页面或者帖子页面','jinsom');
}
echo $args['after_widget'];
}
}
