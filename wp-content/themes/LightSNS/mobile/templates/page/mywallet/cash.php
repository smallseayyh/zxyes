<?php 
//提现
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
$credit=(int)get_user_meta($user_id,'credit',true);
$credit_name=jinsom_get_option('jinsom_credit_name');
$jinsom_cash_ratio=(int)jinsom_get_option('jinsom_cash_ratio');
$jinsom_cash_mini_number=jinsom_get_option('jinsom_cash_mini_number');
$jinsom_cash_poundage=jinsom_get_option('jinsom_cash_poundage');
?>
<div data-page="cash" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('提现','jinsom');?></div>
<div class="right">
<a href="<?php echo get_template_directory_uri();?>/mobile/templates/page/mywallet/cash-note.php" class="link icon-only"><?php _e('记录','jinsom');?></a>
</div>
</div>
</div>

<div class="page-content">

<div class="jinsom-cash-form-content">

<?php 
if($jinsom_cash_poundage){
echo '<div class="tips">提现手续费为'.$jinsom_cash_poundage.'%每笔，若提现失败，手续费则不退还</div>';
}
?>

<div class="credit">
<span>我的<?php echo $credit_name;?></span>
<m><?php echo $credit;?></m>
</div>

<div class="number">
<span><?php _e('提现数量','jinsom');?></span>
<input type="number" id="jinsom-cash-number" data="<?php echo $jinsom_cash_ratio;?>" placeholder="<?php _e('至少','jinsom');?><?php echo $jinsom_cash_mini_number;?>">
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

<?php echo jinsom_get_option('jinsom_mobile_cash_footer_custom_html');?>

</div>
</div>
</div>        