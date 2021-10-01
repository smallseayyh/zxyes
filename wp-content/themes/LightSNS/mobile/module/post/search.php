<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;

if(jinsom_get_option('jinsom_search_login_on_off')&&!is_user_logged_in()){
echo jinsom_empty(__('请登录之后再进行搜索！','jinsom'));
exit();
}

//搜索
$keyword=htmlspecialchars(strip_tags($_POST['keyword']));
if(empty($keyword)){
echo jinsom_empty();
exit();
}




if(isset($_POST['type'])){
$number = get_option('posts_per_page', 10);
$type=strip_tags($_POST['type']);
$page=(int)$_POST['page'];
$offset=($page-1)*$number;


//记录搜索词
if($keyword&&$page==1){
if(!current_user_can('level_10')){
if($type=='topic'||$type=='all'||$type=='forum'){
global $wpdb;
$table_name=$wpdb->prefix.'jin_search_note';
$ip = $_SERVER['REMOTE_ADDR'];
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (content,user_id,type,ip,search_time) VALUES ('$keyword','$user_id','手机端','$ip','$time')");
}
}

//历史搜索
if(isset($_COOKIE['history-search'])){
$history_search=$_COOKIE['history-search'];
$history_search_arr=explode(",",$history_search);

if(in_array($keyword,$history_search_arr)){
$key=array_search($keyword,$history_search_arr);
array_splice($history_search_arr,$key,1);
}else{
if(count($history_search_arr)>10){
array_pop($history_search_arr);
}
}
array_push($history_search_arr,$keyword);
setcookie("history-search",implode(",",$history_search_arr),time()+3600*24*30*12,'/');	
}else{
setcookie("history-search",$keyword,time()+3600*24*30*12,'/');	
}
}


if($type=='user'){//搜索用户
$user_query = new WP_User_Query( array ( 
'meta_key' => 'nickname',
'meta_value' => $keyword,//搜昵称
'meta_compare' =>'LIKE',
'orderby' => 'ID',
'order' =>'ASC',
'count_total'=>false,
'number'=> 80
));
if (!empty($user_query->results)){
echo '<div class="jinsom-search-user-list clear">';
foreach ($user_query->results as $user){
echo '
<li>
<a href="'.jinsom_mobile_author_url($user->ID).'" class="link">
<div class="avatarimg">
'.jinsom_avatar($user->ID,'60', avatar_type($user->ID)).jinsom_verify($user->ID).'
</div>
<div class="info">
<div class="name">'.jinsom_nickname($user->ID).'</div>
<div class="desc"><span>'.__('关注','jinsom').':<m>'.jinsom_following_count($user->ID).'</m></span><span>'.__('粉丝','jinsom').':<m>'.jinsom_follower_count($user->ID).'</m></span></div>
</div>
'.jinsom_mobile_follower_list_button($user_id,$user->ID).'
</a>
</li>';
}
echo '</div>';
}else{
echo jinsom_empty();	
}
}else if($type=='forum'){//搜索论坛
$args=array(
'number'=>30,
'taxonomy'=>'category',//论坛
'search'=>$keyword,
'hide_empty'=>false,
'exclude' =>jinsom_get_option('jinsom_search_bbs_hide'),
);
$bbs_arr=get_terms($args);
if($bbs_arr){	
echo '<div class="jinsom-search-user-list clear" style="margin-bottom:3vw;background-color:#fff;padding-top:4vw;">';
foreach ($bbs_arr as $bbs){
$bbs_id=$bbs->term_id;
echo '
<li>
<a href="'.jinsom_mobile_bbs_url($bbs_id).'" class="link">
<div class="avatarimg">
'.jinsom_get_bbs_avatar($bbs_id,0).'
</div>
<div class="info">
<div class="name">'.$bbs->name.'</div>
<div class="desc"><span>'.__('关注','jinsom').':<m>'.jinsom_get_bbs_like_number($bbs_id).'</m></span><span>'.__('内容','jinsom').':<m>'.jinsom_get_bbs_post($bbs_id).'</m></span></div>
</div>
</a>
</li>';
}
echo '</div>';
}else{
echo jinsom_empty();	
}

}else if($type=='topic'){//搜索话题
$args=array(
'number'=>30,
'taxonomy'=>'post_tag',//话题
'search'=>$keyword,
'hide_empty'=>false,
'orderby' =>'count',
'order' =>'DESC'
);
$topic_arr=get_terms($args);
if($topic_arr){	

echo '<div class="jinsom-search-user-list topic clear">';
foreach ($topic_arr as $topic){
$topic_id=$topic->term_id;
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
$topic_data=get_term_by('id',$topic_id,'post_tag');
echo '
<li>
<a href="'.jinsom_mobile_topic_id_url($topic_id).'" class="link">
<div class="avatarimg">
'.jinsom_get_bbs_avatar($topic_id,1).'
</div>
<div class="info">
<div class="name">'.$topic->name.'</div>
<div class="desc"><span>'.__('关注','jinsom').':<m>'.jinsom_topic_like_number($topic_id).'</m></span><span>'.__('内容','jinsom').':<m>'.$topic_data->count.'</m></span><span>'.__('浏览','jinsom').':<m>'.jinsom_views_show($topic_views).'</m></span></div>
</div>
</a>
</li>';
}
echo '</div>';
}else{
echo jinsom_empty();	
}

}else{


if($type=='all'){


if($_POST['page']==1){

$user_query = new WP_User_Query( array ( 
'meta_key' => 'nickname',
'meta_value' => $keyword,//搜昵称
'meta_compare' =>'LIKE',
'orderby' => 'ID',
'order' =>'ASC',
'count_total'=>false,
'number'=> 5
));
if (!empty($user_query->results)){
echo '<div class="jinsom-search-user-list clear"><h1>'.__('相关用户','jinsom').'</h1>';
foreach ($user_query->results as $user){
echo '
<li>
<a href="'.jinsom_mobile_author_url($user->ID).'" class="link">
<div class="avatarimg">
'.jinsom_avatar($user->ID,'60', avatar_type($user->ID)).jinsom_verify($user->ID).'
</div>
<div class="info">
<div class="name">'.jinsom_nickname($user->ID).'</div>
<div class="desc"><span>'.__('关注','jinsom').':<m>'.jinsom_following_count($user->ID).'</m></span><span>'.__('粉丝','jinsom').':<m>'.jinsom_follower_count($user->ID).'</m></span></div>
</div>
'.jinsom_mobile_follower_list_button($user_id,$user->ID).'
</a>
</li>';
}
echo '</div>';
}



$args=array(
'number'=>5,
'taxonomy'=>'category',//论坛
'search'=>$keyword,
'hide_empty'=>false,
'exclude' =>jinsom_get_option('jinsom_search_bbs_hide'),
);
$bbs_arr=get_terms($args);
if($bbs_arr){	
echo '<div class="jinsom-search-user-list clear"><h1>'.jinsom_get_option('jinsom_bbs_name').'</h1>';
foreach ($bbs_arr as $bbs){
$bbs_id=$bbs->term_id;
echo '
<li>
<a href="'.jinsom_mobile_bbs_url($bbs_id).'" class="link">
<div class="avatarimg">
'.jinsom_get_bbs_avatar($bbs_id,0).'
</div>
<div class="info">
<div class="name">'.$bbs->name.'</div>
<div class="desc"><span>'.__('关注','jinsom').':<m>'.jinsom_get_bbs_like_number($bbs_id).'</m></span><span>'.__('内容','jinsom').':<m>'.jinsom_get_bbs_post($bbs_id).'</m></span></div>
</div>
</a>
</li>';
}
echo '</div>';
}


$args=array(
'number'=>5,
'taxonomy'=>'post_tag',//话题
'search'=>$keyword,
'hide_empty'=>false,
'orderby' =>'count',
'order' =>'DESC'
);
$topic_arr=get_terms($args);
if($topic_arr){	

echo '<div class="jinsom-search-user-list clear"><h1>'.jinsom_get_option('jinsom_topic_name').'</h1>';
foreach ($topic_arr as $topic){
$topic_id=$topic->term_id;
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
$topic_data=get_term_by('id',$topic_id,'post_tag');
echo '
<li>
<a href="'.jinsom_mobile_topic_id_url($topic_id).'" class="link">
<div class="avatarimg">
'.jinsom_get_bbs_avatar($topic_id,1).'
</div>
<div class="info">
<div class="name">'.$topic->name.'</div>
<div class="desc"><span>'.__('关注','jinsom').':<m>'.jinsom_topic_like_number($topic_id).'</m></span><span>'.__('内容','jinsom').':<m>'.$topic_data->count.'</m></span><span>'.__('浏览','jinsom').':<m>'.jinsom_views_show($topic_views).'</m></span></div>
</div>
</a>
</li>';
}
echo '</div>';
}



}


$args = array(
'post_status' =>'publish',
'post_type' => 'post',
'showposts' => $number,
'offset' => $offset,
's' => $keyword,
'category__not_in'=>jinsom_get_option('jinsom_search_bbs_hide')
);



}else if($type=='bbs'){
$args = array(
'post_status' =>'publish',
'showposts' => $number,
'offset' => $offset,
'post_parent'=>999999999,
's' => $keyword,
'category__not_in'=>jinsom_get_option('jinsom_search_bbs_hide')
);
}else{
$args = array(
'post_status' =>'publish',
'meta_key' => 'post_type',
'meta_value'=>$type,
'showposts' => $number,
'offset' => $offset,
'post_parent'=>0,
's' => $keyword,
);	
}

$args['no_found_rows']=true;
query_posts($args);
if (have_posts()) {
while ( have_posts() ) : the_post();
require(get_template_directory().'/mobile/templates/post/post-list.php' );	
endwhile;
}else{

if($_POST['page']==1){
echo jinsom_empty();	
}else{
echo 0;
}

}


}

}