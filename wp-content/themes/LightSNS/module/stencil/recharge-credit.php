<?php
//金币充值
require( '../../../../../wp-load.php' );
$recharge_number_add=jinsom_get_option('jinsom_recharge_number_add');
$jinsom_pay_add=jinsom_get_option('jinsom_pay_add');
$WIDout_trade_no=time().rand(100,999);
?>
<div class="jinsom-credit-recharge-content">
<?php echo do_shortcode(jinsom_get_option('jinsom_recharge_credit_header_html'));?>
<div class="jinsom-credit-recharge-number clear">

<?php 
if($recharge_number_add){
$i=1;
foreach ($recharge_number_add as $data) {
if($i==1){
$one_price=$data['price'];
echo '
<li class="on">
<n><i class="jinsom-icon jinsom-dui"></i></n>
<div class="top">
<span class="jinsom-icon jinsom-jinbi"></span><i>'.$data['number'].'</i>
</div>
<div class="bottom" data="'.$data['price'].'">'.$data['price'].' '.__('元','jinsom').'</div>
</li>';
}else{
echo '
<li>
<n><i class="jinsom-icon jinsom-dui"></i></n>
<div class="top">
<span class="jinsom-icon jinsom-jinbi"></span><i>'.$data['number'].'</i>
</div>
<div class="bottom" data="'.$data['price'].'">'.$data['price'].' '.__('元','jinsom').'</div>
</li>';
}
$i++;
}
}else{
echo jinsom_empty('请在后台-支付充值-金币充值-预设充值套餐-配置充值套餐');
}
?>


</div>

<form id="jinsom-credit-recharge-form" action="" method="get" target="_blank">
<input type="hidden" name="WIDtotal_fee" value="<?php echo $one_price;?>" id="jinsom-credit-recharge-number">
<input name="WIDsubject" type="hidden" value="<?php echo jinsom_get_option('jinsom_credit_name');?>充值">
<input name="WIDout_trade_no" type="hidden" value="<?php echo $WIDout_trade_no;?>">
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
}
}
}else{
echo jinsom_empty('请在后台-支付充值-支付配置-添加支付选项');
}

?>

</div>

<div class="jinsom-credit-recharge-btn opacity" onclick="jinsom_recharge_credit()"><?php _e('去支付','jinsom');?> <span><?php echo $one_price;?> <?php _e('元','jinsom');?></span></div>

</div>


<script type="text/javascript">
$('.jinsom-credit-recharge-number li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
$('#jinsom-credit-recharge-number').val($(this).children('.bottom').attr('data'));//同步值
$('.jinsom-credit-recharge-btn span').html($(this).children('.bottom').html());

});

$('.jinsom-credit-recharge-type li').click(function() {
$('#jinsom-credit-recharge-form input[name="WIDout_trade_no"]').val(new Date().getTime());
$(this).addClass('on').siblings().removeClass('on');
});
</script>