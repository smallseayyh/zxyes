<?php
//我的订单表单
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$data_2_count=$wpdb->get_var("SELECT count(ID) FROM $table_name WHERE user_id = $user_id and status=2;");//待评价数量
?>
<div class="jinsom-goods-order-content">


<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title">
<li class="layui-this"><?php _e('待付款','jinsom');?></li>
<li><?php _e('待发货','jinsom');?></li>
<li><?php _e('待评价','jinsom');?><?php if($data_2_count){echo '<n>'.$data_2_count.'</n>';}?></li>
<li><?php _e('已完成','jinsom');?></li>
<li><?php _e('收藏的商品','jinsom');?></li>
</ul>
<div class="layui-tab-content">

<!-- 待付款 -->
<div class="jinsom-goods-order-list status-0 layui-tab-item layui-show">
<div class="content">
<?php 
$data_0=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id and status=0 ORDER BY time desc limit 5 ;");
$data_0_count=$wpdb->get_var("SELECT count(ID) FROM $table_name WHERE user_id = $user_id and status=0;");
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
$title_tips='<n>'.__('已失效','jinsom').'</n>';
$buy_btn='<span class="pay opacity">'.__('已失效','jinsom').'</span>';
}else{
$title_tips='';
$buy_btn='<span class="pay opacity" onclick=\'jinsom_goods_order_confirmation_buy_form("'.$data->trade_no.'","pay")\'>'.__('立即支付','jinsom').'</span>';	
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
<span>'.__('数量','jinsom').'：'.$number.'</span>
<span>'.__('时间','jinsom').'：'.$data->time.'</span>
'.$select_info_text.'
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
<div class="do">'.$buy_btn.'<span class="del" onclick="jinsom_goods_order_delete('.$data->trade_no.',this)">'.__('删除','jinsom').'</span></div>
</li>';
}
}else{
echo jinsom_empty();
}
?>
</div>
<div id="jinsom-goods-order-0-page" class="jinsom-mycredit-page"></div>
</div>



<!-- 待发货 -->
<div class="jinsom-goods-order-list status-1 layui-tab-item">
<div class="content">
<?php 
$data_1=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id and status=1 ORDER BY time desc limit 5;");
$data_1_count=$wpdb->get_var("SELECT count(ID) FROM $table_name WHERE user_id = $user_id and status=1;");
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
<span>'.__('数量','jinsom').'：'.$number.'</span>
<span>'.__('时间','jinsom').'：'.$data->time.'</span>
'.$select_info_text.'
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
<div class="do"><span class="pay opacity" onclick=\'jinsom_goods_order_confirmation_buy_form("'.$data->trade_no.'","read")\'>'.__('查看订单','jinsom').'</span></div>
</li>';
}
}else{
echo jinsom_empty();
}
?>
</div>
<div id="jinsom-goods-order-1-page" class="jinsom-mycredit-page"></div>
</div>

<!-- 待评论 -->
<div class="jinsom-goods-order-list status-2 layui-tab-item">
<div class="content">
<?php 
$data_2=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id and status=2 ORDER BY time desc limit 5;");
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
<span>'.__('数量','jinsom').'：'.$number.'</span>
<span>'.__('时间','jinsom').'：'.$data->time.'</span>
'.$select_info_text.'
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
<div class="do"><span class="pay opacity" onclick=\'jinsom_goods_order_comment_form('.$post_id.',"'.$data->trade_no.'")\'>'.__('立即评价','jinsom').'</span><span class="read" onclick=\'jinsom_goods_order_confirmation_buy_form("'.$data->trade_no.'","read")\'>'.__('查看订单','jinsom').'</span></div>
</li>';
}
}else{
echo jinsom_empty();
}
?>
</div>
<div id="jinsom-goods-order-2-page" class="jinsom-mycredit-page"></div>
</div>


<!-- 已完成 -->
<div class="jinsom-goods-order-list status-3 layui-tab-item">
<div class="content">
<?php 
$data_3=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id and status=3 ORDER BY time desc limit 5;");
$data_3_count=$wpdb->get_var("SELECT count(ID) FROM $table_name WHERE user_id = $user_id and status=3;");
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
<span>'.__('数量','jinsom').'：'.$number.'</span>
<span>'.__('时间','jinsom').'：'.$data->time.'</span>
'.$select_info_text.'
</div>
</div>
<div class="price"><span>'.$price_icon.$pay_price.'</span></div>
<div class="do"><span class="pay opacity" onclick=\'jinsom_goods_order_confirmation_buy_form("'.$data->trade_no.'","read")\'>'.__('查看订单','jinsom').'</span></div>
</li>';
}
}else{
echo jinsom_empty();
}
?>
</div>
<div id="jinsom-goods-order-3-page" class="jinsom-mycredit-page"></div>
</div>



<!-- 收藏的 -->
<div class="jinsom-goods-order-list status-like layui-tab-item">
<div class="content">
<?php 
$table_name_like=$wpdb->prefix.'jin_collect';
$data_like=$wpdb->get_results("SELECT * FROM $table_name_like WHERE user_id = $user_id and type='goods' ORDER BY time desc limit 5;");
$data_like_count=$wpdb->get_var("SELECT count(ID) FROM $table_name_like WHERE user_id = $user_id and type='goods';");
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
<span>'.__('销量','jinsom').'：'.(int)$goods_data['buy_number'].'</span>
<span>'.__('时间','jinsom').'：'.$data->time.'</span>
</div>
</div>
<div class="price"><span>'.$price_icon.$price.'</span></div>
<div class="do"><span class="pay opacity" onclick="jinsom_post_link(this)" data="'.$post_permalink.'">'.__('查看商品','jinsom').'</span></div>
</li>';
}
}else{
echo jinsom_empty(__('你还没收藏任何商品！','jinsom'));
}
?>
</div>
<div id="jinsom-goods-order-like-page" class="jinsom-mycredit-page"></div>
</div>



</div>
</div>

</div>

<script type="text/javascript">
layui.use('laypage', function(){
var laypage = layui.laypage;


<?php if($data_0_count>5){?>
laypage.render({//待付款
elem: 'jinsom-goods-order-0-page',
theme:'#5fb878',
limit:5,
groups:3,
count: <?php echo $data_0_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-goods-order-list.status-0 .content').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/goods-order.php",
data:{page:page,number:number,type:'status-0'},
success: function(msg){
$('.jinsom-goods-order-list.status-0 .content').html(msg);
}
});

}
}
});
<?php }?>


<?php if($data_1_count>5){?>
laypage.render({//待发货
elem: 'jinsom-goods-order-1-page',
theme:'#5fb878',
limit:5,
groups:3,
count: <?php echo $data_1_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-goods-order-list.status-1 .content').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/goods-order.php",
data:{page:page,number:number,type:'status-1'},
success: function(msg){
$('.jinsom-goods-order-list.status-1 .content').html(msg);
}
});

}
}
});
<?php }?>


<?php if($data_2_count>5){?>
laypage.render({//待评价
elem: 'jinsom-goods-order-2-page',
theme:'#5fb878',
limit:5,
groups:3,
count: <?php echo $data_2_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-goods-order-list.status-2 .content').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/goods-order.php",
data:{page:page,number:number,type:'status-2'},
success: function(msg){
$('.jinsom-goods-order-list.status-2 .content').html(msg);
}
});

}
}
});
<?php }?>


<?php if($data_3_count>5){?>
laypage.render({//已完成
elem: 'jinsom-goods-order-3-page',
theme:'#5fb878',
limit:5,
groups:3,
count: <?php echo $data_3_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-goods-order-list.status-3 .content').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/goods-order.php",
data:{page:page,number:number,type:'status-3'},
success: function(msg){
$('.jinsom-goods-order-list.status-3 .content').html(msg);
}
});

}
}
});
<?php }?>


<?php if($data_like_count>5){?>
laypage.render({//喜欢的
elem: 'jinsom-goods-order-like-page',
theme:'#5fb878',
limit:5,
groups:3,
count: <?php echo $data_like_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-goods-order-list.status-like .content').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/goods-order.php",
data:{page:page,number:number,type:'status-like'},
success: function(msg){
$('.jinsom-goods-order-list.status-like .content').html(msg);
}
});

}
}
});
<?php }?>

});
</script>