<?php 
//礼物记录
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
global $wpdb;
$table_name = $wpdb->prefix . 'jin_gift';
?>
<div data-page="gift-note" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('礼物记录','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>

<div class="subnavbar">
<div class="jinsom-chat-tab">
<a href="#jinsom-gift-note-receive" class="link tab-link jinsom-tab-button active"><?php _e('我收到的','jinsom');?></a>
<a href="#jinsom-gift-note-send" class="link tab-link jinsom-tab-button"><?php _e('我送出的','jinsom');?></a>
</div>
</div>


</div>
</div>

<div class="page-content">
<div class="tabs jinsom-gift-note-form">
<div id="jinsom-gift-note-receive" class="tab active">
<?php 
$receive_data = $wpdb->get_results("SELECT * FROM $table_name WHERE `receive_user_id` ='$user_id' order by ID desc limit 30;");
if($receive_data){
foreach ($receive_data as $data) {
$send_user_id=$data->send_user_id;
echo '
<li>
<a href="'.jinsom_mobile_author_url($send_user_id).'" class="link">
<m>'.jinsom_nickname($send_user_id).'</m>'.__('给你赠送了','jinsom').'['.$data->name.']
<span>'.jinsom_timeago($data->time).'</span>
</a>
</li>
';
}

}else{
echo jinsom_empty();
}
?>

</div>
<div id="jinsom-gift-note-send" class="tab">
<?php 
$send_data = $wpdb->get_results("SELECT * FROM $table_name WHERE `send_user_id` ='$user_id' order by ID desc limit 30;");
if($send_data){
foreach ($send_data as $data) {
$send_user_id=$data->receive_user_id;
echo '
<li>
<a href="'.jinsom_mobile_author_url($send_user_id).'" class="link">
'.sprintf(__( '你给<m>%s</m>赠送了','jinsom'),jinsom_nickname($send_user_id)).'['.$data->name.']
<span>'.jinsom_timeago($data->time).'</span>
</a>
</li>
';
}

}else{
echo jinsom_empty();
}
?>
</div>	
</div>
</div>

</div>
</div>        