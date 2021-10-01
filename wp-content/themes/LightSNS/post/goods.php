<?php 
$post_id=get_the_ID();
$goods_data=get_post_meta($post_id,'goods_data',true);
if($goods_data){
$goods_type=$goods_data['jinsom_shop_goods_type'];//商品类型
$price_type=$goods_data['jinsom_shop_goods_price_type'];
$select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐


if($goods_type=='a'||$goods_type=='d'||!$select_change_price_add){//本站虚拟、淘宝客、没有添加价格套餐
$price=$goods_data['jinsom_shop_goods_price'];
$price_discount=$goods_data['jinsom_shop_goods_price_discount'];
}else{
$price=$select_change_price_add[0]['value_add'][0]['price'];
$price_discount=$select_change_price_add[0]['value_add'][0]['price_discount'];
}


if($price_discount){
$price_show=$price_discount;
if($price_type=='rmb'||$goods_type=='d'){
$price_discount_show='<n>'.$price.__('元','jinsom').'</n>';
}else{
$price_discount_show='<n>'.$price.jinsom_get_option('jinsom_credit_name').'</n>';	
}
}else{
$price_show=$price;
$price_discount_show='';
}

if($price_type=='rmb'||$goods_type=='d'){
$price_icon='<t class="yuan">￥</t>';
}else{
$price_icon='<m class="jinsom-icon jinsom-jinbi"></m>';	
}

$cover=$goods_data['jinsom_shop_goods_img'];
$cover_img_add=$goods_data['jinsom_shop_goods_img_add'];
if($cover_img_add){
$cover=$cover_img_add[0]['img'];
}else{
if($cover){
$cover_arr= explode(',',$cover);
$cover_src=wp_get_attachment_image_src($cover_arr[0],'full');
$cover=$cover_src[0];
}else{
$cover=get_template_directory_uri().'/images/default-cover.jpg';
}
}


$ico=$goods_data['jinsom_shop_goods_ico'];
if($ico){
$ico='<span class="ico">'.$ico.'</span>';
}else{
$ico='';
}
$power=$goods_data['jinsom_shop_goods_power'];
if($power=='vip'){
$power='<span class="power">'.__('VIP专享','jinsom').'</span>';
}else if($power=='verify'){
$power='<span class="power">'.__('认证专享','jinsom').'</span>';	
}else if($power=='new'){
$power='<span class="power">'.__('新人专享','jinsom').'</span>';	
}else if($power=='no'){
$power='<span class="power">'.__('预售','jinsom').'</span>';	
}else{
$power='';	
}
if($waterfall){
$bg='<div class="bg opacity"><img src="'.$cover.'"></div>';
}else{
$bg='<div class="bg opacity" style="background-image:url('.$cover.')"></div>';
}
echo '<li class="'.$style.'"><div class="mark">'.$power.$ico.'</div>
<a href="'.jinsom_mobile_post_url($post_id).'" target="_blank" class="link">
'.$bg.'
<h2>'.get_the_title().'</h2>
<div class="info clear">
<span>'.$price_icon.$price_show.' '.$price_discount_show.'</span><span><i class="jinsom-icon jinsom-goumai2"></i> '.(int)$goods_data['buy_number'].'</span>	
</div>
</a></li>';

}