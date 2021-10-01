<?php 
//手机号登录
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
$jinsom_machine_verify_appid=jinsom_get_option('jinsom_machine_verify_appid');

$login_on_off=jinsom_get_option('jinsom_login_on_off');//强制登录开关
if($login_on_off&&!is_user_logged_in()){
$class="open-login-screen";
}else{
$class="back";	
}
?>
<div data-page="login-phone" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="<?php echo $class;?> link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('手机号登录','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-login-page-content" style="padding: 0;">

<div class="jinsom-login-input-form jinsom-login-phone">
<div class="jinsom-mobile-input">
<p class="phone"><input type="text" placeholder="<?php _e('手机号','jinsom');?>"></p>

<p class="code">
<input type="number" placeholder="<?php _e('验证码','jinsom');?>">

<?php if($jinsom_machine_verify_on_off&&in_array("code",$jinsom_machine_verify_use_for)){?>
<input class="jinsom-get-code jinsom-get-code-phone-login" type="submit" value="<?php _e('获取验证码','jinsom');?>" id="code-5">
<script type="text/javascript">
new TencentCaptcha(document.getElementById('code-5'),'<?php echo $jinsom_machine_verify_appid;?>',function(res){
if(res.ret === 0){jinsom_get_code(120,'phone-login',res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<input class="jinsom-get-code jinsom-get-code-phone-login" type="submit" value="<?php _e('获取验证码','jinsom');?>" onclick="jinsom_get_code(120,'phone-login','','');">
<?php }?>

</p>

</div>


<div class="jinsom-mobile-login-form-btn reg">
<?php if($jinsom_machine_verify_on_off&&in_array("reg",$jinsom_machine_verify_use_for)){?>
<div class="jinsom-login-btn" id="reg-5"><?php _e('立即登录','jinsom');?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('reg-5'),'<?php echo $jinsom_machine_verify_appid;?>',function(res){
if(res.ret === 0){jinsom_login_phone(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<div class="jinsom-login-btn" onclick="jinsom_login_phone('','')"><?php _e('立即登录','jinsom');?></div>
<?php }?>
</div>

</div>



</div>

</div>
</div>        