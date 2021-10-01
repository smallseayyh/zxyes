<?php 
//移动端商城入口
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$shop_option=get_post_meta($post_id,'shop_page_data',true);
$cat_add=$shop_option['jinsom_mobile_shop_cat_add'];
$sort_add=$shop_option['jinsom_mobile_shop_sort_add'];
?>

<div data-page="shop-select" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="center sliding jinsom-select-navbar-center">
<form id="jinsom-shop-select-search-form" action="">
<i class="jinsom-icon jinsom-sousuo1"></i><input id="jinsom-shop-select-input" class="jinsom-select-input" type="search" placeholder="请输入商品名称" value="<?php echo $search;?>">
</form>
</div>
<div class="right" style="margin-left:0;width:13vw;"><a href="#" class="back link icon-only">返回</a></div>
<div class="subnavbar">
<div class="jinsom-select-subnavbar-list">

<!-- 分类 -->
<?php if($cat_add){?>
<div class="bbs"><span>全部</span> <i class="jinsom-icon jinsom-lower-triangle"></i>
<div class="list">
<ul class="clear">
<li data="all" class="on">全部</li>
<?php 
foreach ($cat_add as $data) {
echo '<li data="'.$data['cats'].'">'.$data['name'].'</li>';
}
?>
</ul>
</div>
</div>
<?php }?>


<?php if($sort_add){?>
<div class="sort"><span><?php echo $sort_add[0]['name'];?></span> <i class="jinsom-icon jinsom-lower-triangle"></i>
<div class="list">
<ul class="clear">
<?php 
$sort_i=0;
foreach ($sort_add as $data) {
if($sort_i==0){
$on='on';
}else{
$on='';
}
echo '<li class="'.$on.'" data="'.$data['jinsom_mobile_shop_sort_add_type'].'">'.$data['name'].'</li>';
$sort_i++;
}
?>
</ul>
</div>
</div>
<?php }?>
<div class="more">筛选 <i class="jinsom-icon jinsom-yousanjiao"></i></div>
</div>
</div>
</div>
</div>

<div class="page-content infinite-scroll jinsom-shop-goods-select-content" post_id="<?php echo $post_id;?>" data-distance="500">
<div class="jinsom-shop-select-post-list clear" page="1" list_style="<?php echo $shop_option['jinsom_mobile_shop_list_style'];?>"></div>
</div>
<div style="height: 2vw;display: none;" id="jinsom-waterfull-margin"></div>



<div class="jinsom-select-more-content" style="display: none;">
<div class="jinsom-select-left-more-content">
<div class="topic-list">

<div class="topic price_type clear">
<label>类型</label>
<ul>
<li class="on" data="all">全部</li>
<li data="credit">金币</li>
<li data="rmb">人民币</li>
</ul>
</div>

<?php if($shop_option['jinsom_mobile_shop_price_add']){?>
<div class="topic price clear">
<label>价格范围</label>
<ul>
<li class="on" data="all">全部</li>
<?php 
foreach ($shop_option['jinsom_mobile_shop_price_add'] as $data) {
echo '<li data="'.$data['min'].'-'.$data['max'].'">'.$data['name'].'</li>';
}
?>
</ul>
</div>
<?php }?>


</div>
</div>
</div>
