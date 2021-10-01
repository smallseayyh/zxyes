<?php
//订单确定表单
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$jinsom_pay_add=jinsom_get_option('jinsom_pay_add');
$select_str=stripslashes(strip_tags($_POST['select_arr']));
$post_id=(int)$_POST['post_id'];
$number=(int)$_POST['number'];
$select_price=(int)$_POST['select_price'];
$select_price=$select_price-1;//数组下标需要-1
$select_arr=json_decode($select_str,true);//套餐--数组
$goods_data=get_post_meta($post_id,'goods_data',true);
$goods_type=$goods_data['jinsom_shop_goods_type'];//商品类型
$price_type=$goods_data['jinsom_shop_goods_price_type'];
$select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐

if($goods_type=='a'||$goods_type=='d'||!$select_change_price_add){//本站虚拟、淘宝客、没有添加价格套餐
$price=$goods_data['jinsom_shop_goods_price'];
$price_discount=$goods_data['jinsom_shop_goods_price_discount'];
}else{
$price=$select_change_price_add[0]['value_add'][$select_price]['price'];
$price_discount=$select_change_price_add[0]['value_add'][$select_price]['price_discount'];
}

if($price_discount){
$price_show=$price_discount;
}else{
$price_show=$price;
}


if($price_show<=0||!is_numeric($price_show)){
exit();
}

if($number>1){
$price_show=$number*$price_show;
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
</div>

<?php 
if($goods_data['jinsom_shop_goods_buy_tips']){
echo '<div class="tips">'.$goods_data['jinsom_shop_goods_buy_tips'].'</div>';
}
?>


<?php if($goods_type=='b'&&$goods_data['jinsom_shop_goods_buy_user_info_add']){?>
<div class="pass-info">
<p><?php _e('下单信息','jinsom');?></p>
<div class="list clear">
<?php 
foreach ($goods_data['jinsom_shop_goods_buy_user_info_add'] as $data) {
echo '<li><span>'.$data['name'].'</span><input type="text"></li>';
}
?>
</div>
</div>
<?php }?>

<?php if($goods_type=='c'){?>
<div class="marks">
<p><?php _e('备注信息','jinsom');?></p>
<textarea placeholder="<?php echo $goods_data['jinsom_shop_goods_buy_info_tips'];?>"></textarea>
</div>
<?php }?>

<?php if($goods_type=='c'){?>
<div class="address">
<div class="list">
<?php 
$user_address_arr=get_user_meta($user_id,'address',true);
if($user_address_arr){

for ($i=0; $i <count($user_address_arr) ; $i++) { 
echo '<li><m onclick="jinsom_address_del(this)">删除</m>
<div class="info">
<input type="radio" name="address" value="'.$i.'"><span>'.$user_address_arr[$i]['address'].'</span>
<p><span>收件人<n>'.$user_address_arr[$i]['name'].'</n></span><span>手机号<n>'.$user_address_arr[$i]['phone'].'</n></span></p>
</div>
</li>';
}

}else{
echo '<div class="no">你还没有地址，请点击添加</div>';
}

?>
</div>
<div class="add" onclick="jinsom_address_select_form()"><?php _e('添加地址','jinsom');?></div>
</div>
<?php }?>

<!-- <div class="优惠券"></div> -->
<div class="jinsom-credit-recharge-type clear">
<p><?php _e('支付方式','jinsom');?></p>
<?php 
if($price_type=='rmb'){
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
}else{
echo '
<li class="credit on" data="creditpay">
<n><i class="jinsom-icon jinsom-dui"></i></n>
<i class="jinsom-icon jinsom-jinbi"></i><span>'.jinsom_get_option('jinsom_credit_name').'</span>
</li>';
}

?>
</div>
<div class="btn opacity" onclick="jinsom_goods_buy(<?php echo $post_id;?>)"><?php _e('马上支付','jinsom');?></div>

</div>
<script type="text/javascript">
//选择地址
$('.jinsom-goods-order-confirmation-content .address').on('click','li .info',function(){
$(this).siblings('li').children('input').removeAttr("checked");
$(this).children('input').prop("checked",true);
});


//付款方式
$('.jinsom-credit-recharge-type li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
});

//选择地址
function jinsom_address_select_form(){
layer.load(1);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/stencil/address-select.php",
success: function(msg){
layer.closeAll('loading');
address_select_form=layer.open({
title:'添加地址',
type: 1,
fixed: false,
// offset: '100px',
skin:'jinsom-address-select-form',
area: ['700px','auto'],
resize:false,
content: msg
});
}
});	
}


//删除地址
function jinsom_address_del(obj){
number=$(obj).siblings('.info').children('input').val();
layer.confirm('你确定要删除吗？',{
btnAlign: 'c',
}, function(){
layer.load(1);
$.ajax({
type: "POST",
url:  jinsom.jinsom_ajax_url+"/action/address-add.php",
data: {number:number,type:'del'},
success: function(msg){
layer.closeAll('loading');
layer.msg(msg.msg);
if(msg.code==1){
$(obj).parent().fadeTo("slow",0.06, function(){
$(this).slideUp(0.06, function() {
$(this).remove();

if($('.jinsom-goods-order-confirmation-content .address li').length==0){
$('.jinsom-goods-order-confirmation-content .address .list').html('<div class="no">你还没有地址，请点击添加</div>');
}

});
});

}
}
});
});
}
</script>