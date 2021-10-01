<?php 
//排行榜
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$number=(int)$_POST['number'];
$post_id=(int)$_POST['post_id'];
$page_leaderboard_data=get_post_meta($post_id,'page_leaderboard_data',true);
$jinsom_leaderboard_add=$page_leaderboard_data['jinsom_leaderboard_add'];
$type=$jinsom_leaderboard_add[$number]['type'];
$type_name=$jinsom_leaderboard_add[$number]['unit'];//单位
if($type!='follower'&&$type!='post_count'){
$user_query_a = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'meta_key' => $type,
'count_total'=>false,
'number' =>4
));
$user_query_b = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'meta_key' => $type,
'count_total'=>false,
'offset' => 4,
'number' =>56
));
}else if($type=='follower'){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$user_query_a = $wpdb->get_results("SELECT count(`user_id`) as total,`user_id` FROM $table_name WHERE `follow_status` in(1,2) group by `user_id` order by count(*) desc limit 4;");
$user_query_b = $wpdb->get_results("SELECT count(`user_id`) as total,`user_id` FROM $table_name WHERE `follow_status` in(1,2) group by `user_id` order by count(*) desc limit 4,56;");
}else if($type=='post_count'){
$user_query_a = new WP_User_Query( array ( 
'orderby' => 'post_count',
'order' => 'DESC',
'count_total'=>false,
'number' =>4
));
$user_query_b = new WP_User_Query( array ( 
'orderby' => 'post_count',
'order' => 'DESC',
'offset' => 4,
'count_total'=>false,
'number' =>56
));
}
?>



<div class="jinsom-ranking-page-top">

<?php
if($type=='follower'){
$i=1;
foreach ($user_query_a as $user){
if($i==1){
$class='one';
$rank='<span class="rank">'.__('冠军','jinsom').'</span>';
}else if($i==2){
$class='two';
$rank='<span class="rank">'.__('亚军','jinsom').'</span>';	
}else if($i==3){
$class='three';
$rank='<span class="rank">'.__('季军','jinsom').'</span>';	
}else if($i==4){
$class='four';
$rank='<span class="rank">'.__('殿军','jinsom').'</span>';	
}


$bg=jinsom_member_bg($user->user_id,'small_img');

echo '
<li class="'.$class.'">
<a href="'.jinsom_userlink($user->user_id).'" target="_blank">
'.$rank.'
<div class="bg" style="background-image:url('.$bg.')"></div>
<div class="avatarimg">'.jinsom_vip_icon($user->user_id).jinsom_avatar($user->user_id,'40',avatar_type($user->user_id)).jinsom_verify($user->user_id).'</div>
<div class="name">'.jinsom_nickname($user->user_id).jinsom_honor($user->user_id).'</div>	
<div class="number">'.$user->total.' '.$type_name.'</div>
</a>	
<div class="btn">'.jinsom_follow_button_home($user->user_id).'</div>
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
$class='one';
$rank='<span class="rank">'.__('冠军','jinsom').'</span>';
}else if($i==2){
$class='two';
$rank='<span class="rank">'.__('亚军','jinsom').'</span>';	
}else if($i==3){
$class='three';
$rank='<span class="rank">'.__('季军','jinsom').'</span>';	
}else if($i==4){
$class='four';
$rank='<span class="rank">'.__('殿军','jinsom').'</span>';	
}

$data_arr=explode(",",$data);
$leaderboard_user_id=$data_arr[0];
$type_name=$data_arr[1];
$bg=jinsom_member_bg($leaderboard_user_id,'small_img');
echo '
<li class="'.$class.'">
<a href="'.jinsom_userlink($leaderboard_user_id).'" target="_blank">
'.$rank.'
<div class="bg" style="background-image:url('.$bg.')"></div>
<div class="avatarimg">'.jinsom_vip_icon($leaderboard_user_id).jinsom_avatar($leaderboard_user_id,'40',avatar_type($leaderboard_user_id)).jinsom_verify($leaderboard_user_id).'</div>
<div class="name">'.jinsom_nickname($leaderboard_user_id).jinsom_honor($leaderboard_user_id).'</div>	
<div class="number">'.$type_name.'</div>
</a>	
<div class="btn">'.jinsom_follow_button_home($leaderboard_user_id).'</div>
</li>
';
$i++;
}

}else{
if (!empty($user_query_a->results)){
$i=1;
foreach ($user_query_a->results as $user){

if($type=='post_count'){
$num=count_user_posts($user->ID);
}else{
$num=(int)get_user_meta($user->ID,$type,true);
}

if($i==1){
$class='one';
$rank='<span class="rank">'.__('冠军','jinsom').'</span>';
}else if($i==2){
$class='two';
$rank='<span class="rank">'.__('亚军','jinsom').'</span>';	
}else if($i==3){
$class='three';
$rank='<span class="rank">'.__('季军','jinsom').'</span>';	
}else if($i==4){
$class='four';
$rank='<span class="rank">'.__('殿军','jinsom').'</span>';	
}

$bg=jinsom_member_bg($user->ID,'small_img');

echo '
<li class="'.$class.'">
<a href="'.jinsom_userlink($user->ID).'" target="_blank">
'.$rank.'
<div class="bg" style="background-image:url('.$bg.')"></div>
<div class="avatarimg">'.jinsom_vip_icon($user->ID).jinsom_avatar($user->ID,'40',avatar_type($user->ID)).jinsom_verify($user->ID).'</div>
<div class="name">'.jinsom_nickname($user->ID).jinsom_honor($user->ID).'</div>	
<div class="number">'.$num.' '.$type_name.'</div>
</a>	
<div class="btn">'.jinsom_follow_button_home($user->ID).'</div>
</li>
';
$i++;
}
}
}//判断是否是粉丝排行榜
?>

</div>
<div class="jinsom-ranking-page-bottom clear">

<?php
if($type=='follower'){//粉丝排行榜
$i=1;
foreach ($user_query_b as $user){
$rank=$i+4;
echo '
<li>
<a href="'.jinsom_userlink($user->user_id).'" target="_blank">
<div class="rank">'.$rank.'</div>
<div class="avatarimg">'.jinsom_vip_icon($user->user_id).jinsom_avatar($user->user_id,'40',avatar_type($user->user_id)).jinsom_verify($user->user_id).'</div>
<div class="info">
<div class="name">'.jinsom_nickname($user->user_id).'</div>	
<div class="number">'.$user->total.' '.$type_name.'</div>
</div>
</a>
</li>
';	
$i++;	
}

}else if($type=='custom'){
$custom_data=$jinsom_leaderboard_add[$number]['custom'];
$custom_data_arr=explode("||",$custom_data);
unset($custom_data_arr[0],$custom_data_arr[1],$custom_data_arr[2],$custom_data_arr[3]);

$i=1;
foreach ($custom_data_arr as $data){
$data_arr=explode(",",$data);
$leaderboard_user_id=$data_arr[0];
$type_name=$data_arr[1];
$rank=$i+4;
echo '
<li id="'.$data.'">
<a href="'.jinsom_userlink($leaderboard_user_id).'" target="_blank">
<div class="rank">'.$rank.'</div>
<div class="avatarimg">'.jinsom_vip_icon($leaderboard_user_id).jinsom_avatar($leaderboard_user_id,'40',avatar_type($leaderboard_user_id)).jinsom_verify($leaderboard_user_id).'</div>
<div class="info">
<div class="name">'.jinsom_nickname($leaderboard_user_id).'</div>	
<div class="number">'.$type_name.'</div>
</div>
</a>
</li>
';
$i++;
}

}else{
if (!empty($user_query_b->results)){
$i=1;
foreach ($user_query_b->results as $user){

if($type=='post_count'){
$num=count_user_posts($user->ID);
}else{
$num=(int)get_user_meta($user->ID,$type,true);
}

$rank=$i+4;
echo '
<li>
<a href="'.jinsom_userlink($user->ID).'" target="_blank">
<div class="rank">'.$rank.'</div>
<div class="avatarimg">'.jinsom_vip_icon($user->ID).jinsom_avatar($user->ID,'40',avatar_type($user->ID)).jinsom_verify($user->ID).'</div>
<div class="info">
<div class="name">'.jinsom_nickname($user->ID).'</div>	
<div class="number">'.$num.' '.$type_name.'</div>
</div>
</a>
</li>
';
$i++;
}
}
}//判断是否是粉丝排行榜
?>

</div>	