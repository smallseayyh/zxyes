<?php 
//加载内容数据
require( '../../../../../wp-load.php' );
$require_url=get_template_directory();
$user_id=$current_user->ID;
$jinsom_sns_home_load_type=jinsom_get_option('jinsom_sns_home_load_type');
$page=(int)$_POST['page'];//页数
$type=strip_tags($_POST['type']);//获取内容的类型
$load_type=strip_tags($_POST['load_type']);//加载类型
$index=(int)$_POST['index'];//点击的第几个
$author_id=(int)$_POST['author_id'];

//自定义html菜单
if($type=='custom-html'){
if($author_id){
$menu_add=jinsom_get_option('jinsom_member_menu_add');
}else{
$menu_add=jinsom_get_option('jinsom_sns_home_menu_add');
}
echo do_shortcode($menu_add[$index]['custom_html']);
exit();
}

//自定义引入文件
if($type=='require'){
if($author_id){
$menu_add=jinsom_get_option('jinsom_member_menu_add');
}else{
$menu_add=jinsom_get_option('jinsom_sns_home_menu_add');
}
require(do_shortcode($menu_add[$index]['require']));
exit();
}



$number=get_option('posts_per_page',10);
$offset=($page-1)*$number;
$jinsom_bbs_hide_arr=jinsom_get_option('jinsom_bbs_hide');//首页隐藏的论坛板块


if($author_id){//个人主页
$jinsom_bbs_hide_arr=array();
}


//SNS首页第一个菜单显示置顶||并且是点击菜单
if($index==0&&$load_type!='more'&&$author_id==0){
//显示置顶
$sticky_posts=get_option('sticky_posts');
if($sticky_posts){
$sticky_posts=array_reverse($sticky_posts);
$args_sticky=array(
'post__in'=>$sticky_posts,
'orderby' =>'post__in',
'no_found_rows'=>true
);
query_posts($args_sticky);
while(have_posts()):the_post();
$post_power=get_post_meta(get_the_ID(),'post_power',true);
if($post_power!=3){//置顶不显示私密类型
require($require_url.'/post/post-list.php');	
}
endwhile;wp_reset_query();
}

}


//个人主页置顶
$author_id=(int)$_POST['author_id'];
$sticky_posts=get_user_meta($author_id,'sticky',true);
if($index==0&&$load_type!='more'&&$author_id){
if($sticky_posts){
$args_sticky=array(
'post__in'=>array($sticky_posts),
'showposts' =>1,
'ignore_sticky_posts'=>1
);
query_posts($args_sticky);
while(have_posts()):the_post();
$post_power=get_post_meta(get_the_ID(),'post_power',true);
if($post_power!=3){//置顶不显示私密类型
require($require_url.'/post/post-list.php');	
}
endwhile;wp_reset_query();
}
}

$author_id=(int)$_POST['author_id'];
if($index==0&&!$author_id){
$sticky_data=get_option('sticky_posts');//置顶数据
}else if($index==0&&$author_id){
$sticky_data=array($sticky_posts);//置顶数据
}else{
$sticky_data=array();//置顶数据
}

require($require_url.'/post/post.php');//内容类型
$args['offset']=$offset;


$author_id=(int)$_POST['author_id'];//防止置顶循环覆盖了变量，重新获取
if($author_id&&$type!='buy'&&$type!='like'){
$args['author']=$author_id;//过滤只展示当前的用户数据
if($author_id==$user_id||jinsom_is_admin($user_id)){
$args['post_status']=array('publish','private');
}
}else{
$args['post_status']='publish';	
}

if(!$author_id){
	
if(isset($_COOKIE["sort"])){
if($_COOKIE["sort"]=='comment'){//最新回复
$args['meta_query']=array(
'last_comment_time' => array(
'key' => 'last_comment_time',
'type' => 'numeric',
)
);
$args['orderby']='last_comment_time';
}else if($_COOKIE["sort"]=='comment_count_month'){//本月热门
$args['date_query']=array(
array(
'column' => 'post_date',
'before' =>date('Y-m-d',time()+3600*24),
'after' =>date('Y-m-d',time()-3600*24*30)
)
);
$args['orderby']='comment_count';
}else{
$args['orderby']=$_COOKIE["sort"];	
}
}else{//默认设置
$sort=jinsom_get_option('jinsom_sns_home_default_sort');
if($sort=='comment'){//最新回复
$args['meta_query']=array(
'last_comment_time' => array(
'key' => 'last_comment_time',
'type' => 'numeric',
)
);
$args['orderby']='last_comment_time';
}else if($sort=='comment_count_month'){//本月热门
$args['date_query']=array(
array(
'column' => 'post_date',
'before' =>date('Y-m-d',time()+3600*24),
'after' =>date('Y-m-d',time()-3600*24*30)
)
);
$args['orderby']='comment_count';
}else{
$args['orderby']=$sort;	
}
}




if($type=='hot'){
$args['orderby']='comment_count';	
}
if($type=='rand'){
$args['orderby']='rand';	
}
}

if($jinsom_sns_home_load_type!='page'){
$args['no_found_rows']=true;
}


$args['showposts']=$number;

// print_r($args);

$the_query=new WP_Query($args);
if($the_query->have_posts()){
while ($the_query->have_posts()):$the_query->the_post();
require($require_url.'/post/post-list.php');
endwhile;
if(!$_POST['author_id']){
if($load_type!='more'){
if($jinsom_sns_home_load_type!='page'){
echo '<div class="jinsom-more-posts" author_id="'.$_POST['author_id'].'" page="2" onclick=\'jinsom_post("'.$type.'","more",this);\'>加载更多</div>';
}else{
echo '<div id="jinsom-sns-home-ajax-page" count="'.$the_query->found_posts.'" number="'.$number.'"></div>';
}
}
}else{
if($load_type!='more'){
echo '<div class="jinsom-more-posts" author_id="'.$_POST['author_id'].'" page="2" onclick=\'jinsom_post("'.$type.'","more",this);\'>加载更多</div>';
}
}
}else{
if($load_type=='more'){
echo 0;
}else{
echo jinsom_empty();
}	
}
