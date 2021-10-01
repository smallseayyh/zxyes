<?php 
//收藏
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
// $jinsom_collect_menu = jinsom_get_option('jinsom_collect_menu');
// $enabled=$jinsom_collect_menu['enabled'];
$page=(int)$_POST['page'];
if(!$page){$page=1;}
$first_menu=strip_tags($_POST['type']);
if($type==''){$type='all';}
$number=get_option('posts_per_page',10);
$offset=($page-1)*$number;



if($first_menu=='all'){
$args = array(
'showposts' => $number,
'post_status' => 'publish',
);
}else if($first_menu=='words'||$first_menu=='music'||$first_menu=='video'||$first_menu=='single'||$first_menu=='redbag'){
$args = array(
'showposts' => $number,
'post_status' => 'publish',
'meta_key' => 'post_type',
'meta_value'=>$first_menu,
);
}else if($first_menu=='bbs'){
$args = array(
'showposts' => $number,
'post_parent'=>999999999,
'post_status' => 'publish',
);
}else if($first_menu=='goods'){
	
}
$jinsom_collect_post_arr=jinsom_collect_post_arr($user_id);
if(!$jinsom_collect_post_arr){
$args['cat']=array(999999);
}
$args['post__in']=$jinsom_collect_post_arr;
$args['ignore_sticky_posts']=1;
$args['no_found_rows']=true;
$args['orderby']='post__in';
$args['offset']=$offset;
query_posts($args);
if(have_posts()){
while(have_posts()) : the_post();
require(get_template_directory().'/mobile/templates/post/post-list.php' );	
endwhile;
}else{
if(isset($_POST['load_type'])){
echo 0;//没有更多内容了
}else{
echo jinsom_empty();	
}
}


