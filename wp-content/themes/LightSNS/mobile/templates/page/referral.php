<?php 
//推广页面
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$referral_credit=(int)get_user_meta($user_id,'referral_credit',true);
$invite_number=(int)get_user_meta($user_id,'invite_number',true);//用户总邀请人数
$today_invite_number=(int)get_user_meta($user_id,'today_invite_number',true);//今日邀请人数
$referral_recharge_yuan=(int)get_user_meta($user_id,'referral_recharge_yuan',true);//推广的用户 累计充值
$today_referral_recharge_yuan=(int)get_user_meta($user_id,'today_referral_recharge_yuan',true);//今日推广的用户累计充值
$referral_times=(int)get_user_meta($user_id,'referral_times',true);//推广链接访问次数
$referral_url=get_user_meta($user_id,'referral-url',true);
if($referral_url){
$style="style='display:block;'";
}else{
$style="style='display:none;'";
}

global $wpdb;
$datas=$wpdb->get_results("SELECT user_id FROM $wpdb->usermeta where meta_key='who' and meta_value=$user_id  ORDER BY umeta_id DESC limit 30;");


$referral_link_name=jinsom_get_option('jinsom_referral_link_name');
$url=home_url().'/?'.$referral_link_name.'='.$user_id;
?>
<div data-page="referral" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('我的推广','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"><?php //_e('奖励','jinsom');?></a>
</div>

</div>
</div>

<div class="page-content jinsom-referral-page-content">

<div class="jinsom-referral-page-main">

<div class="jinsom-referral-page-count">
<span><?php echo $referral_credit;?></span>
<p>累计推广获得<?php echo jinsom_get_option('jinsom_credit_name');?></p>
</div>
<div class="jinsom-referral-page-number">
<li><span><?php echo $invite_number;?><i>人</i></span><p><?php _e('总推广','jinsom');?></p></li>	
<li><span><?php echo $today_invite_number;?><i>人</i></span><p><?php _e('今日推广','jinsom');?></p></li>
<li><span><?php echo $referral_recharge_yuan;?><i><?php _e('元','jinsom');?></i></span><p><?php _e('总推广充值','jinsom');?></p></li>
<li><span><?php echo $today_referral_recharge_yuan;?><i><?php _e('元','jinsom');?></i></span><p><?php _e('今日推广充值','jinsom');?></p></li>
</div>



<div class="referral-times"><?php _e('今日推广链接被访问次数','jinsom');?>：<span><?php echo $referral_times;?></span></div>
<div class="jinsom-referral-url" <?php echo $style;?>><span id="jinsom-referral-url-cover"><?php echo $referral_url;?></span><m data-clipboard-target="#jinsom-referral-url-cover" id="jinsom-referral-cover"><?php _e('复制','jinsom');?></m></div>
<div class="jinsom-referral-btn">
<?php //if(!$referral_url){?>
<div class="link" onclick="jinsom_referral_url(this)"><?php _e('生成推广链接','jinsom');?></div>
<?php //}?>
<div class="playbill" onclick="jinsom_referral_playbill_page('<?php echo $url;?>')"><?php _e('生成推广海报','jinsom');?></div>
</div>


<div class="jinsom-referral-page-user-list">
<div class="title"><?php _e('我推广的用户','jinsom');?></div>
<?php if($datas){?>
<div class="header">
<span><?php _e('用户昵称','jinsom');?></span><span><?php _e('累计充值','jinsom');?></span><span><?php _e('注册时间','jinsom');?></span>
</div>
<div class="content">
<?php 
foreach ($datas as $data) {
$referral_user_id=$data->user_id;
$user_recharge_yuan=(int)get_user_meta($referral_user_id,'recharge_yuan',true);
$user_info=get_userdata($referral_user_id);
echo '<li><a href="'.jinsom_mobile_author_url($referral_user_id).'" class="link"><span>'.jinsom_avatar($referral_user_id,'40',avatar_type($referral_user_id)).jinsom_verify($referral_user_id).'<m>'.jinsom_nickname($referral_user_id).'</m></span><span>'.$user_recharge_yuan.__('元','jinsom').'</span><span>'.jinsom_timeago($user_info->user_registered).'</span></a></li>';
}

// if(count($datas)>1){
// echo '<a href="#" class="link more">'.__('查看更多','jinsom').'</a>';
// }



?>
</div>
<?php }else{echo jinsom_empty();}?>
</div>




<div class="jinsom-referral-page-user-list">
<div class="title"><?php _e('最新加入社区','jinsom');?></div>
<div class="header">
<span><?php _e('用户昵称','jinsom');?></span><span><?php _e('推广人','jinsom');?></span><span><?php _e('注册时间','jinsom');?></span>
</div>
<div class="content">
<?php 
$regs=$wpdb->get_results("SELECT ID FROM $wpdb->users ORDER BY ID DESC limit 10;");
foreach ($regs as $data) {
$reg_user_id=$data->ID;
$who=get_user_meta($reg_user_id,'who',true);
if($who){
$who=jinsom_nickname($who);
}else{
$who=__('无','jinsom');
}
$user_info=get_userdata($reg_user_id);
echo '<li><a href="'.jinsom_mobile_author_url($reg_user_id).'" class="link"><span>'.jinsom_avatar($reg_user_id,'40',avatar_type($reg_user_id)).jinsom_verify($reg_user_id).'<m>'.jinsom_nickname($reg_user_id).'</m></span><span>'.$who.'</span><span>'.jinsom_timeago($user_info->user_registered).'</span></a></li>';
}


?>
</div>
</div>



</div>



</div>

</div>
</div>      