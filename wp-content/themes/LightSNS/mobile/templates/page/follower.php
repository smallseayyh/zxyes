<?php 
//粉丝列表
require( '../../../../../../wp-load.php');
$author_id=$_GET['author_id'];
$type=$_GET['type'];

if($author_id){
$user_id=$author_id;

if($type=='following'){
$navbar_name=__('关注','jinsom').'('.jinsom_following_count($user_id).')';	
}else{
$navbar_name=__('粉丝','jinsom').'('.jinsom_follower_count($user_id).')';
}
}else{
$user_id=$current_user->ID;	
if($type=='following'){
$navbar_name=__('我的关注','jinsom').'('.jinsom_following_count($user_id).')';		
}else{
$navbar_name=__('我的粉丝','jinsom').'('.jinsom_follower_count($user_id).')';
}

}
?>
<div data-page="follower" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $navbar_name;?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-follower-content">
<div class="jinsom-chat-user-list follower list-block" page="2" user_id="<?php echo $user_id;?>">
<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';

if($type=='following'){//关注
$user_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  follow_user_id='$user_id' AND follow_status IN(1,2)  ORDER BY follow_time DESC limit 20;");
}else{//粉丝
$user_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  user_id='$user_id' AND follow_status IN(1,2)  ORDER BY follow_time DESC limit 20;");	
}


if($user_data){
foreach ($user_data as $data) {

if($type=='following'){
$follower_id=$data->user_id;
}else{
$follower_id=$data->follow_user_id;
}

$user_info = get_userdata($follower_id);
$desc=$user_info->description;
if(!$desc){$desc=jinsom_get_option('jinsom_user_default_desc_b');}
echo '
<li>
<div class="item-content">
<div class="item-media">
<a href="'.jinsom_mobile_author_url($follower_id).'" class="link">
'.jinsom_avatar($follower_id,'40',avatar_type($follower_id)).jinsom_verify($follower_id).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="'.jinsom_mobile_author_url($follower_id).'" class="link">
<div class="name">'.jinsom_nickname($follower_id).jinsom_vip($follower_id).'</div>
<div class="desc">'.$desc.'</div>
</a>
</div>
</div>
'.jinsom_mobile_follower_list_button($current_user->ID,$follower_id).'
</div>
</li>
';


}

}else{
echo jinsom_empty(__('没有任何用户','jinsom'));
}

?>
</div>

</div>
</div>        