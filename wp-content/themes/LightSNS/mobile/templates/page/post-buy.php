<?php 
//购买付费内容
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$post_price=get_post_meta($post_id,'post_price',true);
$vip_discount=jinsom_vip_discount($user_id);
$vip_price=ceil($post_price*$vip_discount);
$buy_times=(int)get_post_meta($post_id,'buy_times',true);
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<div data-page="post-buy" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('付费购买','jinsom');?></div>
<div class="right">
<a href="#>" class="link icon-only"></a>
</div>
</div>
</div>


<div class="page-content keep-toolbar-on-scroll jinsom-post-buy-content">
<div class="jinsom-post-buy-form">
<div class="header"><i class="jinsom-icon jinsom-fufeineirong"></i></div>
<?php if(is_vip($user_id)){?>
<div class="price"><i><?php echo $vip_price;?></i><?php echo $credit_name;?></div>
<div class="vip"><?php echo jinsom_vip_pay_text($user_id);?></div>
<?php }else{?>
<div class="price"><i><?php echo $post_price;?></i><?php echo $credit_name;?></div>
<?php }?>
<div class="hadbuy">
<?php if($buy_times){
echo $buy_times.'人已经购买';
}else{
echo __('还没有人购买，快买了看看呗~','jinsom');
}?>
</div>
<div class="btn" onclick="jinsom_pay_for_visible(<?php echo $post_id;?>,this)"><?php _e('确定购买','jinsom');?></div>
</div>
<?php echo do_shortcode(jinsom_get_option('jinsom_mobile_buy_post_footer_html'));?>

</div>
</div>        