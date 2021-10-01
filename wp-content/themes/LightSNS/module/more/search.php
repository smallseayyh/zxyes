<?php 
require( '../../../../../wp-load.php' );
//搜索 加载更多
$keyword=$_POST['keyword'];
$page=$_POST['page'];
$number = get_option('posts_per_page', 10);
$offset = ($page-1)*$number;


//所有类型
if(isset($_POST['type'])){
$type=$_POST['type'];

if($type=='all'){
$args = array(
'post_status' =>'publish',
'post_type' => 'post',
'showposts' => $number,
'offset' => $offset ,
's' => $keyword,
);
}else if($type=='bbs'){
$args = array(
'post_status' =>'publish',
'showposts' => $number,
'offset' => $offset ,
'post_parent'=>999999999,
's' => $keyword,
);
}else{
$args = array(
'post_status' =>'publish',
'meta_key' => 'post_type',
'meta_value'=>$type,
'showposts' => $number,
'offset' => $offset ,
'post_parent'=>0,
's' => $keyword,
);	
}
$args['no_found_rows']=true;
query_posts($args);
if (have_posts()) {
while (have_posts()) : the_post();
require(get_template_directory().'/post/post-list.php');	
endwhile;	
}else{
echo 0;//没有更多文章	
}

}