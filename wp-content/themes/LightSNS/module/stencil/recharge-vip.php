<?php
//开通会员表单
require( '../../../../../wp-load.php' );
$credit_name=jinsom_get_option('jinsom_credit_name');
$jinsom_recharge_vip_add = jinsom_get_option('jinsom_recharge_vip_add');
$jinsom_pay_add=jinsom_get_option('jinsom_pay_add');
?>
<div class="jinsom-credit-recharge-content">
<?php echo do_shortcode(jinsom_get_option('jinsom_recharge_vip_header_html'));?>
<div class="jinsom-credit-recharge-number vip clear">

<?php 
if($jinsom_recharge_vip_add){
$i=1;
foreach ($jinsom_recharge_vip_add as $data) {
$time=$data['time'];
if($time>=120){
$time=__('永久','jinsom');
}else{
if($time<1){
$time=(int)($data['time']*30).__('天','jinsom');
}else{
$time=$data['time'].__('个月','jinsom');	
}
}
if($i==1){
$one_price=$data['rmb_price'];
echo '
<li class="on" data="'.$data['time'].'">
<n><i class="jinsom-icon jinsom-dui"></i></n>
<div class="top">
<span class="jinsom-icon jinsom-vip2"></span><i>'.$time.'</i>
</div>
<div class="bottom" rmb_price="'.$data['rmb_price'].'" credit_price="'.$data['credit_price'].'"><m>'.$data['rmb_price'].'</m> <i>'.__('元','jinsom').'</i></div>
</li>';
}else{
echo '
<li data="'.$data['time'].'">
<n><i class="jinsom-icon jinsom-dui"></i></n>
<div class="top">
<span class="jinsom-icon jinsom-vip2"></span><i>'.$time.'</i>
</div>
<div class="bottom" rmb_price="'.$data['rmb_price'].'" credit_price="'.$data['credit_price'].'"><m>'.$data['rmb_price'].'</m> <i>'.__('元','jinsom').'</i></div>
</li>';
}
$i++;
}
}else{
echo jinsom_empty('请在后台-我的钱包-开通会员-预设VIP套餐-配置VIP套餐');
}
?>


</div>

<form id="jinsom-credit-recharge-form" action="" method="get" target="_blank">
<input type="hidden" name="WIDtotal_fee" value="<?php echo $one_price;?>" id="jinsom-credit-recharge-number">
<input name="WIDsubject" type="hidden" value="<?php _e('开通会员','jinsom');?>">
<input name="WIDout_trade_no" type="hidden" value="<?php echo time().rand(100,999);?>">
</form>


<div class="jinsom-credit-recharge-type clear">
<p><?php _e('充值方式','jinsom');?>：</p>
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
}else if($type=='creditpay'){
echo '
<li class="credit" data="creditpay">
<n><i class="jinsom-icon jinsom-dui"></i></n>
<i class="jinsom-icon jinsom-jinbi"></i><span>'.$data['name'].'</span>
</li>'; 
}
}
}else{
echo jinsom_empty('请在后台-支付充值-支付配置-添加支付选项');
}

?>
</div>
<div class="jinsom-credit-recharge-btn opacity" onclick="jinsom_recharge_credit()"><?php _e('立即开通','jinsom');?></div>

</div>



<script type="text/javascript">
$('.jinsom-credit-recharge-number li').click(function() {
$(this).addClass('on').siblings().removeClass('on');
if($('.jinsom-credit-recharge-type li.on').length>0){
if($('.jinsom-credit-recharge-type li.on').attr('data')=='creditpay'){
$('#jinsom-credit-recharge-number').val($(this).children('.bottom').attr('credit_price'));	
}else{
$('#jinsom-credit-recharge-number').val($(this).children('.bottom').attr('rmb_price'));	
}
}
});	



$('.jinsom-credit-recharge-type li').click(function() {
$('#jinsom-credit-recharge-form input[name="WIDout_trade_no"]').val(new Date().getTime());
$(this).addClass('on').siblings().removeClass('on');
type=$(this).attr('data');
// $('#jinsom-recharge-type').val(type);
if(type=='creditpay'){
$('#jinsom-credit-recharge-number').val($('.jinsom-credit-recharge-number li.on').children('.bottom').attr('credit_price'));

$(".jinsom-credit-recharge-number li").each(function(){
$(this).children('.bottom').find('m').html($(this).children('.bottom').attr('credit_price'));
});
$('.jinsom-credit-recharge-number li .bottom i').html(jinsom.credit_name);
}else{
$('#jinsom-credit-recharge-number').val($('.jinsom-credit-recharge-number li.on').children('.bottom').attr('rmb_price'));	

$(".jinsom-credit-recharge-number li").each(function(){
$(this).children('.bottom').find('m').html($(this).children('.bottom').attr('rmb_price'));
});
$('.jinsom-credit-recharge-number li .bottom i').html('元');


}

});
</script>