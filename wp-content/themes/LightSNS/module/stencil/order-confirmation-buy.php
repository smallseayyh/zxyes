<?php
//订单确定--之后再付款
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$jinsom_pay_add=jinsom_get_option('jinsom_pay_add');
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$trade_no=(int)$_POST['trade_no'];
$order_type=strip_tags($_POST['type']);
$order_data=$wpdb->get_results("SELECT * FROM $table_name WHERE trade_no = $trade_no limit 1;");
foreach ($order_data as $data) {
$select_arr=unserialize($data->select_info);
$number=$data->number;
$post_id=$data->post_id;
$price_show=$data->pay_price;//显示的支付价格
$price_type=$data->price_type;
$goods_type=$data->goods_type;
}



if($price_type=='rmb'||$goods_type=='d'){
$price_icon='<t class="yuan">￥</t>';
}else{
$price_icon='<m class="jinsom-icon jinsom-jinbi"></m>';	
}
?>
<div class="jinsom-goods-order-confirmation-content">
<div class="title"><?php echo get_the_title($post_id);?></div>
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

<?php if($order_type=='read'){
$address_arr=unserialize($data->address);
if($address_arr&&$goods_type=='c'){
?>
<div class="address_name"><span><?php _e('收件人','jinsom');?></span><span><?php echo $address_arr['name'];?></span></div>
<div class="address_phone"><span><?php _e('手机号','jinsom');?></span><span><?php echo $address_arr['phone'];?></span></div>
<div class="address_info"><span><?php _e('收件地址','jinsom');?></span><span><?php echo $address_arr['address'];?></span></div>
<?php }?>

<?php if($data->address_order){?>
<div class="address_order"><span><?php _e('发货信息','jinsom');?></span><span><?php echo $data->address_order;?></span></div>
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

<div class="trade_no"><span><?php _e('订单号','jinsom');?></span><span><?php echo $trade_no;?></span></div>
<div class="time"><span><?php _e('时间','jinsom');?></span><span><?php echo $data->time;?></span></div>
<?php }?>
</div>




<?php if($order_type=='pay'){?>

<div class="jinsom-credit-recharge-type clear">
<p><?php _e('支付方式','jinsom');?></p>
<?php 
if($jinsom_pay_add){
foreach($jinsom_pay_add as $data) {
$type=$data['jinsom_pay_add_type'];
if($type=='alipay_pc'||$type=='alipay_code'||$type=='epay_alipay'||$type=='mapay_alipay'){
echo '
<li class="alipay" data="'.$type.'">
<n><i class="jinsom-icon jinsom-dui"></i></n>
<i class="jinsom-icon jinsom-zhifubaozhifu"></i><span>'.$data['name'].'</span>
</li>'; 
}else if($type=='wechatpay_pc'||$type=='xunhupay_wechat_pc'||$type=='epay_wechatpay'||$type=='mapay_wechatpay'){    
echo '
<li class="wechat" data="'.$type.'">
<n><i class="jinsom-icon jinsom-dui"></i></n>
<i class="jinsom-icon jinsom-weixinzhifu"></i><span>'.$data['name'].'</span>
</li>';  
}
}
}else{
echo jinsom_empty('请在后台-支付充值-支付配置-添加支付选项');
}

?>
</div>

<div class="btn buy opacity"><?php _e('马上支付','jinsom');?></div>
<?php }?>


</div>



<?php if($order_type=='pay'){?>
<script type="text/javascript">
//付款方式
$('.jinsom-credit-recharge-type li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
});
$('.jinsom-goods-order-confirmation-content .btn.buy').click(function(){
pay_type=$('.jinsom-credit-recharge-type li.on').attr('data');
if(!pay_type){
layer.msg('请选择支付方式！');
return false;	
}
jinsom_goods_order_pay(pay_type,<?php echo $trade_no;?>);//发起订单支付

});
</script>
<?php }?>