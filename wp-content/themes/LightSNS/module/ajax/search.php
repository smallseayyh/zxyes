<?php 
require( '../../../../../wp-load.php' );
//搜索
$keyword=$_POST['keyword'];
if(empty($keyword)){
echo jinsom_empty();
exit();
}

$number = get_option('posts_per_page', 10);
if(isset($_POST['type'])){
$type=$_POST['type'];

if($type=='user'){//搜索用户
$user_query = new WP_User_Query( array ( 
'meta_key' => 'nickname',
'count_total'=>false,
'meta_value' => $keyword,//搜昵称
'meta_compare' =>'LIKE',
'orderby' => 'ID',
'order' =>'ASC',
'number'=> 51
));
if (!empty($user_query->results)){
echo '<div class="jinsom-search-user-list clear">';
foreach ($user_query->results as $user){
echo '<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user->ID).'" target="_blank">
'.jinsom_vip_icon($user->ID).jinsom_avatar($user->ID,'60', avatar_type($user->ID)).jinsom_verify($user->ID).'
</a>
</div>
<div class="info">
<p><a href="'.jinsom_userlink($user->ID).'" target="_blank">'.jinsom_nickname($user->ID).'</a></p>
<p><span>'.__('关注','jinsom').':<m>'.jinsom_following_count($user->ID).'</m></span><span>'.__('粉丝','jinsom').':<m>'.jinsom_follower_count($user->ID).'</m></span></p>
</div>
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

echo '<div class="jinsom-bbs-cat-list clear">';
foreach ($bbs_arr as $bbs){
$bbs_id=$bbs->term_id;
echo '
<li>
<div class="left">
<a href="'.get_category_link($bbs_id).'">
'.jinsom_get_bbs_avatar($bbs_id,0).'
</a>
</div>
<div class="right">
<div class="name"><a href="'.get_category_link($bbs_id).'">'.$bbs->name.'<span>'.jinsom_get_bbs_post($bbs_id).'</span></a></div>
<div class="desc">'.get_term_meta($bbs_id,'desc',true).'</div>
</div>
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

echo '<div class="jinsom-single-topic-list clear">';
foreach ($topic_arr as $topic){
$topic_id=$topic->term_id;
echo '<a href="'.get_tag_link($topic_id).'" title="'.$topic->name.'" class="opacity">'.jinsom_get_bbs_avatar($topic_id,1).'<span>'.$topic->name.'</span></a>';
}
echo '</div>';
}else{
echo jinsom_empty();	
}

}else{


if($type=='all'){
//相关用户
$user_query = new WP_User_Query( array ( 
'meta_key' => 'nickname',
'count_total'=>false,
'meta_value' => $keyword,//搜昵称
'meta_compare' =>'LIKE',
'orderby' => 'ID',
'order' =>'ASC',
'number'=> 12
));
if (!empty($user_query->results)){
echo '<div class="jinsom-search-user-list clear"><h1>'.__('用户','jinsom').'</h1>';
foreach ($user_query->results as $user){
echo '<li class="clear">
<div class="avatarimg">
<a href="'.jinsom_userlink($user->ID).'" target="_blank">
'.jinsom_vip_icon($user->ID).jinsom_avatar($user->ID,'60', avatar_type($user->ID) ).'
</a>
</div>
<div class="info">
<p><a href="'.jinsom_userlink($user->ID).'" target="_blank">'.jinsom_nickname($user->ID).'</a></p>
<p><span>'.__('关注','jinsom').':<m>'.jinsom_following_count($user->ID).'</m></span><span>'.__('粉丝','jinsom').':<m>'.jinsom_follower_count($user->ID).'</m></span></p>
</div>
</li>';
}
echo '</div>';
}


//相关论坛
$bbs_args=array(
'number'=>8,
'taxonomy'=>'category',//论坛
'search'=>$keyword,
'hide_empty'=>false,
'exclude' =>jinsom_get_option('jinsom_search_bbs_hide'),
);
$bbs_arr=get_terms($bbs_args);
if($bbs_arr){	

echo '<div class="jinsom-bbs-cat-list clear">';
echo '<h1>'.jinsom_get_option('jinsom_bbs_name').'</h1><div class="content">';
foreach ($bbs_arr as $bbs){
$bbs_id=$bbs->term_id;
echo '
<li>
<div class="left">
<a href="'.get_category_link($bbs_id).'">
'.jinsom_get_bbs_avatar($bbs_id,0).'
</a>
</div>
<div class="right">
<div class="name"><a href="'.get_category_link($bbs_id).'">'.$bbs->name.'<span>'.jinsom_get_bbs_post($bbs_id).'</span></a></div>
<div class="desc">'.get_term_meta($bbs_id,'desc',true).'</div>
</div>
</li>';
}
echo '</div></div>';
}

//相关话题
$topic_args=array(
'number'=>12,
'taxonomy'=>'post_tag',//话题
'search'=>$keyword,
'hide_empty'=>false,
'orderby' =>'count',
'order' =>'DESC'
);
$topic_arr=get_terms($topic_args);
if($topic_arr){	
echo '<div class="jinsom-single-topic-list clear">';
echo '<h1>'.jinsom_get_option('jinsom_topic_name').'</h1>';
foreach ($topic_arr as $topic){
$topic_id=$topic->term_id;
echo '<a href="'.get_tag_link($topic_id).'" title="'.$topic->name.'" class="opacity">'.jinsom_get_bbs_avatar($topic_id,1).'<span>'.$topic->name.'</span></a>';
}
echo '</div>';
}



$args = array(
'post_status' =>'publish',
'post_type' => 'post',
'showposts' => $number,
's' => $keyword,
'category__not_in'=>jinsom_get_option('jinsom_search_bbs_hide')
);
}else if($type=='bbs'){
$args = array(
'post_status' =>'publish',
'showposts' => $number,
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
'post_parent'=>0,
's' => $keyword,
);	
}


$args['no_found_rows']=true;
query_posts($args);
if (have_posts()) {
while ( have_posts() ) : the_post();
require(get_template_directory().'/post/post-list.php');	
endwhile;

echo '<div class="jinsom-more-posts" data="2" type="'.$type.'" onclick="jinsom_more_search(this);" b="'.$number.'">'.__('加载更多','jinsom').'</div>';	

}else{
echo jinsom_empty();	
}


}

}