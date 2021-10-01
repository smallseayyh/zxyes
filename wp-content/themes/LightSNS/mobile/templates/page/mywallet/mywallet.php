<?php 
require( '../../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
$credit=(int)get_user_meta($user_id,'credit',true);
$credit_name=jinsom_get_option('jinsom_credit_name');
$jinsom_cash_on_off = jinsom_get_option('jinsom_cash_on_off');
$jinsom_transfer_on_off = jinsom_get_option('jinsom_transfer_on_off');//转账功能
$jinsom_cash_ratio=jinsom_get_option('jinsom_cash_ratio');
$lottery_on_off=jinsom_get_option('jinsom_lottery_on_off');
$jinsom_recharge_number_add=jinsom_get_option('jinsom_recharge_number_add');//金币充值套餐
$jinsom_recharge_vip_add=jinsom_get_option('jinsom_recharge_vip_add');//VIP套餐
?>
<div data-page="mywallet" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('我的钱包','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"><!-- <i class="jinsom-icon jinsom-gengduo2" style="font-size: 8vw;"></i> --></a>
</div>
</div>
</div>

<div class="page-content jinsom-mywallet-page-content">

<div class="jinsom-mywallet-form">
<div class="jinsom-mywallet-header">
<div class="number">
<span><?php echo $credit;?></span>
<div class="name"><?php echo $credit_name;?></div>
</div>
<?php if($jinsom_cash_on_off){?>
<div class="center">≈</div>
<div class="yuan">
<span><?php echo intval($credit/$jinsom_cash_ratio);?></span>
<div class="name"><?php _e('元','jinsom');?></div>
</div>
<?php }?>

</div>

<div class="jinsom-mywallet-box">
<div class="header"><?php _e('钱包服务','jinsom');?></div>
<div class="content clear">
<?php if($jinsom_recharge_number_add){?>
<li class="recharge-credit"><a class="link" href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/recharge-credit.php"><i class="jinsom-icon jinsom-chongzhi2"></i><p>充值<?php echo $credit_name;?></p></a></li>
<?php }?>
<?php if($jinsom_recharge_vip_add){?>
<li class="recharge-vip"><a class="link" href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/recharge-vip.php"><i class="jinsom-icon jinsom-kaitonghuiyuan"></i><p><?php _e('开通VIP','jinsom');?></p></a></li>
<?php }?>
<?php if($jinsom_transfer_on_off){?>
<li class="transfer" onclick="layer.open({content:'<?php _e('暂未开启','jinsom');?>',skin:'msg',time:2});"><a href="#" class="link"><i class="jinsom-icon jinsom-zhuanzhang"></i><p><?php _e('转账','jinsom');?></p></a></li>
<?php }?>
<?php if($jinsom_cash_on_off){?>
<li class="withdrawals"><a href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/cash.php" class="link"><i class="jinsom-icon jinsom-tixian2"></i><p><?php _e('提现','jinsom');?></p></a></li>
<?php }?>

</div>
</div>

<?php if($shop_url||$lottery_on_off){?>
<div class="jinsom-mywallet-box">
<div class="header"><?php _e('其他服务','jinsom');?></div>
<div class="content clear">



<!-- <li class="shop"><a href="#" target="_blank"><i class="jinsom-icon jinsom-gouwu"></i><p><?php _e('自营商城','jinsom');?></p></a></li> -->


<?php if($lottery_on_off){?>
<li class="lottery"><a href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/lottery.php" class="link"><i class="jinsom-icon jinsom-dazhuanpan"></i><p><?php _e('幸运转盘','jinsom');?></p></a></li>
<?php }?>


</div>
</div>
<?php }?>



<div class="jinsom-mywallet-box">
<div class="header"><?php _e('钱包记录','jinsom');?></div>
<div class="content clear">
<li class="income"><a href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/income.php" class="link"><i class="jinsom-icon jinsom-shouru"></i><p><?php _e('收入记录','jinsom');?></p></a></li>
<li class="outlay"><a href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/outlay.php" class="link"><i class="jinsom-icon jinsom-zhichu"></i><p><?php _e('支出记录','jinsom');?></p></a></li>

<?php if($jinsom_recharge_number_add){?>
<li class="recharge-note"><a href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/recharge-note.php" class="link"><i class="jinsom-icon jinsom-chongzhi3"></i><p><?php _e('充值记录','jinsom');?></p></a></li>
<?php }?>

<?php if($jinsom_cash_on_off){?>
<li class="withdrawals-note"><a href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/cash-note.php" class="link"><i class="jinsom-icon jinsom-shijian"></i><p><?php _e('提现记录','jinsom');?></p></a></li>
<?php }?>

<li class="exp-note"><a href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/exp.php" class="link"><i class="jinsom-icon jinsom-dengji"></i><p><?php _e('经验记录','jinsom');?></p></a></li>
</div>
</div>

</div>


</div>
</div>        