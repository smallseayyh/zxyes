
<?php 
//我的订单
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$page=(int)$_POST['page'];
$number=(int)$_POST['number'];
$type=strip_tags($_POST['type']);
$offset=($page-1)*$number;


//待支付
if($type=='status-0'){
$data_0=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id and status=0 ORDER BY time desc limit $offset,$number;");
if($data_0){
foreach ($data_0 as $data) {
$post_id=$data->post_id;
$number=$data->number;
$time=strtotime($data->time);
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

if(time()-$time>86400){
$title_tips='<n>已失效</n>';
$buy_btn='<span class="pay opacity">已失效</span>';
}else{
$title_tips='';
$buy_btn='<span class="pay opacity" onclick=\'jinsom_goods_order_confirmation_buy_form("'.$data->trade_no.'","pay")\'>立即支付</span>';
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

echo '
<li>
<div class="img"><a href="'.$post_permalink.'" target="_blank"><img src="'.$cover_one.'"></a></div>
<div class="info">
<div class="title"><a href="'.$post_permalink.'" target="_blank">'.$title_tips.get_the_title($post_id).'</a></div>
<div class="desc">
<span>数量：'.$number.'</span>
<span>时间：'.$data->time.'</span>
'.$select_info_text.'
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
<div class="do">'.$buy_btn.'<span class="del" onclick="jinsom_goods_order_delete('.$data->trade_no.',this)">删除</span></div>
</li>';
}
}else{
echo jinsom_empty();
}
}



//待发货
if($type=='status-1'){
$data_1=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id and status=1 ORDER BY time desc limit $offset,$number;");
if($data_1){
foreach ($data_1 as $data) {
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

echo '
<li>
<div class="img"><a href="'.$post_permalink.'" target="_blank"><img src="'.$cover_one.'"></a></div>
<div class="info">
<div class="title"><a href="'.$post_permalink.'" target="_blank">'.get_the_title($post_id).'</a></div>
<div class="desc">
<span>数量：'.$number.'</span>
<span>时间：'.$data->time.'</span>
'.$select_info_text.'
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
<div class="do"><span class="pay opacity" onclick=\'jinsom_goods_order_confirmation_buy_form("'.$data->trade_no.'","pay")\'>查看订单</span></div>
</li>';
}
}else{
echo jinsom_empty();
}
}



//待评价
if($type=='status-2'){
$data_2=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id and status=2 ORDER BY time desc limit $offset,$number;");
if($data_2){
foreach ($data_2 as $data) {
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

echo '
<li>
<div class="img"><a href="'.$post_permalink.'" target="_blank"><img src="'.$cover_one.'"></a></div>
<div class="info">
<div class="title"><a href="'.$post_permalink.'" target="_blank">'.get_the_title($post_id).'</a></div>
<div class="desc">
<span>数量：'.$number.'</span>
<span>时间：'.$data->time.'</span>
'.$select_info_text.'
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
<div class="do"><span class="pay opacity" onclick=\'layer.msg("暂未开启！")\'>立即评价</span><span class="read" onclick=\'jinsom_goods_order_confirmation_buy_form("'.$data->trade_no.'","read")\'>查看</span></div>
</li>';
}
}else{
echo jinsom_empty();
}
}


//已发货
if($type=='status-3'){
$data_3=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id and status=3 ORDER BY time desc limit $offset,$number;");
if($data_3){
foreach ($data_3 as $data) {
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

echo '
<li>
<div class="img"><a href="'.$post_permalink.'" target="_blank"><img src="'.$cover_one.'"></a></div>
<div class="info">
<div class="title"><a href="'.$post_permalink.'" target="_blank">'.get_the_title($post_id).'</a></div>
<div class="desc">
<span>数量：'.$number.'</span>
<span>时间：'.$data->time.'</span>
'.$select_info_text.'
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
<div class="do"><span class="pay opacity" onclick=\'jinsom_goods_order_confirmation_buy_form("'.$data->trade_no.'","pay")\'>查看订单</span></div>
</li>';
}
}else{
echo jinsom_empty();
}
}



//喜欢的
if($type=='status-like'){
$table_name_like=$wpdb->prefix.'jin_collect';
$data_like=$wpdb->get_results("SELECT * FROM $table_name_like WHERE user_id = $user_id and type='goods' ORDER BY time desc limit $offset,$number;");
if($data_like){
foreach ($data_like as $data) {
$post_id=$data->post_id;
$goods_data=get_post_meta($post_id,'goods_data',true);
$goods_type=$goods_data['jinsom_shop_goods_type'];//商品类型
$price_type=$goods_data['jinsom_shop_goods_price_type'];
$select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐

if($goods_type=='a'||$goods_type=='d'||!$select_change_price_add){//本站虚拟、淘宝客、没有添加价格套餐
$price=(int)$goods_data['jinsom_shop_goods_price'];
$price_discount=(int)$goods_data['jinsom_shop_goods_price_discount'];
}else{
$price=(int)$select_change_price_add[0]['value_add'][0]['price'];
$price_discount=(int)$select_change_price_add[0]['value_add'][0]['price_discount'];
}


if($price_discount){
$price=$price_discount;
}

$post_permalink=get_the_permalink($post_id);

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
$price_icon='<m class="jinsom-icon jinsom-jinbi"></m> ';	
}

echo '
<li>
<div class="img"><a href="'.$post_permalink.'" target="_blank"><img src="'.$cover_one.'"></a></div>
<div class="info">
<div class="title"><a href="'.$post_permalink.'" target="_blank">'.get_the_title($post_id).'</a></div>
<div class="desc">
<span>销量：'.(int)$goods_data['buy_number'].'</span>
<span>时间：'.$data->time.'</span>
</div>
</div>
<div class="price"><span>'.$price_icon.$price.'</span></div>
<div class="do"><span class="pay opacity" onclick="jinsom_post_link(this)" data="'.$post_permalink.'">查看商品</span></div>
</li>';
}
}else{
echo jinsom_empty();
}
}