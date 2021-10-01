<?php 
//附近的人
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$city=get_user_meta($user_id,'city',true);
$user_ip=get_user_meta($user_id,'latest_ip',true);
$user_query = new WP_User_Query( array ( 
'order' => 'DESC',
'count_total'=>false,
'meta_query' => array( 
array(
'key' => 'city', 
'value' => $city, 
'compare' => 'LIKE' 
)
),
'number' => 30
));
?>
<div data-page="nearby-people" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('附近的人','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content">

<div class="jinsom-chat-user-list nearby-people list-block" page="2">
<?php 
if (!empty($user_query->results)){
foreach ($user_query->results as $user){
$user_info = get_userdata($user->ID);
$desc=$user_info->description;
if(!$desc){$desc=jinsom_get_option('jinsom_user_default_desc_b');}
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
<div class="name">'.jinsom_nickname($user->ID).jinsom_sex($user->ID).jinsom_vip($user->ID).jinsom_honor($user->ID).'</div>
<div class="desc">'.$desc.'</div>
</a>
</div>
</div>
'.jinsom_mobile_follower_list_button($user_id,$user->ID).'
</div>
</li>
';


}

}else{
echo '<div class="jinsom-empty-page"><i class="jinsom-icon jinsom-kong"></i><div class="title"><p>'.__('你附近太荒凉了！','jinsom').'</p></div></div>';
}

?>
</div>

</div>

</div>
</div>        