<?php 
require( '../../../../../wp-load.php' );
$post_id=$_POST['post_id'];
$user_id=$current_user->ID;
$price=(int)get_post_meta($post_id,'post_price',true);
$vip_discount=jinsom_vip_discount($user_id);
$vip_price=ceil($price*$vip_discount);
$post_type=get_post_meta($post_id,'post_type',true);
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<div class="jinsom-pay-form clear">
<div class="left">
<div class="icon"><i class="jinsom-icon jinsom-fufeineirong"></i></div>
<div class="time">
<span><?php _e('数量','jinsom');?>：<em><?php _e('1个','jinsom');?></em></span>
<span><?php _e('时长','jinsom');?>：<em><?php _e('永久','jinsom');?></em></span>
</div>
<div class="price">
<?php _e('售价','jinsom');?>：<?php echo '<em>'.$price.'</em>'.$credit_name; ?> 
</div>
</div>
<div class="right">
<?php if(!is_vip($user_id)){?>
<div class="top">
<p><?php _e('您在获取【付费内容】','jinsom');?></p>
<?php echo '<p>查看隐藏内容需要<span>'.$price.'</span>'.$credit_name.'</p>';?>
</div>
<p class="tips no"><a onclick="jinsom_recharge_vip_form()"><?php _e('开通VIP会员','jinsom');?></a><?php _e('立刻可享受更多优惠','jinsom');?></p>
<?php }else{?>
<div class="top">
<p style="color: #FFC107;"><?php echo jinsom_vip_pay_text($user_id);?></p>
<?php echo '<p>查看隐藏内容只需要<span>'.$vip_price.'</span>'.$credit_name.'</p>';?>
</div>
<p class="tips"><a onclick="jinsom_recharge_vip_form()"><?php _e('续费VIP会员','jinsom');?></a><?php _e('让尊贵更持久','jinsom');?></p>
<?php }?>
<div class="btn opacity"  onclick='jinsom_pay_for_visible(<?php echo $post_id;?>);'><?php _e('确定购买','jinsom');?></div>
</div>
</div>