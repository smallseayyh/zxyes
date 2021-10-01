<?php 
if($type=='follower'){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$user_query = $wpdb->get_results("SELECT count(`user_id`) as total,`user_id` FROM $table_name WHERE `follow_status` in(1,2) group by `user_id` order by count(*) desc limit 60;");	

$i=1;
foreach ($user_query as $user){
if($i==1){
$rank='<i class="jinsom-icon jinsom-diyiming"></i>';
}else if($i==2){
$rank='<i class="jinsom-icon jinsom-dierming"></i>';	
}else if($i==3){
$rank='<i class="jinsom-icon jinsom-disanming"></i>';	
}else{
$rank=$i;
}
echo '
<li>
<a href="'.jinsom_mobile_author_url($user->user_id).'" class="link">
<div class="rank">'.$rank.'</div>
<div class="avatarimg">'.jinsom_vip_icon($user->user_id).jinsom_avatar($user->user_id,'40',avatar_type($user->user_id)).jinsom_verify($user->user_id).'</div>
<div class="info">
<div class="name">'.jinsom_nickname($user->user_id).jinsom_honor($user->user_id).'</div>	
<div class="desc">'.get_user_meta($user->user_id,'description',true).'</div>
</div>
<div class="number"><i>'.$user->total.'</i><p>'.$type_name.'</p></div>
</a>
</li>
';
$i++;
}



}else if($type=='custom'){
$custom_data=$jinsom_leaderboard_add[$number]['custom'];	
$custom_data_arr=explode("||",$custom_data);

$i=1;
foreach ($custom_data_arr as $data){
if($i>4){break;}
if($i==1){
$rank='<i class="jinsom-icon jinsom-diyiming"></i>';
}else if($i==2){
$rank='<i class="jinsom-icon jinsom-dierming"></i>';	
}else if($i==3){
$rank='<i class="jinsom-icon jinsom-disanming"></i>';	
}else{
$rank=$i;
}

$data_arr=explode(",",$data);
$leaderboard_user_id=$data_arr[0];
$type_name=$data_arr[1];
$bg=jinsom_member_bg($leaderboard_user_id,'small_img');
echo '
<li>
<a href="'.jinsom_mobile_author_url($leaderboard_user_id).'" class="link">
<div class="rank">'.$rank.'</div>
<div class="avatarimg">'.jinsom_vip_icon($leaderboard_user_id).jinsom_avatar($leaderboard_user_id,'40',avatar_type($leaderboard_user_id)).jinsom_verify($leaderboard_user_id).'</div>
<div class="info">
<div class="name">'.jinsom_nickname($leaderboard_user_id).jinsom_honor($leaderboard_user_id).'</div>	
<div class="desc">'.get_user_meta($leaderboard_user_id,'description',true).'</div>
</div>
<div class="number"><i>'.$type_name.'</i></div>
</a>
</li>
';
$i++;
}

}else{
$args = array( 
'order' => 'DESC',
'count_total'=>false,
'number' =>60
);	
if($type=='post_count'){
$args['orderby']='post_count';
}else{
$args['orderby']='meta_value_num';
$args['meta_key']=$type;	
}
$user_query = new WP_User_Query($args);


if (!empty($user_query->results)){
$i=1;
foreach ($user_query->results as $user){
if($i==1){
$rank='<i class="jinsom-icon jinsom-diyiming"></i>';
}else if($i==2){
$rank='<i class="jinsom-icon jinsom-dierming"></i>';	
}else if($i==3){
$rank='<i class="jinsom-icon jinsom-disanming"></i>';	
}else{
$rank=$i;
}

if($type=='post_count'){
$num=count_user_posts($user->ID);
}else{
$num=(int)get_user_meta($user->ID,$type,true);	
}

echo '
<li>
<a href="'.jinsom_mobile_author_url($user->ID).'" class="link">
<div class="rank">'.$rank.'</div>
<div class="avatarimg">'.jinsom_vip_icon($user->ID).jinsom_avatar($user->ID,'40',avatar_type($user->ID)).jinsom_verify($user->ID).'</div>
<div class="info">
<div class="name">'.jinsom_nickname($user->ID).jinsom_honor($user->ID).'</div>	
<div class="desc">'.get_user_meta($user->ID,'description',true).'</div>
</div>
<div class="number"><i>'.$num.'</i><p>'.$type_name.'</p></div>
</a>
</li>
';
$i++;
}
}

}