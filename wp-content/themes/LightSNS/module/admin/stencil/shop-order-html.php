<?php 
if($data_a){
foreach ($data_a as $data){
$post_id=$data->post_id;
$number=$data->number;
$pay_price=$data->pay_price;
$price_type=$data->price_type;
$select_info=$data->select_info;
$select_info_arr=unserialize($select_info);
$select_info_text='';
if($select_info_arr){
foreach ($select_info_arr as $arr) {
$select_info_text.='<span>'.$arr['name'].'：'.$arr['value'].'</span>';
}
}


$post_permalink=get_the_permalink($post_id);
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

$status=$data->status;
if($status==0){
$status_text=__('未付款','jinsom');
}else if($status==1){
$status_text=__('待发货','jinsom');	
}else if($status==2){
$status_text=__('待评价','jinsom');	
}else if($status==3){
$status_text=__('已完成','jinsom');	
}else{
$status_text=__('其他状态','jinsom');	
}


$goods_time=$data->time;
$goods_day=substr($goods_time,0,10);
if($goods_day==date('Y-m-d')){
$goods_time='<font style="color:#f00;">'.__('今天','jinsom').' '.substr($goods_time,10,18).'</font>';
}


echo '
<li class="order-'.$data->trade_no.'">
<div class="img"><a href="'.$post_permalink.'" target="_blank"><img src="'.$cover_one.'"></a></div>
<div class="info">
<div class="title"><a href="'.$post_permalink.'" target="_blank">'.get_the_title($post_id).'</a></div>
<div class="desc">
<span class="author">'.jinsom_nickname_link($data->user_id).'</span>
<span>'.__('数量','jinsom').'：'.$number.'</span>
<span>'.__('时间','jinsom').'：'.$goods_time.'</span>
'.$select_info_text.'
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
<div class="status">'.$status_text.'</div>
<div class="do"><span class="pay opacity" onclick=\'jinsom_goods_order_view_form("'.$data->trade_no.'")\'>查看订单</span></div>
</li>';
}
}else{
echo jinsom_empty();
}