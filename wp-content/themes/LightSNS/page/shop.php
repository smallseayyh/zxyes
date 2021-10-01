<?php
/*
Template Name:商城
*/
if(wp_is_mobile()){
require($require_url.'/mobile/index.php');
}else{
get_header();
$page_id=get_the_ID();
$shop_page_data=get_post_meta($page_id,'shop_page_data',true);
$custom_sidebar=$shop_page_data['jinsom_shop_sidebar_type'];
$show_type=$shop_page_data['jinsom_shop_show_type'];
$jinsom_shop_slider_on_off=$shop_page_data['jinsom_shop_slider_on_off'];
$jinsom_shop_slider_add=$shop_page_data['jinsom_shop_slider_add'];
$jinsom_shop_header_commend_add=$shop_page_data['jinsom_shop_header_commend_add'];
$category_ids='';
?>

<div class="jinsom-shop-main jinsom-shop-<?php echo $page_id;?> <?php echo $show_type;?>">

<?php if($jinsom_shop_slider_on_off&&$jinsom_shop_slider_add){?>
<div class="jinsom-shop-slider">

<?php 
$jinsom_shop_slider_nav_add=$shop_page_data['jinsom_shop_slider_nav_add'];
if($jinsom_shop_slider_nav_add){
?>	
<div class="nav">
<div class="menu">
<div class="list">
<?php 
foreach ($jinsom_shop_slider_nav_add as $data) {
?>
<li>
<a href="<?php echo $data['link'];?>" target="_blank"><?php echo $data['title'];?><i class="jinsom-icon jinsom-arrow-right"></i></a>
<?php if($data['nav_add']){?>
<div class="views">
<div class="content">
<?php 
foreach ($data['nav_add'] as $nav) {
echo '<a href="'.$nav['sublink'].'" target="_blank" title="'.$nav['subtitle'].'"><img loading="lazy" alt="'.$nav['subtitle'].'" src="'.$nav['subimages'].'"><span>'.$nav['subtitle'].'</span></a>';
}
?>
</div>
</div>
<?php }?>
</li>

<?php }?>
	
</div>	
</div>
</div>
<?php }?>
<div class="swiper-wrapper">
<?php 
foreach ($jinsom_shop_slider_add as $data) {
echo '<a href="'.$data['link'].'" target="_blank" class="swiper-slide" style="background-image: url('.$data['images'].');"></a>';
}
?>
</div>
<div class="swiper-pagination"></div>
</div>
<?php }?>

<?php echo do_shortcode($shop_page_data['jinsom_shop_header_html']);//头部自定义区域 ?>
<div class="jinsom-main-content <?php if($custom_sidebar=='no'){echo 'full';}?> clear">

<?php if($jinsom_shop_header_commend_add){?>
<div class="jinsom-shop-commend-ad clear">
<?php foreach ($jinsom_shop_header_commend_add as $data) {
echo '<li><a href="'.$data['link'].'" target="_blank"><img loading="lazy" src="'.$data['images'].'"></a></li>';
}?>
</div>
<?php }?>


<div class="jinsom-content-left">
<?php 
if($show_type=='box'){//模块添加
$jinsom_shop_box_add=$shop_page_data['jinsom_shop_box_add'];
if($jinsom_shop_box_add){
foreach ($jinsom_shop_box_add as $data) {
$jinsom_shop_box_add_type=$data['jinsom_shop_box_add_type'];

if($jinsom_shop_box_add_type=='goods'){//展示商品
$category_ids=$data['ids'];
$style=$data['goods_style'];//商品列表样式
$title_style=$data['title_style'];//标题样式
$title_link=$data['title_link'];
if($title_link){
$title='<a href="'.$title_link.'" target="_blank">'.$data['title'].'</a>';
}else{
$title=$data['title'];
}

echo '<div class="jinsom-shop-goods-box">';

//头部
if($data['title']){//如果填写标题则显示头部
echo '<div class="jinsom-shop-header '.$title_style.'">
<div class="title">'.$title.'</div>';

if($data['subtitle_text']){
echo '<div class="subtitle">'.$data['subtitle_text'].'</div>';
}
if($data['jinsom_shop_box_add_submenu_add']&&$title_style!='center'){
echo '<div class="submenu">';

foreach ($data['jinsom_shop_box_add_submenu_add'] as $value) {
echo '<li><a href="'.$value['subtitle_link'].'" target="_blank">'.$value['subtitle'].'</a></li>';
}

echo '</div>';
}

echo '</div>';//jinsom-shop-header
}

//内容区
echo '<div class="jinsom-shop-content clear">';
$args = array(
'post_type' => 'goods',
'showposts' => $data['number'],
'post_status' => 'publish',
);
if($category_ids){
$category_ids_arr=explode(",",$category_ids);
$args['tax_query']=array(
array(
'taxonomy' => 'shop',
'field' => 'id',
'terms' => $category_ids_arr
)
);
}
query_posts($args);
if(have_posts()){
while(have_posts()):the_post();
require($require_url.'/post/goods.php');
endwhile;
wp_reset_query();
}else{
echo jinsom_empty();	
}

echo '</div>';//jinsom-shop-content

echo '</div>';

}else{
echo $data['jinsom_shop_box_add_type_html'];//自定义内容
}

}
}else{
echo jinsom_empty('请后台添加数据');	
}
}else{//展示列表
$style=$shop_page_data['jinsom_shop_style_type'];
$number=$shop_page_data['jinsom_shop_show_number'];
$jinsom_shop_header_menu_add=$shop_page_data['jinsom_shop_header_menu_add'];
if($jinsom_shop_header_menu_add){
$a=1;
?>
<div class="jinsom-shop-header <?php echo $style;?>">
<?php 
foreach ($jinsom_shop_header_menu_add as $data) {
if($a==1){
$category_ids=$data['ids'];
$on='class="on"';
}else{
$on='';
}
echo '<li '.$on.' data="'.$data['ids'].'" onclick="jinsom_shop_data(this)">'.$data['name'].'</li>';
$a++;
}
?>
</div>
<?php }?>
<div class="jinsom-shop-content clear">
<?php 
$args = array(
'post_type' => 'goods',
'showposts' => $number,
'post_status' => 'publish',
);
if($category_ids){
$category_ids_arr=explode(",",$category_ids);
$args['tax_query']=array(
array(
'taxonomy' => 'shop',
'field' => 'id',
'terms' => $category_ids_arr
)
);
}
query_posts($args);
if(have_posts()){
$i=0;
while(have_posts()):the_post();
require($require_url.'/post/goods.php');
$i++;
endwhile;
if($i==$number){
echo '<div class="jinsom-more-posts opacity" page="2" onclick="jinsom_shop_data_more(this)">'.__('加载更多','jinsom').'</div>';
}

}else{
echo jinsom_empty();	
}


?>
</div>
<?php }//模块添加/展示列表 ?>
</div>
<?php 
if($custom_sidebar!='no'){
require(get_template_directory().'/sidebar/sidebar-custom.php');//引入右侧栏 
}?>
</div>
<?php echo do_shortcode($shop_page_data['jinsom_shop_footer_html']);//底部自定义区域 ?>
</div>


<script type="text/javascript">
<?php if($show_type=='list'){?>
function jinsom_shop_data(obj){
$(obj).addClass('on').siblings().removeClass('on');
data=$(obj).attr('data');
if($('.jinsom-load-post').length==0){
$('.jinsom-shop-content').prepend(jinsom.loading_post);
}
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/goods.php",
data: {data:data,page:1,load_type:'menu',number:<?php echo $number;?>,style:'<?php echo $style;?>'},
success: function(msg){   
$('.jinsom-shop-content').html(msg);
}
});
}

function jinsom_shop_data_more(obj){
data=$('.jinsom-shop-header li.on').attr('data');
page=parseInt($(obj).attr('page'));
if($('.jinsom-load-post').length==0){
$(obj).before(jinsom.loading_post);
$(obj).hide();
}
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/goods.php",
data: {data:data,page:page,load_type:'more',number:<?php echo $number;?>,style:'<?php echo $style;?>'},
success: function(msg){
$('.jinsom-load-post').remove();
$(obj).show();
if(msg==0){
$(obj).remove();
layer.msg('没有更多内容');
}else{
$(obj).before(msg);
}
page++;	
$(obj).attr('page',page);
}
});
}

<?php }?>

// $('.jinsom-shop-slider .nav .menu .list li').hover(function(){
// $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
// },function(){
// $(this).parent().next().children().eq($(this).index()).hide();
// });

</script>


<?php get_footer();?>

<script type="text/javascript">
//幻灯片
var swiper = new Swiper('.jinsom-shop-slider', {
pagination: {
el: '.swiper-pagination',
clickable :true,
},
paginationClickable: true,
centeredSlides: true,
speed:2000,
autoplay : {
delay:5000
},
loop:true,
autoplayDisableOnInteraction: true,//当用户操作之后，则停止
effect: 'fade',
cube: {
slideShadows: false,
shadow: false,
shadowOffset: 20,
shadowScale: 0.94
}
});
</script>


<?php }?>