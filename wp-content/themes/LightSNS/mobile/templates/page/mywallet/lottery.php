<?php 
//大转盘
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
$jinsom_lottery_add = jinsom_get_option('jinsom_lottery_add');
$lottery_play_max=jinsom_get_option('jinsom_lottery_play_max');
$credit=(int)get_user_meta($user_id,'credit',true);
$lottery_times=(int)get_user_meta($user_id,'lottery_times',true);
$times=$lottery_play_max-$lottery_times;
$lottery_rule_post_id=jinsom_get_option('jinsom_lottery_rule_post_id');
$jinsom_lottery_min=(int)jinsom_get_option('jinsom_lottery_min');
?>
<div data-page="lottery" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('幸运转盘','jinsom');?></div>
<div class="right">
<?php if($lottery_rule_post_id){?>
<a href="<?php echo jinsom_mobile_post_url($lottery_rule_post_id);?>" class="link icon-only"><?php _e('规则','jinsom');?></a>
<?php }else{?>
<a href="#" class="link icon-only"></a>
<?php }?>
</div>
</div>
</div>

<div class="page-content jinsom-lottery-content">

<?php if($jinsom_lottery_add){?>
<div class="jinsom-lottery-container">
<div class="jinsom-lottery-arrow"><?php _e('抽奖','jinsom');?></div>
<div class="jinsom-lottery-list">
<?php 
$i=1;
$a=360/count($jinsom_lottery_add);
foreach ($jinsom_lottery_add as $lottery) {
$b=$i*$a-120;
echo '<div class="item" style="transform: rotateZ('.$b.'deg);"><div class="item-content">'.$lottery['number'].__('倍','jinsom').'</div></div>';
$i++;
}
?>
</div>
</div>
<div class="jinsom-lottery-info">
<div class="credit">我的<?php echo jinsom_get_option('jinsom_credit_name');?><span><?php echo $credit;?></span></div>
<div class="times"><?php _e('可抽次数','jinsom');?><span><?php echo $times;?></span></div>
</div>
<div class="jinsom-lottery-money">
<span><?php _e('下注','jinsom');?></span>
<input type="tel" id="jinsom-lottery-money" value="<?php echo $jinsom_lottery_min;?>">
<span class="add" data="100">+100</span>
<span class="add" data="500">+500</span>
</div>
<div class="jinsom-lottery-btn" onclick="jinsom_lottery(this)"><?php _e('开始翻倍','jinsom');?></div>

<?php }?>

</div>
</div>        