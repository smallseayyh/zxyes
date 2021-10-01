<?php 
//数据类型筛选
if($type=='all'){
$args = array(
'post__not_in'=>$sticky_data,
'category__not_in'=>$jinsom_bbs_hide_arr,
'tag__not_in'=>explode(",",jinsom_get_option('jinsom_topic_sns_hide'))//不对外显示的话题
);
}else if($type=='commend'){//推荐、加精
$args = array(
'meta_key' => 'jinsom_commend',
'post__not_in'=>$sticky_data,
'category__not_in'=>$jinsom_bbs_hide_arr
);
}else if($type=='words'||$type=='music'||$type=='video'||$type=='single'||$type=='reprint'||$type=='redbag'){
$args = array(
'meta_key' => 'post_type',
'meta_value'=>$type,
'post_parent'=>0,
'post__not_in'=>$sticky_data,
);
}else if($type=='pay'){//付费可见===动态+帖子
$args = array(
'meta_key' => 'post_price',
'post__not_in'=>$sticky_data,
'category__not_in'=>$jinsom_bbs_hide_arr
);	
}else if($type=='vip'){//VIP可见===动态+帖子
$args = array(
'meta_query' => array(
'relation' => 'OR',
array(
'key' => 'post_power',
'value' => 4,
),
array(
'key' => 'post_type',
'value' => 'vip_see',
)
),
'post__not_in'=>$sticky_data,
'category__not_in'=>$jinsom_bbs_hide_arr
);	
}else if($type=='login'){//登录可见===动态+帖子
$args = array(
'meta_query' => array(
'relation' => 'OR',
array(
'key' => 'post_power',
'value' => 5,
),
array(
'key' => 'post_type',
'value' => 'login_see',
)
),
'post__not_in'=>$sticky_data,
'category__not_in'=>$jinsom_bbs_hide_arr
);	
}else if($type=='password'){//密码可见===只有动态
$args = array(
'meta_key' => 'post_power',
'meta_value'=>2,
'post__not_in'=>$sticky_data,
);	
}else if($type=='bbs'){//帖子
$args = array(
'post_parent'=>999999999,
'post__not_in'=>$sticky_data,
'category__not_in'=>$jinsom_bbs_hide_arr
);	
}else if($type=='answer'){//问答帖子
$args = array(
'post_parent'=>999999999,
'meta_key'=>'answer_number',
'post__not_in'=>$sticky_data,
'category__not_in'=>$jinsom_bbs_hide_arr
);	
}else if($type=='follow-user'){
$jinsom_follow_user_data=jinsom_follow_user_data($user_id);
$follow_data=implode(",",$jinsom_follow_user_data);//获取我所有关注用户的id


//显示已经关注用户的头像
if($jinsom_follow_user_data&&$load_type!='more'){
echo '<div class="jinsom-post-follow-user-list clear">';
$ii=1;
foreach ($jinsom_follow_user_data as $data) {
if(wp_is_mobile()){
$user_link=jinsom_mobile_author_url($data);
$more_link=get_template_directory_uri().'/mobile/templates/page/follower.php?type=following';
$show_number=20;
}else{
$user_link=jinsom_userlink($data);
$more_link=jinsom_userlink($user_id);
$show_number=9;
}
if($ii<=$show_number){
echo '<li><a href="'.$user_link.'" class="link" target="_blank"><div class="avatarimg">'.jinsom_avatar($data,30,avatar_type($data)).jinsom_verify($data).'</div><p>'.jinsom_nickname($data).'</p></a></li>';
}
$ii++;
}
if(count($jinsom_follow_user_data)>20){
echo '<li class="more"><a href="'.$more_link.'" class="link" target="_blank"><i class="jinsom-icon jinsom-gengduo2"></i><p>'.__('更多关注','jinsom').'</p></a></li>';
}
echo '</div>';
}

if(empty($follow_data)){$follow_data=9999999990;}
$args = array(
'author'=>$follow_data,
'post__not_in'=>$sticky_data,
'category__not_in'=>$jinsom_bbs_hide_arr
);

}else if($type=='follow-bbs'){//我关注的论坛的内容
$my_follow_bbs_id_arr=jinsom_get_user_follow_bbs_id($user_id);
if(!$my_follow_bbs_id_arr){
$my_follow_bbs_id_arr=array(9999999);
}
$args = array(
'post_parent'=>999999999,
'post__not_in'=>$sticky_data,
'cat'=>$my_follow_bbs_id_arr
);	
}else if($type=='follow-topic'){//我关注的话题的内容
$my_follow_topic_id_arr=jinsom_get_user_follow_topic_id($user_id);
if(!$my_follow_topic_id_arr){
$my_follow_topic_id_arr=array(9999999);
}
$args = array(
'post__not_in'=>$sticky_data,
'tag__in'=>$my_follow_topic_id_arr
);	
}else if($type=='hot'||$type=='rand'){//热门内容||随机内容
if(isset($_POST['data'])){
$hot_time=strip_tags($_POST['data']);
}else{
$hot_time=$jinsom_sns_home_menu_add[0]['time'];
}
if($hot_time!='all'){
$before=date('Y-m-d',time()+3600*24);
if($hot_time=='week'){
$after=date('Y-m-d',time()-3600*24*7);
}else if($hot_time=='half_month'){
$after=date('Y-m-d',time()-3600*24*15);	
}else if($hot_time=='month'){
$after=date('Y-m-d',time()-3600*24*30);	
}else if($hot_time=='year'){
$after=date('Y-m-d',time()-3600*24*365);	
}
$date_query=array(
array(
'column' => 'post_date',
'before' =>$before,
'after' =>$after
)
);
}else{
$date_query=array();
}
$args = array(
'date_query' => $date_query,
'post__not_in'=>$sticky_data,
'category__not_in'=>$jinsom_bbs_hide_arr,
);	
}else if($type=='city'){//同城
$city=get_user_meta($user_id,'city',true);
if($city){
$args = array(
'meta_key' => 'city',
'meta_value' => $city,
'meta_compare' => 'LIKE',
'post__not_in'=>$sticky_data,
'category__not_in'=>$jinsom_bbs_hide_arr
);	
}else{
$args = array(
'cat' =>999999999,
);		
}
}else if($type=='buy'){//购买
$buy_data=jinsom_buy_post_arr($author_id);
if($buy_data){
$args = array(
'post__in'=>$buy_data,
'post_status' => 'publish',
'post__not_in'=>$sticky_data,//排除置顶
'category__not_in'=>$jinsom_bbs_hide_arr
);	
}else{
$args = array(
'cat' =>999999999,
);		
}
}else if($type=='like'){//喜欢
$like_data=jinsom_like_post_arr($author_id);
if($like_data){
$args = array(
'post__in'=>$like_data,
'post__not_in'=>$sticky_data,//排除置顶
'orderby' => 'post__in',
'category__not_in'=>$jinsom_bbs_hide_arr
);
}else{
$args = array(
'cat' =>999999999,
);	
}
}else if($type=='custom-bbs'){//自定义论坛
if(isset($_POST['data'])){
$cat=explode(",",strip_tags($_POST['data']));
}else{
$cat=explode(",",strip_tags(jinsom_get_option('jinsom_sns_home_menu_add')[$index]['bbs_id']));
}
$args = array(
'post_parent'=>999999999,
'category__in' =>$cat,
'post__not_in'=>$sticky_data,
);	
}else if($type=='custom-topic'){//自定义话题
if(isset($_POST['data'])){
$tag__in=explode(",",strip_tags($_POST['data']));
}else{
$tag__in=$cat=explode(",",strip_tags(jinsom_get_option('jinsom_sns_home_menu_add')[$index]['topic_id']));
}
$args = array(
'tag__in' =>$tag__in,
'post__not_in'=>$sticky_data,
);	
}

$args['ignore_sticky_posts']=1;//忽略置顶
