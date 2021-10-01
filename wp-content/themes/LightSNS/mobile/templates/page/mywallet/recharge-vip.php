<?php 
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
$jinsom_recharge_vip_add = jinsom_get_option('jinsom_recharge_vip_add');
$jinsom_pay_add=jinsom_get_option('jinsom_pay_add');
$openid=get_user_meta($user_id,'weixin_uid',true);
?>
<div data-page="recharge-vip" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('开通会员','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-recharge-content">
<div class="jinsom-recharge-number clear">


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
$time=sprintf(__( '%s个月','jinsom'),$data['time']);
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
}else if($type=='creditpay'){
echo '<li data="creditpay"><n><i class="jinsom-icon jinsom-dui"></i></n><i class="jinsom-icon jinsom-jinbi"></i><span>'.jinsom_get_option('jinsom_credit_name').'</span></li>';	
}
}
}else{
echo jinsom_empty('请在后台-支付充值-支付配置-添加支付选项');
}
?>

</div>
<input type="hidden" id="jinsom-recharge-type">
<div class="jinsom-recharge-btn" onclick="jinsom_recharge('vip');"><?php _e('立即开通','jinsom');?></div>
<div class="jinsom-recharge-custom-html"><?php echo do_shortcode(jinsom_get_option('jinsom_mobile_recharge_vip_footer_custom_html'));?></div>
<form id="jinsom-credit-recharge-form" method="get">
<input type="hidden" name="WIDtotal_fee" value="<?php echo $one_price;?>" id="jinsom-credit-recharge-number">
<input name="WIDsubject" type="hidden" value="<?php _e('开通会员','jinsom');?>">
<input name="WIDout_trade_no" type="hidden" value="<?php echo time().rand(100,999);?>">
<input name="openid" type="hidden" value="<?php echo $openid;?>">
</form>
</div>
</div>        