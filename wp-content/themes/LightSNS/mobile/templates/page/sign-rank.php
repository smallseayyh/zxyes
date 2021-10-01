<?php 
//签到排行
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;

global $wpdb;
$table_name=$wpdb->prefix.'jin_sign';
$today=date('Y-m-d',time());
$new_data=$wpdb->get_results("SELECT * FROM $table_name WHERE date='$today' ORDER BY strtotime;");

$user_query_b = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'meta_key' => 'sign_c',
'count_total'=>false,
'number'=>50
));	

?>

<div data-page="sign-rank" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('签到排行榜','jinsom');?></div>
<div class="right"><a href="#" class="link icon-only"></a></div>
<div class="subnavbar">
<div class="jinsom-chat-tab">
<a href="#jinsom-today-sign-rank" class="link tab-link jinsom-tab-button active"><?php _e('今日签到','jinsom');?> (<?php echo count($new_data);?><?php _e('人','jinsom');?>)</a>
<a href="#jinsom-continuous-sign-rank" class="link tab-link jinsom-tab-button"><?php _e('累计签到','jinsom');?></a>
</div>
</div>
</div>
</div>

<div class="page-content jinsom-sign-rank-content">

<div class="jinsom-sign-rank tabs">
<div id="jinsom-today-sign-rank" class="tab active">
<?php 
if($new_data){
$i=1;
foreach($new_data as $data){
if($i==1){
$one='one';
$one_sign='<span>'.__('首签','jinsom').'</span>';
}else{
$one='other';
$one_sign='';
}
$sign_user_id=$data->user_id;

$sign_time=__('今天','jinsom').date('H:i',$data->strtotime);
$sign_c=get_user_meta($sign_user_id,'sign_c',true);


echo '
<li class="clear '.$one.'">
'.$one_sign.'
<a href="'.jinsom_mobile_author_url($sign_user_id).'" class="link">
<div class="avatarimg">
'.jinsom_avatar($sign_user_id,'40',avatar_type($sign_user_id)).jinsom_verify($sign_user_id).'
</div>
<div class="info">
<div class="name">'.jinsom_nickname($sign_user_id).jinsom_vip($sign_user_id).jinsom_honor($sign_user_id).'</div>
<div class="desc">'.$sign_time.'</div>
</div>
<div class="number">'.__('累计','jinsom').'<i>'.$sign_c.'</i>天</div>
</a>
</li>';
$i++;
}
}else{
echo jinsom_empty();
}
?>
</div>
<div id="jinsom-continuous-sign-rank" class="tab">
<?php 
if (!empty($user_query_b->results)){
$i=1;
foreach ($user_query_b->results as $user){
if($i==1){$one='one';}else{$one='other';}
$sign_date_meta=get_user_meta($user->ID,'daily_sign',true);
$sign_time=__('今天','jinsom').date('H:i',strtotime($sign_date_meta));
$sign_c=get_user_meta($user->ID,'sign_c',true);
$user_info = get_userdata($user->ID);
$desc=$user_info->description;
echo '
<li class="clear '.$one.'">
<a href="'.jinsom_mobile_author_url($user->ID).'" class="link">
<div class="avatarimg">
'.jinsom_avatar($user->ID,'40',avatar_type($user->ID)).jinsom_verify($user->ID).'
</div>
<div class="info">
<div class="name">'.jinsom_nickname($user->ID).jinsom_vip($user->ID).jinsom_honor($user->ID).'</div>
<div class="desc">'.$desc.'</div>
</div>
<div class="number">'.__('累计','jinsom').'<i>'.$sign_c.'</i>天</div>
</a>
</li>';
$i++;
}
}else{
echo jinsom_empty();
}
?>
</div>
</div>

</div>
</div>        