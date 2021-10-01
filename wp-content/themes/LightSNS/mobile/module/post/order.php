<?php 
//订单数据
require( '../../../../../../wp-load.php' );
$require_url=get_template_directory();
$user_id=$current_user->ID;
$page=(int)$_POST['page'];
$type=strip_tags($_POST['type']);
$link_type=strip_tags($_POST['link_type']);


if(!$page){$page=1;}
$number=10;
$offset=($page-1)*$number;

global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';


if($type=='status-0'){//待付款
$goods_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id=$user_id and status=0 ORDER BY time desc limit $offset,$number;");
}else if($type=='status-1'){//待发货
$goods_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id=$user_id and status=1 ORDER BY time desc limit $offset,$number;");	
}else if($type=='status-2'){//待评价
$goods_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id=$user_id and status=2 ORDER BY time desc limit $offset,$number;");	
}else if($type=='status-3'){//已完成
$goods_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id=$user_id and status=3 ORDER BY time desc limit $offset,$number;");	
}else if($type=='collect'){//收藏的商品
$table_name_like=$wpdb->prefix.'jin_collect';
$goods_data=$wpdb->get_results("SELECT * FROM $table_name_like WHERE user_id=$user_id and type='goods' ORDER BY time desc limit $offset,$number;");	
}


if($goods_data){
foreach ($goods_data as $data) {
$post_id=$data->post_id;
$trade_no=$data->trade_no;
$time=strtotime($data->time);
$goods_data=get_post_meta($post_id,'goods_data',true);


if($type=='collect'){
$number=(int)$goods_data['buy_number'];
$number=__('销量','jinsom').'：'.$number;
$goods_type=$goods_data['jinsom_shop_goods_type'];//商品类型
$price_type=$goods_data['jinsom_shop_goods_price_type'];
$select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐

if($goods_type=='a'||$goods_type=='d'||!$select_change_price_add){//本站虚拟、淘宝客、没有添加价格套餐
$pay_price=$goods_data['jinsom_shop_goods_price'];
$price_discount=$goods_data['jinsom_shop_goods_price_discount'];
}else{
$pay_price=$select_change_price_add[0]['value_add'][0]['price'];
$price_discount=$select_change_price_add[0]['value_add'][0]['price_discount'];
}

if($price_discount){
$pay_price=$price_discount;
}
}else{
$number=__('数量','jinsom').'：'.$data->number;
$pay_price=$data->pay_price;
$price_type=$data->price_type;
}

// $post_permalink=get_the_permalink($post_id);
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

if($link_type=='add_application'){
$link='onclick="jinsom_publish_add_application_shop(this)"';
}else{
if($type=='collect'){
$link='href="'.jinsom_mobile_post_url($post_id).'"';
}else{
$link='onclick=\'jinsom_order_details('.$data->ID.')\'';
}
}


//价格类型
if($price_type=='rmb'){
$price_icon='<t class="yuan">￥</t>';
}else{
$price_icon='<m class="jinsom-icon jinsom-jinbi"></m>';	
}

echo '
<li data="'.$post_id.'" id="jinsom-order-'.$trade_no.'">
<a '.$link.' class="link">
<div class="img"><img src="'.$cover_one.'"></div>
<div class="info">
<div class="title">'.$title_tips.get_the_title($post_id).'</div>
<div class="desc">
<div class="number">'.$number.'</div>
<div class="time">'.__('时间','jinsom').'：'.$data->time.'</div>
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
</a>
</li>';
}
}else{
if($_POST['page']==1){
echo jinsom_empty('没有订单数据！');
}else{
echo 0;
}
}