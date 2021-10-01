<?php 
//vip会员中心
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$theme_url=get_template_directory_uri();
$vip_number_everyday=(int)jinsom_get_option('jinsom_vip_number_everyday');
if(is_vip($user_id)){
$class="vip";	
$discount=jinsom_vip_discount($user_id);
if($discount>0&&$discount<1){
$discount=$discount*10;	
$discount='<span>'.sprintf(__( '%s折','jinsom'),$discount).'</span>';
}else if($discount<=0){
$discount='<span>'.__('免费','jinsom').'</span>';	
}else{
$discount='';	
}

}else{
$class="";
}
$date=date('Y-m-d',time());
?>
<div data-page="vip" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('会员中心','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="toolbar toolbar-bottom jinsom-vip-toolbar">
<div class="toolbar-inner">
<?php if(is_vip($user_id)){?>
<a><?php _e('续费VIP会员，让尊贵更持久！','jinsom');?></a>
<a class="link" href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/recharge-vip.php"><?php _e('马上续费','jinsom');?></a>
<?php }else{?>
<a><?php _e('开通VIP会员，立享全部特权！','jinsom');?></a>
<a class="link" href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/recharge-vip.php"><?php _e('马上开通','jinsom');?></a>
<?php }?>
</div>
</div>

<div class="page-content jinsom-vip-content">
<div class="jinsom-vip-page-header-bg"></div>

<?php if(is_vip($user_id)){?>
<div class="jinsom-vip-page-header-card vip">
<div class="icon jinsom-icon jinsom-huiyuan"></div>
<div class="logo"><?php echo jinsom_get_option('jinsom_vip_page_text');?></div>
<div class="title"><?php echo jinsom_vip_text($user_id);?><?php echo $discount;?></div>
<div class="info">
<span><?php _e('成长值','jinsom');?>：<m><?php echo (int)get_user_meta($user_id,'vip_number',true);?></m></span>
<span><?php _e('到期','jinsom');?>：<?php echo get_user_meta($user_id,'vip_time',true);?></span>
</div>
</div>
<?php }else{?>
<div class="jinsom-vip-page-header-card">
<div class="logo"><?php echo jinsom_get_option('jinsom_vip_page_text');?></div>
<div class="title"><?php _e('普通会员','jinsom');?></div>
<div class="info">
<span><?php _e('开通会员，尊享会员特权','jinsom');?></span>
<a class="link" href="<?php echo $theme_url;?>/mobile/templates/page/mywallet/recharge-vip.php"><?php _e('开通会员','jinsom');?></a>
</div>
</div>
<?php }?>

<?php if(is_vip($user_id)&&$vip_number_everyday){?>
<?php if(get_user_meta($user_id,'get_vip_number',true)!=$date){?>
<div class="jinsom-vip-page-get-number" onclick="jinsom_get_vip_number(this)"><?php _e('领取成长值','jinsom');?><span>+<?php echo $vip_number_everyday;?> <i class="jinsom-icon jinsom-chengchangzhi"></i></span></div>
<?php }else{?>
<div class="jinsom-vip-page-get-number had"><?php _e('今日已领取成长值','jinsom');?></div>
<?php }?>
<?php }?>

<div class="jinsom-vip-page-info">
<div class="header"><?php _e('会员特权','jinsom');?></div>
<div class="content clear">
<li><i class="jinsom-icon jinsom-huiyuan2"></i><p><?php _e('尊贵身份','jinsom');?></p></li>
<li><i class="jinsom-icon jinsom-nicheng"></i><p><?php _e('红色昵称','jinsom');?></p></li>
<li><i class="jinsom-icon jinsom-zhekou"></i><p><?php _e('优惠折扣','jinsom');?></p></li>
<li><i class="jinsom-icon jinsom-neirong"></i><p><?php _e('专属内容','jinsom');?></p></li>
<li><i class="jinsom-icon jinsom-gexinghua1"></i><p><?php _e('专属背景','jinsom');?></p></li>
<li><i class="jinsom-icon jinsom-guanzhu6"></i><p><?php _e('关注上限','jinsom');?></p></li>
<li><i class="jinsom-icon jinsom-huoj"></i><p><?php _e('成长加速','jinsom');?></p></li>
<li><i class="jinsom-icon jinsom-tubiao1wuguanggao"></i><p><?php _e('无广告','jinsom');?></p></li>
</div>
</div>


<?php echo do_shortcode(jinsom_get_option('jinsom_mobild_vip_page_html'));?>

</div>

</div>
</div>        