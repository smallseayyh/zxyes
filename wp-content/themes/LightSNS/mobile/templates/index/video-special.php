<div class="jinsom-video-special-list jinsom-post-list clear" number="<?php echo $video_data['jinsom_mobile_video_load_number'];?>">
<?php 
$user_id=$current_user->ID;
if($jinsom_mobile_video_special_add){
$topic_arr=explode(",",$jinsom_mobile_video_special_add[0]['topic']);
$args = array(
'post_status' => 'publish',
'meta_key' => 'post_type',
'meta_value'=>'video',
'showposts' => $video_data['jinsom_mobile_video_load_number'],
);
$args['ignore_sticky_posts']=1;
if($jinsom_mobile_video_special_add[0]['topic']){
$args['tag__in']=$topic_arr;
}
$args['no_found_rows']=true;
query_posts($args);
if(have_posts()){
while (have_posts()) : the_post();

$author_id=get_the_author_meta('ID');
$post_id=get_the_ID();
$post_type=get_post_meta($post_id,'post_type',true);
$post_power=get_post_meta($post_id,'post_power',true);//内容权限
$post_views=(int)get_post_meta($post_id,'post_views',true);
$is_bbs_post=is_bbs_post($post_id);
$load_type='list';
$require_url=get_template_directory();
	
require(get_template_directory().'/mobile/templates/post/video.php');
endwhile;
wp_reset_query();
}else{
echo jinsom_empty();	
}
}else{
echo jinsom_empty();
}
?>
</div>

