<?php 
//订单详情
require( '../../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$id=(int)$_GET['id'];
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$order_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id and ID=$id limit 1;");
foreach ($order_data as $data) {
$select_arr=unserialize($data->select_info);
$number=$data->number;
$status=$data->status;
$post_id=$data->post_id;
$price_show=$data->pay_price;//显示的支付价格
$goods_type=$data->goods_type;
$order_time=strtotime($data->time);
$trade_no=$data->trade_no;
}
$openid=get_user_meta($user_id,'weixin_uid',true);
$title=get_the_title($post_id);
?>
<div data-page="order-details" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left"><a href="#" class="back link icon-only"><i class="jinsom-icon jinsom-fanhui2"></i></a></div>
<div class="center sliding"><?php if($status==0){echo '订单支付';}else{echo'订单详情';}?></div>
<div class="right"><a href="javascript:jinsom_goods_order_delete(<?php echo $trade_no;?>)" class="link icon-only">删除</a></div>
</div>
</div>



<div class="page-content jinsom-shop-order-details-content">
<div class="jinsom-goods-order-confirmation-content">
<div class="title"><?php echo $title;?></div>
<div class="box">
<div class="price"><span><?php _e('金额','jinsom');?></span><span><t class="yuan">￥</t><?php echo $price_show;?></span></div>
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

<?php if($goods_type=='a'&&$data->virtual_info){?>

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

<?php if($data->virtual_type=='faka'||$data->virtual_type=='download'){?>
<div class="virtual_faka"><span><?php _e('商品信息','jinsom');?></span><span><?php echo $data->virtual_info;?></span></div>
<?php }?>

<?php }?>

<?php if($status==1){?>
<div class="order_status"><span><?php _e('订单状态','jinsom');?></span><span style="color:#f00;"><?php _e('发货中','jinsom');?></span></div>
<?php }?>

<?php if($status==1&&$goods_type=='c'){?>
<div class="address_order"><span><?php _e('物流信息','jinsom');?></span><span><?php if($data->address_order){echo $data->address_order;}else{echo '暂无';}?></span></div>
<?php }?>


<?php if($data->marks&&$goods_type=='b'){
$info_arr=unserialize($data->marks);
if($info_arr){
?>
<div class="pass-info"><span><?php _e('下单信息','jinsom');?></span><span>
<?php 
for ($i=0; $i <count($info_arr) ; $i++) { 
echo '<li><span>'.$info_arr[$i]['name'].'</span>：<span>'.$info_arr[$i]['value'].'</span></li>';
}
?>
</span></div>
<?php }}?>

<?php if($data->marks&&$goods_type=='c'){?>
<div class="marks"><span><?php _e('备注','jinsom');?></span><span><?php echo $data->marks;?></span></div>
<?php }?>

<div class="trade_no"><span><?php _e('订单号','jinsom');?></span><span><?php echo $data->trade_no;?></span></div>
<div class="time"><span><?php _e('时间','jinsom');?></span><span><?php echo $data->time;?></span></div>

</div>

<?php if($goods_type=='c'&&(time()-$order_time<86400)){?>
<div class="box">
<?php if($status!=0){
$address_arr=unserialize($data->address);
if($address_arr){
?>
<div class="address_name"><span><?php _e('收件人','jinsom');?></span><span><?php echo $address_arr['name'];?></span></div>
<div class="address_phone"><span><?php _e('手机号','jinsom');?></span><span><?php echo $address_arr['phone'];?></span></div>
<div class="address_info"><span><?php _e('收件地址','jinsom');?></span><span><?php echo $address_arr['address'];?></span></div>
<?php }?>
<?php }else{
echo '<div class="address-list">';
$user_address_arr=get_user_meta($user_id,'address',true);
if($user_address_arr){
echo '
<li><i class="jinsom-icon jinsom-arrow-right"></i>
<p><span>地址：</span>'.$user_address_arr[0]['address'].'</p>
<p><span>收件人：</span>'.$user_address_arr[0]['name'].'</p>
<p><span>手机号：</span>'.$user_address_arr[0]['phone'].'</p>
</li>';
$address=0;
}else{
$address='';
echo jinsom_empty('你还没有收货地址').'<div class="add-address">添加收货地址</div>';
}
echo '</div>';
}?>
</div>
<input type="hidden" id="jinsom-goods-address" value="<?php echo $address;?>">
<?php }?>




<?php if($status==0&&(time()-$order_time<86400)){?>
<div class="jinsom-recharge-tips">支付方式</div>
<div class="jinsom-recharge-type clear">
<?php 
$jinsom_pay_add=jinsom_get_option('jinsom_pay_add');
if($jinsom_pay_add){
foreach($jinsom_pay_add as $data) {
$type=$data['jinsom_pay_add_type'];
if($type=='alipay_mobile'||$type=='alipay_code'||$type=='epay_alipay'||$type=='mapay_alipay'){
echo '
<li class="alipay" data="'.$type.'">
<n><i class="jinsom-icon jinsom-dui"></i></n>
<i class="jinsom-icon jinsom-zhifubaozhifu"></i><span>'.$data['name'].'</span>
</li>'; 
}else if($type=='wechatpay_mobile'||$type=='wechatpay_mp'||$type=='xunhupay_wechat_mobile'||$type=='epay_wechatpay'||$type=='mapay_wechatpay'){    
if(is_wechat()){//微信内部不显示微信H5支付
if($type!='wechatpay_mobile'){
echo '
<li class="wechat" data="'.$type.'">
<n><i class="jinsom-icon jinsom-dui"></i></n>
<i class="jinsom-icon jinsom-weixinzhifu"></i><span>'.$data['name'].'</span>
</li>';
}
}else{//普通浏览器不显示微信公众号支付
if($type!='wechatpay_mp'){
echo '
<li class="wechat" data="'.$type.'">
<n><i class="jinsom-icon jinsom-dui"></i></n>
<i class="jinsom-icon jinsom-weixinzhifu"></i><span>'.$data['name'].'</span>
</li>';
}
}  
}
}
}else{
echo jinsom_empty('请在后台-支付充值-支付配置-添加支付选项');
}

?>
</div>
<input type="hidden" id="jinsom-recharge-type">
<div class="btn buy opacity" onclick="jinsom_recharge_goods()"><?php _e('马上支付','jinsom');?></div>
<form id="jinsom-goods-recharge-form" method="get">
<input name="trade_no" type="hidden" value="<?php echo $trade_no;?>">
<input name="openid" type="hidden" value="<?php echo $openid;?>">
</form>
<?php }
if($status==0&&(time()-$order_time>86400)){
echo '<div class="jinsom-order-details-tips">'.__('该订单已经超时失效，请删除后重新下单','jinsom').'</div>';
}?>

<?php if($status==2){?>
<div class="btn" onclick="layer.open({content:'暂未开启！',skin:'msg',time:2});"><?php _e('马上评价','jinsom');?></div>
<?php }?>

</div>
</div>

</div>   

