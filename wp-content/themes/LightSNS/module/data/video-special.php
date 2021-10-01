<?php 
//获取视频专题子菜单
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$page=1;
$type='click';
$topic=strip_tags($_POST['topic']);

$number=(int)$_POST['number'];
$offset=($page-1)*$number;


$topic_arr=explode(",",$topic);
$args = array(
'post_status' => 'publish',
'meta_key' => 'post_type',
'meta_value'=>'video',
'offset' => $offset,
'showposts' => $number,
);
if($topic){
$args['tag__in']=$topic_arr;
}
$args['no_found_rows']=true;
query_posts($args);
if(have_posts()){
while (have_posts()) : the_post();
$video_post_id=get_the_ID();
$video_author_id=get_the_author_meta('ID');
$video_title=get_the_title();
if($video_title==''){$video_title=get_the_content();}
$video_post_views=(int)get_post_meta($video_post_id,'post_views',true);
$video_time=get_post_meta($video_post_id,'video_time',true);
//if($video_time){
//$video_time='<span class="topic">'.jinsom_secToTime($video_time).'</span>';
//}else{
$video_time='';
//}
echo '
<li>
<a href="'.get_the_permalink().'" target="_blank">
<div class="img">
<div class="shadow"><i class="jinsom-icon jinsom-bofang2"></i></div>
<img src="'.jinsom_video_cover($video_post_id).'" alt="'.$video_title.'">
'.$video_time.'
</div>
<div class="title">'.$video_title.'</div>
</a>
<div class="bottom">
<a href="'.jinsom_userlink($video_author_id).'" target="_blank">
'.jinsom_avatar($video_author_id,'30',avatar_type($video_author_id)).'
<span class="name">'.get_user_meta($video_author_id,'nickname',true).'</span>
</a>
<span><i class="jinsom-icon jinsom-fire-fill"></i>'.jinsom_views_show($video_post_views).'</span>
</div>
</li>';
endwhile;
}else{
if($type=='more'){
echo 0;
}else{
echo jinsom_empty();	
}
}


