<?php
//提现表单
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$credit=(int)get_user_meta($user_id,'credit',true);
$jinsom_cash_ratio=jinsom_get_option('jinsom_cash_ratio');
$jinsom_cash_mini_number=jinsom_get_option('jinsom_cash_mini_number');
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<div class="jinsom-cash-form-content">

<?php echo do_shortcode(jinsom_get_option('jinsom_cash_header_custom_html'));?>

<div class="credit">
<span>我的<?php echo $credit_name;?></span>
<m><?php echo $credit;?></m>
</div>

<div class="number">
<span><?php _e('提现数量','jinsom');?></span>
<input type="number" id="jinsom-cash-number" placeholder="<?php _e('至少','jinsom');?><?php echo $jinsom_cash_mini_number;?>">
<span><?php echo $credit_name;?></span>
<m>≈</m>
<n>0<?php _e('元','jinsom');?></n>
</div>

<div class="type">
<span><?php _e('提现类型','jinsom');?></span>
<m class="on" type="alipay"><n><i class="jinsom-icon jinsom-dui"></i></n><?php _e('支付宝','jinsom');?></m>
<m type="wechat"><n><i class="jinsom-icon jinsom-dui"></i></n><?php _e('微信','jinsom');?></m>
</div>

<div class="name">
<span><?php _e('收款姓名','jinsom');?></span>
<input type="text" id="jinsom-cash-name" placeholder="<?php _e('真实的收款姓名','jinsom');?>" value="<?php echo get_user_meta($user_id,'cash_name',true);?>">
</div>

<div class="alipay-phone">
<span><?php _e('收款帐号','jinsom');?></span>
<input type="text" id="jinsom-cash-alipay-phone" placeholder="<?php _e('手机号/邮箱','jinsom');?>" value="<?php echo get_user_meta($user_id,'alipay',true);?>">
</div>

<div class="wechat-phone" style="display: none;">
<span><?php _e('收款帐号','jinsom');?></span>
<input type="number" id="jinsom-cash-wechat-phone" placeholder="<?php _e('手机号（需微信开启接收转账）','jinsom');?>" value="<?php echo get_user_meta($user_id,'cash_wechat_phone',true);?>">
</div>

<div class="jinsom-cash-btn opacity" onclick="jinsom_cash()"><?php _e('提交审核','jinsom');?></div>

</div>

<script type="text/javascript">
$('.jinsom-cash-form-content .type>m').click(function(){
$(this).addClass('on').siblings().removeClass('on');
if($(this).attr('type')=='alipay'){
$('.jinsom-cash-form-content .alipay-phone').show();
$('.jinsom-cash-form-content .wechat-phone').hide();
}else{
$('.jinsom-cash-form-content .alipay-phone').hide();
$('.jinsom-cash-form-content .wechat-phone').show();	
}
});
$("#jinsom-cash-number").bind("input propertychange",function(){
number=Math.floor($(this).val()/<?php echo $jinsom_cash_ratio;?>);
$('.jinsom-cash-form-content .number n').text(number+'元');
});
</script>