<?php
/*
Template Name:小黑屋（监狱）
*/
$user_id =$current_user->ID;
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');	
}else{
get_header();
?>

<div class="jinsom-black-house-content">
<div class="jinsom-main-content">

<div class="jinsom-black-house-header-html">
<?php echo do_shortcode(jinsom_get_option('jinsom_blacklist_page_header_html'));?>
</div>

<div class="jinsom-black-house-box">
<div class="title"><?php _e('拘留所','jinsom');?><span><?php _e('拘留1-30天的黑名单用户','jinsom');?></span></div>
<div class="content clear">

<?php 
$black_query = new WP_User_Query( array ( //黑名单
'meta_query' => array(
array(
'key' => 'blacklist_time',
'value' => date("Y-m-d",strtotime("+30 day")),
'type' => 'DATE',
'compare' => '<='
),
array(
'key' => 'blacklist_time',
'value' => date("Y-m-d",time()),
'type' => 'DATE',
'compare' => '>'
),
),
'number' =>100,
'orderby' => 'meta_value',
));
if (!empty($black_query->results)){
foreach ($black_query->results as $user){
$blacklist_time=strtotime(get_user_meta($user->ID,'blacklist_time',true));
$day=ceil(($blacklist_time-time())/86400);
$reason=get_user_meta($user->ID,'blacklist_reason',true);
if($reason){$reason=__('原因','jinsom').'：'.$reason;}else{$reason='';}
if($user_id!=$user->ID){
$bail='<div class="btn opacity" onclick="jinsom_blacklist_bail_form('.$user->ID.')">保释</div>';
}else{
$bail='';
}
echo '
<li title="'.$reason.'">
<a href="'.jinsom_userlink($user->ID).'" target="_blank">'.jinsom_avatar($user->ID,'60',avatar_type($user->ID)).'</a>
<div class="name">'.jinsom_nickname_link($user->ID).'</div>
<div class="time yellow">'.sprintf(__( '%s天后释放','jinsom'),$day).'</div>
'.$bail.'
</li>';
}}else{
echo jinsom_empty(__('暂无黑名单用户','jinsom'));		
}
?>



</div>
</div>

<div class="jinsom-black-house-box">
<div class="title"><?php _e('监狱','jinsom');?><span><?php _e('关押2个月以上的黑名单用户','jinsom');?></span></div>
<div class="content clear">

<?php 
$black_query = new WP_User_Query( array ( //黑名单
'meta_query' => array(
array(
'key' => 'blacklist_time',
'value' => date("Y-m-d",strtotime("+30 day")),
'type' => 'DATE',
'compare' => '>'
),
),
'number' =>100,
'orderby' => 'meta_value',

));
if (!empty($black_query->results)){
foreach ($black_query->results as $user){
$blacklist_time=strtotime(get_user_meta($user->ID,'blacklist_time',true));
$day=ceil(($blacklist_time-time())/86400);
$reason=get_user_meta($user->ID,'blacklist_reason',true);
if($reason){$reason=__('原因','jinsom').'：'.$reason;}else{$reason='';}
echo '
<li title="'.$reason.'">
<a href="'.jinsom_userlink($user->ID).'" target="_blank">'.jinsom_avatar($user->ID,'60',avatar_type($user->ID)).'</a>
<div class="name">'.jinsom_nickname_link($user->ID).'</div>
<div class="time">'.sprintf(__( '%s天后释放','jinsom'),$day).'</div>
</li>';
}}else{
echo jinsom_empty(__('暂无黑名单用户','jinsom'));		
}
?>



</div>
</div>

<div class="jinsom-black-house-box">
<div class="title"><?php _e('死牢','jinsom');?><span><?php _e('被执行死刑的风险用户','jinsom');?></span></div>
<div class="content clear">

<?php 
$danger_query = new WP_User_Query( array ( //风险
'meta_key' => 'user_power',
'meta_value' =>4,
'number' =>100,
'count_total'=>false,
));
if (!empty($danger_query->results)){
foreach ($danger_query->results as $user){

echo '
<li>
<a href="'.jinsom_userlink($user->ID).'" target="_blank">'.jinsom_avatar($user->ID,'60',avatar_type($user->ID)).'</a>
<div class="name">'.jinsom_nickname_link($user->ID).'</div>
<div class="time black">'.__('死刑犯','jinsom').'</div>
</li>';
}}else{
echo jinsom_empty(__('暂无黑名单用户','jinsom'));	
}
?>
</div>
</div>

<div class="jinsom-black-house-footer-html">
<?php echo do_shortcode(jinsom_get_option('jinsom_blacklist_page_footer_html'));?>
</div>

</div>
</div>

<script type="text/javascript">
//保释黑名单用户表单
function jinsom_blacklist_bail_form(user_id) {
layer.load(1);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/stencil/blacklist-bail.php",
data:{user_id:user_id},
success: function(msg){
layer.closeAll('loading');
layer.open({
title:false,
btn: false,
area: ['auto'],
content: msg
})
}
}); 
}

//保释黑名单用户
function jinsom_blacklist_bail(author_id){
layer.load(1);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/action/blacklist-bail.php",
data:{author_id:author_id},
success: function(msg){
layer.closeAll('loading');
layer.msg(msg.msg);
if(msg.code==1){
function d(){window.location.reload();}setTimeout(d,1500);
}
}
}); 	
}
</script>

<?php get_footer();?>

<?php }?>