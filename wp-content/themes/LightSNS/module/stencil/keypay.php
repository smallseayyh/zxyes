<?php
//卡密兑换
require( '../../../../../wp-load.php' );
$jinsom_keypay_url = jinsom_get_option('jinsom_keypay_url');
?>
<div class="jinsom-pop-login-form">
<li class="key"style="margin-bottom: 10px;"><input id="jinsom-pop-key" type="text" placeholder="<?php echo jinsom_get_option('jinsom_pay_key_placeholder');?>"></li>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_keypay();"><?php echo jinsom_get_option('jinsom_pay_key_btn_text');?></span>
</div>
<?php echo do_shortcode(jinsom_get_option('jinsom_key_recharge_custom_html'));?>
</div>
