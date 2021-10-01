<?php
/*
Template Name:SNS首页(默认)
*/
if(wp_is_mobile()){
require(get_template_directory().'/mobile/index.php');	
}else{
get_header();
$user_id=$current_user->ID;
$require_url=get_template_directory();
$jinsom_slider_on_off=jinsom_get_option('jinsom_slider_on_off');
$jinsom_slider_default_style=jinsom_get_option('jinsom_slider_default_style');
$jinsom_slider_add=jinsom_get_option('jinsom_slider_add');
$jinsom_slider_navigation_on_off=jinsom_get_option('jinsom_slider_navigation_on_off');
$jinsom_slider_pagination_on_off=jinsom_get_option('jinsom_slider_pagination_on_off');
$jinsom_media_add=jinsom_get_option('jinsom_media_add');
$jinsom_sns_home_load_type=jinsom_get_option('jinsom_sns_home_load_type');


//幻灯片
if($jinsom_slider_on_off){
$slider_html=do_shortcode(jinsom_get_option('jinsom_slider_top_html'));//幻灯片头部自定义内容
$slider_html.= '<div class="jinsom-slider"><div class="swiper-wrapper">';
if(!empty($jinsom_slider_add)){
foreach ($jinsom_slider_add as $jinsom_slider_adds) {
if($jinsom_slider_adds['target']){$target='target="_blank"';}else{$target='';}
$slider_html.= '
<a class="swiper-slide" href="'.$jinsom_slider_adds['link'].'" '.$target.' style="background-image: url('.$jinsom_slider_adds['images'].');">
<div class="swiper-text">
<h2>'.$jinsom_slider_adds['title'].'</h2>
<p>'.$jinsom_slider_adds['desc'].'</p>
</div>
</a>';

} 
}//empty

$slider_html.= '</div>';
if($jinsom_slider_pagination_on_off){
$slider_html.= '<div class="swiper-pagination"></div>';
}
if($jinsom_slider_navigation_on_off){
$slider_html.= '<div class="swiper-button-next jinsom-icon jinsom-arrow-right"></div>
<div class="swiper-button-prev jinsom-icon jinsom-arrow-left"></div>';
}

$slider_html.= '</div>';

$slider_html.=do_shortcode(jinsom_get_option('jinsom_slider_bottom_html'));//幻灯片底部自定义内容

if($jinsom_slider_default_style!='s'){
echo $slider_html;
}
}//幻灯片结束




if(!empty($jinsom_media_add)){
echo '<div class="jinsom-media-show">';
foreach ($jinsom_media_add as $jinsom_media_adds) {
$link=$jinsom_media_adds['link'];
$desc=$jinsom_media_adds['desc'];
$title=$jinsom_media_adds['title'];
$video_url=$jinsom_media_adds['video_url'];
$img_url=$jinsom_media_adds['images'];
if($jinsom_media_adds['jinsom_media_add_type']=='a'){
echo '<li><a href="'.$link.'"><img src="'.$img_url.'" class="opacity" loading="lazy"></a></li>';
}elseif($jinsom_media_adds['jinsom_media_add_type']=='b'){
echo '
<li>
<figure class="effect-apollo">
<img src="'.$img_url.'" loading="lazy">
<figcaption>
<h2>'.$title.'</h2>
<p>'.$desc.'</p>
<a href="'.$link.'">'.__('查看','jinsom').'</a>
</figcaption>     
</figure>
</li>';
}elseif($jinsom_media_adds['jinsom_media_add_type']=='c'){
echo '<li>
<figure class="effect-ming">
<img src="'.$img_url.'" loading="lazy">
<figcaption>
<h2>'.$title.'</h2>
<p>'.$desc.'</p>
<a href="'.$link.'">'.__('查看','jinsom').'</a>
</figcaption>     
</figure>
</li> '; 
}else{
echo " 
<li onclick=\"jinsom_pop_video('".$video_url."','".$img_url."',this);\" data='".$title."'><a>
<span>
<i class='fa fa-play'></i></span>
<img src='".$img_url."' loading='lazy'></a></li>";
}


}
echo '</div>';
}


echo do_shortcode(jinsom_get_option('jinsom_media_bottom_html'));//媒体格子底部自定义内容

?>



<!-- 主内容 -->
<div class="jinsom-main-content sns clear">

<div class="jinsom-content-left"><!-- 左侧 -->
<?php 

//输出小屏幻灯片
if($jinsom_slider_default_style=='s'){
echo $slider_html;
}

echo do_shortcode(jinsom_get_option('jinsom_ajax_menu_top_html'));//ajax菜单区域自定义内容

//sns菜单
$type='';
$jinsom_sns_home_menu_add=jinsom_get_option('jinsom_sns_home_menu_add');
if($jinsom_sns_home_menu_add){
// $type=$jinsom_sns_home_menu_add[0]['jinsom_sns_home_menu_type'];
if(count($jinsom_sns_home_menu_add)>1){
echo '<div class="jinsom-index-menu clear">';
echo '<div class="jinsom-index-menu-list">';
$i=1;
foreach($jinsom_sns_home_menu_add as $data){
if(!$data['in_mobile']){
$sns_menu_type=$data['jinsom_sns_home_menu_type'];

//选中项
if(isset($_GET['type'])&&strip_tags($_GET['type'])!=''){
if($sns_menu_type==strip_tags($_GET['type'])){
$on='class="on"';	
}else{
$on='';
}
}else{
if($i==1){$on='class="on"';}else{$on='';}
}


if(!$type){$type=$sns_menu_type;}//获取第一个有效的类型
if($sns_menu_type=='custom-link'){
echo '<li type="'.$sns_menu_type.'" '.$on.' onclick="'.$data['link'].'">'.$data['name'].'</li>';
}else if($sns_menu_type=='custom-bbs'){
echo '<li type="'.$sns_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\' data="'.$data['bbs_id'].'">'.$data['name'].'</li>';
}else if($sns_menu_type=='custom-topic'){
echo '<li type="'.$sns_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\' data="'.$data['topic_id'].'">'.$data['name'].'</li>';
}else if($sns_menu_type=='hot'||$sns_menu_type=='rand'){
echo '<li type="'.$sns_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\' data="'.$data['time'].'">'.$data['name'].'</li>';
}else{
echo '<li type="'.$sns_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\'>'.$data['name'].'</li>'; 
}
$i++;
}else{
echo '<li style="display:none;"></li>';
}
}
echo '</div>';
echo '</div>';
}else{//只有一个菜单的情况
$type=$jinsom_sns_home_menu_add[0]['jinsom_sns_home_menu_type'];
}
}else{
$type='all';
}

if(isset($_GET['type'])&&strip_tags($_GET['type'])!=''){
$type=strip_tags($_GET['type']);
}

echo do_shortcode(jinsom_get_option('jinsom_ajax_menu_bottom_html'));//ajax菜单区域自定义内容


//动态列表风格
$jinsom_post_list_type = jinsom_get_option('jinsom_post_list_type');
if(empty($_COOKIE["post-style"])){
if($jinsom_post_list_type=='post-style-block'){
$normal='block';
}else{
$normal='time';
}
}else{
if($_COOKIE["post-style"]=='post-style-block.css'){
$normal='block';
}else{
$normal='time';
} 
}
?>
<div class="jinsom-post-list <?php echo $normal;?> clear">
<?php 

//非引入文件或自定义html
if(isset($_GET['index'])){
$index=(int)$_GET['index'];
}else{
$index=0;
}
if($type=='custom-html'||$type=='require'){
if($type=='custom-html'){
echo do_shortcode($jinsom_sns_home_menu_add[$index]['custom_html']);
}else{
require(do_shortcode($jinsom_sns_home_menu_add[$index]['require']));
}
}else{


//显示置顶
if(!isset($_GET['index'])||(isset($_GET['index'])&&$_GET['index']==0)){
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

if($jinsom_sns_home_load_type!='page'){
$args['no_found_rows']=true;
}

$args['post_status']='publish';

//显示数量
$number=get_option('posts_per_page',10);
$args['showposts']=$number;

//页数
if(isset($_GET['page'])){
$page=(int)$_GET['page'];
if(!$page){$page=1;}
$offset=($page-1)*$number;
$args['offset']=$offset;
}


$the_query=new WP_Query($args);
if($the_query->have_posts()){
while ($the_query->have_posts()):$the_query->the_post();
require($require_url.'/post/post-list.php');
endwhile;
if($jinsom_sns_home_load_type!='page'){
echo '<div class="jinsom-more-posts" page="2" onclick=\'jinsom_post("'.$type.'","more",this);\'>'.__('加载更多','jinsom').'</div>';
}else{?>
<div id="jinsom-sns-home-page"></div>
<script>
layui.use('laypage', function(){
var laypage = layui.laypage;
laypage.render({
elem:'jinsom-sns-home-page',
count: <?php echo $the_query->found_posts;?>,
limit:<?php echo $number;?>,
curr:<?php if(isset($_GET['page'])){echo $_GET['page'];}else{echo 1;}?>,
theme:'var(--jinsom-color)',
jump:function(obj,first){
type=$('.jinsom-index-menu li.on').attr('type');
index=$('.jinsom-index-menu li.on').index();
page=obj.curr;
if(!first){
window.open('/?type='+type+'&index='+index+'&page='+page,'_self');
}
}
});
});
</script>
<?php }
}else{
echo jinsom_empty();	
}


}//非引入文件或自定义html
?>
</div>




</div><!-- 左侧结束 -->

<?php require($require_url.'/sidebar/sidebar-index.php');?><!-- 右侧 -->


</div>
<?php get_footer();?>
<?php 
if($jinsom_slider_on_off){//首页并且开启了幻灯片功能
$jinsom_slider_type = jinsom_get_option('jinsom_slider_type');
$jinsom_slider_active_on_off = jinsom_get_option('jinsom_slider_active_on_off');
$jinsom_slider_time = jinsom_get_option('jinsom_slider_time_a');
?>
<script>
var swiper = new Swiper('.jinsom-slider', {
pagination: {
el: '.swiper-pagination',
clickable :true,
},
navigation: {
nextEl: '.swiper-button-next',
prevEl: '.swiper-button-prev',
},
speed:<?php echo $jinsom_slider_time;?>,
<?php if($jinsom_slider_active_on_off){?>
autoplay:true,
<?php }?>
loop:true,
effect: '<?php echo $jinsom_slider_type;?>',
cube: {
slideShadows: false,
shadow: false,
shadowOffset: 20,
shadowScale: 0.94
}
});
</script>
<?php }?>



<?php }?>