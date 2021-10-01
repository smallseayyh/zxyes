<?php 
$bbs_visit_power=get_term_meta($publish_bbs_id,'bbs_visit_power',true);
$bbs_visit_exp=(int)get_term_meta($publish_bbs_id,'bbs_visit_exp',true);
$visit_power_pay_price=(int)get_term_meta($publish_bbs_id,'visit_power_pay_price',true);
$bbs_name=jinsom_get_option('jinsom_bbs_name');
if($bbs_visit_power==1){?>

<div class="jinsom-bbs-no-power-tips bbs">
<div class="content">
<div class="tips"><i class="jinsom-icon jinsom-niming"></i><?php _e('该论坛只允许VIP用户访问','jinsom');?></div>
<a class="btn" onclick="jinsom_recharge_vip_type_form()"><?php _e('开通会员','jinsom');?></a>
</div>
</div>


<?php }else if($bbs_visit_power==2){?>
<div class="jinsom-bbs-no-power-tips bbs"><i class="jinsom-icon jinsom-niming"></i>该<?php echo $bbs_name;?>只允许认证用户访问</div>
<?php }else if($bbs_visit_power==3){?>
<div class="jinsom-bbs-no-power-tips bbs"><i class="jinsom-icon jinsom-niming"></i>该<?php echo $bbs_name;?>只允许管理团队访问</div>
<?php }else if($bbs_visit_power==4){?>
<div class="jinsom-bbs-no-power-tips bbs"><i class="jinsom-icon jinsom-niming"></i>该<?php echo $bbs_name;?>只允许拥有头衔的用户访问</div>
<?php }else if($bbs_visit_power==6){?>


<div class="jinsom-bbs-no-power-tips bbs">
<div class="content">
<div class="tips"><i class="jinsom-icon jinsom-niming"></i>该<?php echo $bbs_name;?>只允许经验值大于<m><?php echo $bbs_visit_exp;?></m>的用户访问<br>你当前的经验值为<m><?php echo jinsom_get_user_exp($user_id);?></m></div>
</div>
</div>


<?php }else if($bbs_visit_power==7){?>
<div class="jinsom-bbs-no-power-tips bbs"><i class="jinsom-icon jinsom-niming"></i>该<?php echo $bbs_name;?>只允许指定的用户访问</div>
<?php }else if($bbs_visit_power==8){?>

<div class="jinsom-bbs-no-power-tips bbs">
<div class="content">
<div class="tips"><i class="jinsom-icon jinsom-niming"></i>该<?php echo $bbs_name;?>只允许登录的用户访问</div>
<a class="btn open-login-screen"><?php _e('马上登录','jinsom');?></a>
</div>
</div>

<?php }else if($bbs_visit_power==9){?>
<div class="jinsom-bbs-no-power-tips bbs"><i class="jinsom-icon jinsom-niming"></i>该<?php echo $bbs_name;?>只允许指定头衔的用户访问</div>
<?php }else if($bbs_visit_power==10){?>
<div class="jinsom-bbs-no-power-tips bbs"><i class="jinsom-icon jinsom-niming"></i>该<?php echo $bbs_name;?>只允许指定认证类型的用户访问</div>
<?php }else if($bbs_visit_power==11){?>
<div class="jinsom-bbs-no-power-tips bbs">
<div class="content">
<div class="tips"><i class="jinsom-icon jinsom-niming"></i>该<?php echo $bbs_name;?>需要支付<m><?php echo $visit_power_pay_price;?></m><?php echo jinsom_get_option('jinsom_credit_name');?>才允许访问</div>
<div class="btn pay" onclick="jinsom_bbs_visit_pay(<?php echo $bbs_id;?>)"><?php _e('马上支付','jinsom');?></div>
</div>
</div>
<?php }else{?>


<div class="jinsom-bbs-no-power-tips bbs">
<div class="content">
<div class="tips"><i class="jinsom-icon jinsom-niming"></i>该<?php echo $bbs_name;?>已加密，请输入密码访问</div>
<div class="password">
<input id="jinsom-bbs-visit-psssword" type="text">
<div class="btn" onclick="jinsom_bbs_visit_password(<?php echo $bbs_id;?>)"><?php _e('马上访问','jinsom');?></div>
</div>
</div>
</div>


<?php }?>
