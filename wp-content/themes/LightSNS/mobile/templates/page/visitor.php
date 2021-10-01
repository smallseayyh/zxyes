<?php 
//我的列表
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
if(is_vip($user_id)){
$number=100;
}else{
$number=50;	
}
?>
<div data-page="visitor" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('我的访客','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-visitor-content">



<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'jin_visitor';
$user_data = $wpdb->get_results("SELECT * FROM $table_name WHERE author_id = $user_id ORDER BY visit_time DESC limit $number;");
if($user_data){
echo '<div class="jinsom-chat-user-list visitor list-block">';
foreach ($user_data as $data) {
$visitor_id=$data->user_id;
$visitor_time = jinsom_timeago($data->visit_time);
echo '
<li>
<div class="item-content">
<div class="item-media">
<a href="'.jinsom_mobile_author_url($visitor_id).'" class="link">
'.jinsom_avatar($visitor_id,'40',avatar_type($visitor_id)).jinsom_verify($visitor_id).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="'.jinsom_mobile_author_url($visitor_id).'" class="link">
<div class="name">'.jinsom_nickname($visitor_id).jinsom_vip($visitor_id).'</div>
<div class="desc">'.$visitor_time.'</div>
</a>
</div>
</div>
'.jinsom_mobile_follower_list_button($user_id,$visitor_id).'
</div>
</li>
';


}

if(!is_vip($user_id)){
echo '<div class="no-vip-tips">'.__('开通会员可以查看更多访客','jinsom').'</div>';
}

echo '</div>';
}else{
echo jinsom_empty();
}

//消除红点
$visitor=(int)get_user_meta($user_id,'visitor',true);
update_user_meta($user_id,'add_visit',$visitor);
?>
</div>

</div>
</div>        