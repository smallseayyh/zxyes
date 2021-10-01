<?php 
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
$recharge_number_add = jinsom_get_option('jinsom_recharge_number_add');
$jinsom_pay_add=jinsom_get_option('jinsom_pay_add');
$openid=get_user_meta($user_id,'weixin_uid',true);
?>
<div data-page="recharge-credit" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('充值','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-recharge-content">
<div class="jinsom-recharge-number clear">


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
echo jinsom_empty('请在后台-我的钱包-金币充值-预设充值套餐-配置充值套餐');
}
?>

</div>

<div class="jinsom-recharge-tips"><?php _e('支付方式','jinsom');?></div>


<div class="jinsom-recharge-type clear">
<?php 
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
<div class="jinsom-recharge-btn" onclick="jinsom_recharge('credit');"><?php _e('立即充值','jinsom');?></div>
<div class="jinsom-recharge-custom-html"><?php echo do_shortcode(jinsom_get_option('jinsom_mobile_recharge_footer_custom_html'));?></div>
<form id="jinsom-credit-recharge-form" method="get">
<input type="hidden" name="WIDtotal_fee" value="<?php echo $one_price;?>" id="jinsom-credit-recharge-number">
<input name="WIDsubject" type="hidden" value="<?php echo jinsom_get_option('jinsom_credit_name');?>充值">
<input name="WIDout_trade_no" type="hidden" value="<?php echo time().rand(100,999);?>">
<input name="openid" type="hidden" value="<?php echo $openid;?>">
</form>
</div>
</div>        