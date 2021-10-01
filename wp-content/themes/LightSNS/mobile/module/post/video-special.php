<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$page=(int)$_POST['page'];
$number=(int)$_POST['number'];
$type=$_POST['type'];
$topic=$_POST['topic'];
if(empty($page)){
$page=1;
}

$offset=($page-1)*$number;


$topic_arr=explode(",",$topic);
$args = array(
'post_status' => 'publish',
'meta_key' => 'post_type',
'meta_value'=>'video',
'offset' => $offset,
'showposts' => $number,
);
$args['ignore_sticky_posts']=1;
if($topic){
$args['tag__in']=$topic_arr;
}
$args['no_found_rows']=true;
query_posts($args);
if(have_posts()){
while (have_posts()) : the_post();



$post_id=get_the_ID();
$post_views=(int)get_post_meta($post_id,'post_views',true);

	
require(get_template_directory().'/mobile/templates/post/video.php');
endwhile;
}else{
if($type=='more'){
echo 0;
}else{
echo jinsom_empty();	
}
}


