<?php 
//查看详细的订单
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$trade_no=(int)$_POST['trade_no'];
$order_data=$wpdb->get_results("SELECT * FROM $table_name WHERE trade_no = $trade_no limit 1;");
foreach ($order_data as $data) {
$select_arr=unserialize($data->select_info);
$number=$data->number;
$post_id=$data->post_id;
$price_show=$data->pay_price;//显示的支付价格
$price_type=$data->price_type;
$goods_type=$data->goods_type;
$pay_type=$data->pay_type;//支付类型
$buy_user_id=$data->user_id;//购买的用户
$order_status=$data->status;//订单状态
}


if($pay_type=='credit'){
$pay_type=jinsom_get_option('jinsom_credit_name');
}else if($pay_type=='qrcode'||$pay_type=='alipay'){
$pay_type=__('支付宝支付','jinsom');
}else if($pay_type=='wechat'){
$pay_type=__('微信支付','jinsom');
}else if($pay_type=='xunhu-wechat'){
$pay_type=__('迅虎微信支付','jinsom');
}

if($order_status==0){
$order_status_text=__('未付款订单','jinsom');
}else if($order_status==1){
$order_status_text=__('待发货订单','jinsom');
}else if($order_status==2){
$order_status_text=__('待评论订单','jinsom');
}else if($order_status==3){
$order_status_text=__('已完成订单','jinsom');
}else{
$order_status_text=__('其他状态订单','jinsom');
}


if($price_type=='rmb'||$goods_type=='d'){
$price_icon='<t class="yuan">￥</t>';
}else{
$price_icon='<m class="jinsom-icon jinsom-jinbi"></m>';	
}
?>
<div class="jinsom-goods-order-confirmation-content">
<div class="title"><a href="<?php echo get_the_permalink($post_id);?>" target="_blank"><?php echo get_the_title($post_id);?></a></div>
<div class="box">
<div class="price"><span><?php _e('金额','jinsom');?></span><span><?php echo $price_icon.$price_show;?></span></div>
<?php if($select_arr){?>
<div class="select">
<?php 
for ($i=0; $i <count($select_arr) ; $i++) { 
echo '<li><span>'.$select_arr[$i]['name'].'</span><span>'.$select_arr[$i]['value'].'</span></li>';
}
?>
</div>
<?php }?>

<div class="number"><span><?php _e('数量','jinsom');?></span><span><?php echo $number;?></span></div>

<?php if($goods_type=='a'){?>

<?php if($data->virtual_type=='honor'){?>
<div class="virtual_honor"><span><?php _e('头衔','jinsom');?></span><span><?php echo $data->virtual_info;?></span></div>
<?php }?>

<?php if($data->virtual_type=='exp'){?>
<div class="virtual_exp"><span><?php _e('经验值','jinsom');?></span><span><?php echo $data->virtual_info;?></span></div>
<?php }?>

<?php if($data->virtual_type=='charm'){?>
<div class="virtual_charm"><span><?php _e('魅力值','jinsom');?></span><span><?php echo $data->virtual_info;?></span></div>
<?php }?>

<?php if($data->virtual_type=='vip_number'){?>
<div class="virtual_vip_number"><span><?php _e('成长值','jinsom');?></span><span><?php echo $data->virtual_info;?></span></div>
<?php }?>

<?php if($data->virtual_type=='sign'){?>
<div class="virtual_sign"><span><?php _e('补签卡','jinsom');?></span><span><?php echo $data->virtual_info;?>张</span></div>
<?php }?>

<?php if($data->virtual_type=='nickname'){?>
<div class="virtual_nickname"><span><?php _e('改名卡','jinsom');?></span><span><?php echo $data->virtual_info;?>张</span></div>
<?php }?>

<?php if($data->virtual_type=='faka'){?>
<div class="virtual_faka"><span><?php _e('卡密信息','jinsom');?></span><span><?php echo $data->virtual_info;?></span></div>
<?php }?>

<?php if($data->virtual_type=='download'){?>
<div class="virtual_download"><span><?php _e('提取信息','jinsom');?></span><span><?php echo $data->virtual_info;?></span></div>
<?php }?>

<?php }?>

</div>


<div class="box">

<?php 
$address_arr=unserialize($data->address);
if($address_arr&&$goods_type=='c'){
?>
<div class="address_name"><span><?php _e('收件人','jinsom');?></span><span><?php echo $address_arr['name'];?></span></div>
<div class="address_phone"><span><?php _e('手机号','jinsom');?></span><span><?php echo $address_arr['phone'];?></span></div>
<div class="address_info"><span><?php _e('收件地址','jinsom');?></span><span><?php echo $address_arr['address'];?></span></div>
<?php }?>

<?php if($data->marks&&$goods_type=='b'){
$info_arr=unserialize($data->marks);
?>
<div class="pass-info"><span><?php _e('下单信息','jinsom');?></span><span>
<?php 
for ($i=0; $i <count($info_arr) ; $i++) { 
echo '<li><span>'.$info_arr[$i]['name'].'</span>：<span>'.$info_arr[$i]['value'].'</span></li>';
}
?>
</span></div>
<?php }?>

<?php if($data->marks&&$goods_type=='c'){?>
<div class="marks"><span><?php _e('备注','jinsom');?></span><span><?php echo $data->marks;?></span></div>
<?php }?>

<div class="buyer"><span><?php _e('购买者','jinsom');?></span><span class="gray" style="text-decoration: underline;"><?php echo jinsom_nickname_link($buy_user_id);?></span></div>
<div class="pay_type"><span><?php _e('支付方式','jinsom');?></span><span class="gray"><?php echo $pay_type;?></span></div>
<div class="order_status"><span><?php _e('订单状态','jinsom');?></span><span class="gray"><?php echo $order_status_text;?></span></div>
<div class="trade_no"><span><?php _e('订单号','jinsom');?></span><span class="gray"><?php echo $trade_no;?></span></div>
<div class="time"><span><?php _e('时间','jinsom');?></span><span class="gray"><?php echo $data->time;?></span></div>
</div>

<div class="btn">
<?php if($order_status==1){?>
<span class="send opacity" onclick="jinsom_goods_order_send_form(<?php echo $trade_no;?>)"><?php _e('发货','jinsom');?></span>
<?php }?>
<span class="del opacity" onclick="jinsom_goods_order_delete(<?php echo $trade_no;?>)"><?php _e('删除','jinsom');?></span>
</div>

</div>

