<!-- SNS首页-公共部分 -->
<div class="pull-to-refresh-layer">
<div class="pull-to-refresh-arrow"><i class="jinsom-icon jinsom-xialashuaxin"></i></div>
</div>

<div class="jinsom-mobile-home-sns-top">

<?php echo do_shortcode(jinsom_get_option('jinsom_mobild_home_header_menu_custom_html'));?>

<?php 

//幻灯片
$jinsom_mobile_sns_slider_add=jinsom_get_option('jinsom_mobile_sns_slider_add');
if($jinsom_mobile_sns_slider_add){
echo '<div class="jinsom-mobile-sns-slider-content"><div class="jinsom-mobile-slider owl-carousel" id="jinsom-sns-slider">';
foreach ($jinsom_mobile_sns_slider_add as $sns_slider) {
if($sns_slider['jinsom_mobile_sns_slider_add_app']){
$class='class="link"';	
}else{
$class='';	
}
if(!$sns_slider['jinsom_mobile_sns_slider_add_app']&&$sns_slider['target']){
$target='target="_blank"';
}else{
$target='';	
}
$desc=$sns_slider['desc'];
if($desc){
$desc='<p>'.$desc.'</p>';
}else{
$desc='';
}

echo '<li class="item"><a href="'.do_shortcode($sns_slider['link']).'" '.$class.' '.$target.' style="background-image:url('.$sns_slider['images'].')">'.$desc.'</a></li>';		
}
echo '</div></div>';
}
echo do_shortcode(jinsom_get_option('jinsom_mobile_sns_slider_custom_html'));

//格子菜单
$jinsom_mobile_sns_cell_menu_add=jinsom_get_option('jinsom_mobile_sns_cell_menu_add');
if($jinsom_mobile_sns_cell_menu_add){
echo '<div class="jinsom-sns-cell-menu clear">';
foreach ($jinsom_mobile_sns_cell_menu_add as $sns_cell_menu){
if($sns_cell_menu['jinsom_mobile_sns_cell_menu_add_app']){
$class='class="link"';	
}else{
$class='';	
}

if($sns_cell_menu['login']){
if(is_user_logged_in()){
echo '<li><a href="'.do_shortcode($sns_cell_menu['link']).'" '.$class.' target="_blank"><img src="'.$sns_cell_menu['images'].'"><p>'.$sns_cell_menu['title'].'</p></a></li>';
}
}else{
echo '<li><a href="'.do_shortcode($sns_cell_menu['link']).'" '.$class.' target="_blank"><img src="'.$sns_cell_menu['images'].'"><p>'.$sns_cell_menu['title'].'</p></a></li>';
}	

}
echo '</div>';
}
echo do_shortcode(jinsom_get_option('jinsom_mobile_cell_menu_custom_html'));



$waterfall=$jinsom_sns_home_menu_add[0]['waterfall'];
?>


</div>


<!-- 内容列表 -->
<div class="jinsom-post-list jinsom-post-list-sns clear <?php if($waterfall){echo 'waterfall';}?>">
	
<?php 
jinsom_mobile_sns_home_hook();//移动端SNS首页内容列表钩子

//非引入文件或自定义html
if($type=='custom-html'||$type=='require'){
if($type=='custom-html'){
echo do_shortcode($jinsom_sns_home_menu_add[0]['custom_html']);
}else{
require(do_shortcode($jinsom_sns_home_menu_add[0]['require']));
}
}else{


$require_url=get_template_directory();

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
if($waterfall){
require(get_template_directory().'/mobile/templates/post/post-list-waterfall.php');
}else{
require(get_template_directory().'/mobile/templates/post/post-list.php');
}
}
endwhile;wp_reset_query();
}

$index=0;
$sticky_data=get_option('sticky_posts');//置顶数据
$jinsom_bbs_hide_arr=jinsom_get_option('jinsom_bbs_hide');//首页隐藏的板块
require($require_url.'/post/post.php');//内容类型

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

$args['no_found_rows']=true;
$args['post_status']='publish';
$args['showposts']=10;//移动端显示数量
query_posts($args);
if(have_posts()){
while(have_posts()):the_post();

if($waterfall){
require(get_template_directory().'/mobile/templates/post/post-list-waterfall.php');
}else{
require(get_template_directory().'/mobile/templates/post/post-list.php');
}

endwhile;
}else{
echo jinsom_empty();
}



}//非引入文件或自定义html
?>
<?php if($waterfall){?>
<script type="text/javascript">
var grid=$('.jinsom-post-list-sns').masonry({
itemSelector:'li',
gutter:0,
transitionDuration:0
});
grid.masonry('reloadItems'); 
grid.imagesLoaded().progress( function() {
grid.masonry('layout');
});
</script>
<?php }?>
</div> 
