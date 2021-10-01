<?php 
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}//如果不是管理员 强制退出
$ip=$_GET['ip'];	
$user_query = new WP_User_Query( array ( 
'orderby' => 'ID',
// 'order' => 'DESC',
'meta_key' => 'latest_ip',
'meta_value' => $ip,
'count_total'=>false,
'number' => 100
));


?>
<div data-page="setting-ip" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('同IP用户','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-setting-content desc">

<div class="jinsom-chat-user-list list-block">
<?php 
if(!empty($user_query->results)){
foreach ($user_query->results as $user){
$user_id=$user->ID;
$user_info=get_userdata($user_id);
$desc=$user_info->description;
if(!$desc){$desc=jinsom_get_option('jinsom_user_default_desc_b');}
echo '
<li>
<div class="item-content">
<div class="item-media">
<a href="'.jinsom_mobile_author_url($user_id).'" class="link">
'.jinsom_avatar($user_id,'40',avatar_type($user_id)).jinsom_verify($user_id).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="'.jinsom_mobile_author_url($user_id).'" class="link">
<div class="name">'.jinsom_nickname($user_id).jinsom_vip($user_id).'</div>
<div class="desc">'.$desc.'</div>
</a>
</div>
</div>
</div>
</li>
';
}
}
?>
</div>
</div>       

