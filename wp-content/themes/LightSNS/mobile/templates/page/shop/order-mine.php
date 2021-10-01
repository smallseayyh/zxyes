<?php 
//我的订单
require( '../../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$data_0=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id and status=0 ORDER BY time desc limit 10 ;");
if(isset($_GET['type'])&&$_GET['type']=='add_application'){
$link_type='add_application';
}else{
$link_type='link';
}
?>
<div data-page="order-mine" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left"><a href="#" class="back link icon-only"><i class="jinsom-icon jinsom-fanhui2"></i></a></div>
<div class="center sliding">我的订单</div>
<div class="right"><a href="#" class="link icon-only"></a></div>
<div class="subnavbar">
<div class="jinsom-home-menu jinsom-shop-menu clear">
<li class="on" type="status-0" onclick="jinsom_order_data('status-0','<?php echo $link_type;?>',this)"><?php _e('待付款','jinsom');?></li>
<li type="status-1" onclick="jinsom_order_data('status-1','<?php echo $link_type;?>',this)"><?php _e('待发货','jinsom');?></li>
<li type="status-2" onclick="jinsom_order_data('status-2','<?php echo $link_type;?>',this)"><?php _e('待评价','jinsom');?></li>
<li type="status-3" onclick="jinsom_order_data('status-3','<?php echo $link_type;?>',this)"><?php _e('已完成','jinsom');?></li>
<li type="collect" onclick="jinsom_order_data('collect','<?php echo $link_type;?>',this)"><?php _e('收藏的商品','jinsom');?></li>
</div>
</div>
</div>
</div>



<div class="page-content jinsom-shop-order-mine-content infinite-scroll" data-distance="500">
<div class="jinsom-shop-order-mine-list">
<?php 
if($data_0){
foreach ($data_0 as $data) {
$post_id=$data->post_id;
$number=$data->number;
$trade_no=$data->trade_no;
$time=strtotime($data->time);
$pay_price=$data->pay_price;
$price_type=$data->price_type;


// $post_permalink=get_the_permalink($post_id);
$goods_data=get_post_meta($post_id,'goods_data',true);


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


//价格类型
if($price_type=='rmb'){
$price_icon='<t class="yuan">￥</t>';
}else{
$price_icon='<m class="jinsom-icon jinsom-jinbi"></m>';	
}


if($link_type=='add_application'){
$link='onclick="jinsom_publish_add_application_shop(this)"';
}else{
$link='onclick=\'jinsom_order_details('.$data->ID.')\'';
}


echo '
<li data="'.$post_id.'" id="jinsom-order-'.$trade_no.'">
<a '.$link.' class="link">
<div class="img"><img src="'.$cover_one.'"></div>
<div class="info">
<div class="title">'.$title_tips.get_the_title($post_id).'</div>
<div class="desc">
<div class="number">'.__('数量','jinsom').'：'.$number.'</div>
<div class="time">'.__('时间','jinsom').'：'.$data->time.'</div>
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
</a>
</li>';
}
}else{
echo jinsom_empty('没有订单数据！');
}
?>

</div>
</div>

</div>   

