<?php 
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$jinsom_blacklist_bail_number=jinsom_get_option('jinsom_blacklist_bail_number');
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<div data-page="black-house" class="page no-tabbar toolbar-fixed">


<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('小黑屋','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-black-house-content">

<div class="jinsom-black-house-header-html">
<?php echo jinsom_get_option('jinsom_blacklist_page_header_html');?>
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
'number' =>50,
'orderby' => 'meta_value',
));
if (!empty($black_query->results)){
foreach ($black_query->results as $user){
$blacklist_time=strtotime(get_user_meta($user->ID,'blacklist_time',true));
$day=ceil(($blacklist_time-time())/86400);
$reason=get_user_meta($user->ID,'blacklist_reason',true);
if($reason){$reason=__('原因','jinsom').'：'.$reason;}else{$reason='';}
$bail_credit=$jinsom_blacklist_bail_number*$day;
if($user_id!=$user->ID){
$bail='<div class="btn opacity" onclick=\'jinsom_blacklist_bail('.$user->ID.',"'.$bail_credit.$credit_name.'",this)\'>'.__('保释','jinsom').'</div>';
}else{
$bail='';
}
echo '
<li title="'.$reason.'">
<a href="'.jinsom_mobile_author_url($user->ID).'" class="link">'.jinsom_avatar($user->ID,'60',avatar_type($user->ID)).'</a>
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
'number' =>50,
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
<a href="'.jinsom_mobile_author_url($user->ID).'" class="link">'.jinsom_avatar($user->ID,'60',avatar_type($user->ID)).'</a>
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
'number' =>50,
'count_total'=>false,
));
if (!empty($danger_query->results)){
foreach ($danger_query->results as $user){

echo '
<li>
<a href="'.jinsom_mobile_author_url($user->ID).'" class="link">'.jinsom_avatar($user->ID,'60',avatar_type($user->ID)).'</a>
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
<?php echo jinsom_get_option('jinsom_blacklist_page_footer_html');?>
</div>

</div>
</div>
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>