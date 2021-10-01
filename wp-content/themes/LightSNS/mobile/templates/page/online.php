<?php 
//当前活跃用户
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="online" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('活跃用户','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-online-content">

<?php 
$user_query = new WP_User_Query( array ( 
'orderby' => 'meta_value',
'order' => 'DESC',
'count_total'=>false,
'meta_key' => 'latest_login',
'number' => 50
));
if (!empty($user_query->results)){
echo '<div class="jinsom-chat-user-list visitor list-block">';
foreach ($user_query->results as $user){

echo '
<li>
<div class="item-content">
<div class="item-media">
<a href="'.jinsom_mobile_author_url($user->ID).'" class="link">
'.jinsom_avatar($user->ID,'40',avatar_type($user->ID)).jinsom_verify($user->ID).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="'.jinsom_mobile_author_url($user->ID).'" class="link">
<div class="name">'.jinsom_nickname($user->ID).jinsom_sex($user->ID).jinsom_vip($user->ID).'</div>
<div class="desc">'.jinsom_timeago(get_user_meta($user->ID,'latest_login',true)).'<v>（'.jinsom_get_online_type($user->ID).'）</v></div>
</a>
</div>
</div>
'.jinsom_mobile_follower_list_button($user_id,$user->ID).'
</div>
</li>
';


}
echo '</div>';
}else{
echo jinsom_empty();
}

?>
</div>

</div>
</div>        
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>