<?php 
//卡密兑换
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="key-recharge" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $_GET['navbar_name'];?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-key-recharge-content">
<input type="text" id="jinsom-pop-key" placeholder="<?php echo jinsom_get_option('jinsom_pay_key_placeholder');?>">
<div class="btn" onclick="jinsom_keypay()"><?php echo jinsom_get_option('jinsom_pay_key_btn_text');?></div>
<div class="info"><?php echo do_shortcode(jinsom_get_option('jinsom_mobile_key_recharge_custom_html'));?></div>
</div>
</div>        