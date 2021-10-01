<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_user_list', array(
'title'       => 'LightSNS_用户展示',
'classname'   => 'jinsom-widget-user-list',
'description' => '展示各类型的用户列表',
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '标题',
'default'   => '推荐用户',
),

array(
'id'                 => 'type',
'type'               => 'radio',
'title'              => '用户类型',
'options'            => array(
'a'              => '最新注册用户',
'b'              => '最新登录用户',
'c'         => '随机用户显示',
'd'         => 'VIP用户',
'e'         => '认证用户',
'f'         => '自定义用户',
),
'default'       =>'a',
),

array(
'id'         => 'user_ids',
'type'       => 'textarea',
'title'      => '自定义用户ID',
'desc'      =>'英文逗号隔开',
'placeholder' => '3,4,5,8,12',
'dependency' => array('type','==','f') ,
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
'title'   => '显示数量',
'dependency' => array('type','!=','f') ,
'default'   => 16,
),

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口打开',
), 


)
));


if(!function_exists('jinsom_widget_user_list')){
function jinsom_widget_user_list($args,$instance){
echo $args['before_widget'];
if (!empty($instance['title'])){
echo $args['before_title'] . apply_filters('widget_title',$instance['title']).$args['after_title'];
}
$number=$instance['number'];
$target=$instance['target'];
$type=$instance['type'];
$user_ids=$instance['user_ids'];
$default_desc = jinsom_get_option('jinsom_user_default_desc_b');

$style=$instance['style'];
if($style=='b'){
$style_class='jinsom-sidebar-user-list a';
}else if($style=='c'){
$style_class='jinsom-sidebar-user-list b';
}else{
$style_class='jinsom-sidebar-lattice-list';	
}

if($target){$target='target="_blank"';}else{$target='';}
echo '<div class="'.$style_class.' user clear">'; 

if($type=='a'){//最新注册
$user_query = new WP_User_Query( array ( 
'orderby' => 'registered', 
'order' => 'DESC',
'count_total'=>false,
'number' => $number
));

if (!empty($user_query->results)){
foreach ($user_query->results as $user){
$user_id=$user->ID;
if($style=='b'||$style=='c'){
echo '
<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >'.jinsom_vip_icon($user_id).jinsom_avatar($user_id , '40' , avatar_type($user_id) ).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.jinsom_timeago($user->user_registered).' 注册</div>
</div>
<div class="follow">
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';
}else{
echo '
<li title="注册时间：'.jinsom_timeago($user->user_registered).'">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >
'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'60', avatar_type($user_id)).
'<p>'.jinsom_nickname($user_id).'</p>
'.jinsom_verify($user_id).
'</a>
</li>';
}

}}


}else if($type=='b'){//最新登录
$user_query = new WP_User_Query( array ( 
'orderby' => 'meta_value',
'order' => 'DESC',
'meta_key' => 'latest_login',
'count_total'=>false,
'number' => $number
));
if (!empty($user_query->results)){
foreach ($user_query->results as $user){
$user_id=$user->ID;
$last_login=get_user_meta($user_id,'latest_login',true);
$online_type=jinsom_get_online_type($user_id);

if($style=='b'||$style=='c'){
echo '
<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'40',avatar_type($user_id)).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.jinsom_timeago($last_login).' ('.$online_type.')</div>
</div>
<div class="follow">
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';
}else{
echo '
<li title="最后活跃：'.jinsom_timeago($last_login).'('.$online_type.')">
<a href="'.jinsom_userlink($user->ID).'" '.$target.' >
'.jinsom_vip_icon($user->ID).jinsom_avatar($user->ID,'60', avatar_type($user->ID) ).
'<p>'.jinsom_nickname($user->ID).'</p>
'.jinsom_verify($user->ID).
'</a>
</li>';
}

}}


}else if($type=='c'){//随机用户
global $wpdb;
$usernames = $wpdb->get_results("SELECT user_login, ID FROM $wpdb->users ORDER BY RAND() LIMIT $number");
foreach ($usernames as $user){
$user_id=$user->ID;
if($style=='b'||$style=='c'){
$user_info = get_userdata($user_id);
$desc=$user_info->description;
if(empty($desc)){$desc=$default_desc;}
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
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';
}else{
echo '
<li title="'.$user->nickname.'">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >
'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'60', avatar_type($user_id) ).'
<p>'.jinsom_nickname($user_id).'</p>
'.jinsom_verify($user_id).'
</a>
</li>';
}

}

}else if($type=='d'){//VIP用户
$user_query = new WP_User_Query( array ( 
'orderby' => 'meta_value',
'order' => 'DESC',
'count_total'=>false,
'meta_query' => array( 
array(
'key' => 'vip_time', 
'value' => date('Y-m-d'), 
'type' => 'DATE',
'compare' => '>' 
)
),
'number' => $number
));
if (!empty($user_query->results)){
foreach ($user_query->results as $user){
$user_id=$user->ID;
$vip_time=get_user_meta($user_id,'vip_time',true);
if($style=='b'||$style=='c'){
$user_info = get_userdata($user_id);
$desc=$user_info->description;
if(empty($desc)){$desc=$default_desc;}
echo '
<li class="clear" title="VIP到期时间：'.$vip_time.'">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'40',avatar_type($user_id)).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.$desc.'</div>
</div>
<div class="follow">
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';
}else{
echo '
<li title="VIP到期时间：'.$vip_time.'">
<a href="'.jinsom_userlink($user_id).'" '.$target.'>
'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'60',avatar_type($user_id)).'
<p>'.jinsom_nickname($user_id).'</p>
'.jinsom_verify($user_id).'
</a>
</li>';
}

}}else{
echo jinsom_empty();	
}
}else if($type=='e'){//认证用户
$user_query = new WP_User_Query( array ( 
'order' => 'DESC',
'count_total'=>false,
'meta_query' => array( 
array(
'key' => 'verify', 
'value' => 0, 
'type' => 'NUMERIC',
'compare' => '!=' 
)
),
'number' => $number
));
if (!empty($user_query->results)){
foreach ($user_query->results as $user){
$user_id=$user->ID;
$verify_info=get_user_meta($user_id,'verify_info',true);
if($style=='b'||$style=='c'){
echo '
<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'40',avatar_type($user_id)).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.$verify_info.'</div>
</div>
<div class="follow">
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';
}else{
echo '
<li title="认证信息：'.$verify_info.'">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >
'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'60',avatar_type($user_id)).'<p>'.jinsom_nickname($user_id).'
</p>
'.jinsom_verify($user_id).'
</a>
</li>';
}

}}else{
echo jinsom_empty();	
}
}else if($type=='f'){
$user_id_arr=explode(",",$user_ids);
if($user_id_arr){
foreach ($user_id_arr as $user_id) {
if($style=='b'||$style=='c'){
$user_info = get_userdata($user_id);
$desc=$user_info->description;
if(empty($desc)){$desc=$default_desc;}
echo '
<li title="'.get_user_meta($user_id,'nickname',true).'" class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'40',avatar_type($user_id)).jinsom_verify($user_id).'</a>
</div>
<div class="info">
'.jinsom_nickname_link($user_id).'
<div class="time">'.$desc.'</div>
</div>
<div class="follow">
'.jinsom_follow_button_sidebar($user_id).'
</div>
</li>';
}else{
echo '
<li title="'.get_user_meta($user_id,'nickname',true).'">
<a href="'.jinsom_userlink($user_id).'" '.$target.' >
'.jinsom_vip_icon($user_id).jinsom_avatar($user_id,'60', avatar_type($user_id) ).'
<p>'.jinsom_nickname($user_id).'</p>
'.jinsom_verify($user_id).'
</a>
</li>';
}

}
}else{
echo jinsom_empty();	
}
}

echo '</div>';
echo $args['after_widget'];
}
}

