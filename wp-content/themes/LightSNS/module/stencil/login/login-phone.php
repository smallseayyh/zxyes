<?php
//手机号登录
require( '../../../../../../wp-load.php' );
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
?>
<div class="jinsom-pop-login-form">
<li class="phone"><input placeholder="<?php _e('手机号','jinsom');?>" id="jinsom-pop-phone" type="text"></input></li>
<?php if(jinsom_get_option('jinsom_sms_style')!='close'){?>
<li class="code">
<input id="jinsom-pop-code" type="text" placeholder="<?php _e('验证码','jinsom');?>">

<?php if($jinsom_machine_verify_on_off&&in_array("code",$jinsom_machine_verify_use_for)){?>
<input value="<?php _e('获取验证码','jinsom');?>" type="submit" class="jinsom-get-code opacity" id="code-2">
<script type="text/javascript">
new TencentCaptcha(document.getElementById('code-2'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_get_code(120,'phone-login',res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<input value="<?php _e('获取验证码','jinsom');?>" type="submit" class="jinsom-get-code opacity" onclick="jinsom_get_code(120,'phone-login','','');">
<?php }?>

</li>
<?php }?>
<div class="jinsom-login-btn">
<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("login",jinsom_get_option('jinsom_machine_verify_use_for'))){?>
<span class="login opacity" id="login-2"><?php _e('登录','jinsom');?></span>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('login-2'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_pop_login_phone(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<span class="login opacity" onclick="jinsom_pop_login_phone('','')"><?php _e('登录','jinsom');?></span>
<?php }?>	
<span class="reg opacity" onclick="jinsom_login_form('注册帐号','reg-style',400)"><?php _e('注册','jinsom');?></span>
</div>
</div>