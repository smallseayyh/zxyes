<?php 
if(wp_is_mobile()){
// require($require_url.'/mobile/index.php');
header("Location:/");
}else{
get_header();
$shop_data=get_queried_object();
$shop_id=$shop_data->term_id;
$number=12;
$style='two';//商品列表风格
?>

<div class="jinsom-shop-main jinsom-shop-<?php echo $shop_id;?> box taxonomy">
<div class="jinsom-main-content full clear">
<div class="jinsom-content-left">

<div class="jinsom-shop-header center">
<div class="title"><?php echo $shop_data->name;?></div>
<div class="subtitle"><?php echo $shop_data->description;?></div>
</div>

<div class="jinsom-shop-content clear">

<?php
$args = array(
'post_type' => 'goods',
'showposts' =>$number,
'post_status' => 'publish',
);
$args['tax_query']=array(
array(
'taxonomy' => 'shop',
'field' => 'id',
'terms' => $shop_id
)
);
$args['no_found_rows']=true;
query_posts($args); 
if(have_posts()){
$i=0;
while(have_posts()):the_post();
require($require_url.'/post/goods.php');
$i++;
endwhile;
wp_reset_query();
if($i==$number){
echo '<div class="jinsom-more-posts opacity" page="2" onclick="jinsom_shop_taxonomy_data_more(this)">'.__('加载更多','jinsom').'</div>';
}
}else{
echo jinsom_empty();	
}
?>

</div>

</div>
</div>
</div>
<script type="text/javascript">
function jinsom_shop_taxonomy_data_more(obj){
page=parseInt($(obj).attr('page'));
if($('.jinsom-load-post').length==0){
$(obj).before(jinsom.loading_post);
$(obj).hide();
}
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/goods.php",
data: {data:<?php echo $shop_id;?>,page:page,load_type:'more',number:<?php echo $number;?>,style:'<?php echo $style;?>'},
success: function(msg){
$('.jinsom-load-post').remove();
$(obj).show();
if(msg==0){
$(obj).remove();
layer.msg(__('没有更多商品','jinsom'));
}else{
$(obj).before(msg);
}
page++;	
$(obj).attr('page',page);
}
});
}
</script>
<?php get_footer();?>

<?php }//电脑端?>