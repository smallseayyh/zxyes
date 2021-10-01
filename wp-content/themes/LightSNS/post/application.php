<?php 
$application_type=get_post_meta($post_id,'application-type',true);
$application_value=get_post_meta($post_id,'application-value',true);
if($application_type){
echo '<div class="jinsom-post-application-show">';
if($application_type=='url'){
echo '<a href="'.$application_value.'" target="_blank" class="url" rel="nofollow"><i class="jinsom-icon jinsom-tuiguang"></i> <span>'.$application_value.'</span></a>';
}else if($application_type=='challenge'){
echo '<a href=\'javascript:layer.msg("请在移动端体验该功能！")\' class="link challenge"><i class="jinsom-icon jinsom-jirou"></i> '.__('赶紧来挑战我吧！','jinsom').'</a>';
}else if($application_type=='pet'){
$pet_arr=explode(",",$application_value);
if(count($pet_arr)==2){
$pet_name=$pet_arr[0];
$pet_img=$pet_arr[1];
echo '<a href=\'javascript:layer.msg("请在移动端体验该功能！")\' class="link pet"><img src="'.$pet_img.'"> '.$pet_name.'-'.__('赶紧来看看我的新宠物吧！','jinsom').'</a>';
}
}else if($application_type=='shop'){
echo '<a href="'.get_the_permalink($application_value).'" class="shop" target="_blank">';
$goods_data=get_post_meta($application_value,'goods_data',true);
$goods_type=$goods_data['jinsom_shop_goods_type'];//商品类型
$price_type=$goods_data['jinsom_shop_goods_price_type'];
$select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐
if($goods_type=='a'||$goods_type=='d'||!$select_change_price_add){//本站虚拟、淘宝客、没有添加价格套餐
$pay_price=(int)$goods_data['jinsom_shop_goods_price'];
$price_discount=(int)$goods_data['jinsom_shop_goods_price_discount'];
}else{
$pay_price=(int)$select_change_price_add[0]['value_add'][0]['price'];
$price_discount=(int)$select_change_price_add[0]['value_add'][0]['price_discount'];
}
if($price_discount){
$pay_price=$price_discount;
}
$cover=$goods_data['jinsom_shop_goods_img'];
$cover_img_add=$goods_data['jinsom_shop_goods_img_add'];
if($cover_img_add){
$cover_one=$cover_img_add[0]['img'];
}else{
if($cover){
$cover_arr=explode(',',$cover);
$cover_src_one=wp_get_attachment_image_src($cover_arr[0],'full');
$cover_one=$cover_src_one[0];//第一张封面
}
}
if($price_type=='rmb'){
$price_icon='<t class="yuan">￥</t>';
}else{
$price_icon='<m class="jinsom-icon jinsom-jinbi"></m>';	
}

echo '<img src="'.$cover_one.'"><span class="title">'.get_the_title($application_value).'</span><span class="price">'.$price_icon.$pay_price.'</span>';

echo '</a>';
}
echo '</div>';
}