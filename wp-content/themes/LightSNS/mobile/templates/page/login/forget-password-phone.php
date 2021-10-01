<?php 
//忘记密码-手机号
require( '../../../../../../../wp-load.php');
$user_id=(int)$_GET['user_id'];
$username=strip_tags($_GET['username']);
$phone=get_user_meta($user_id,'phone',true);
$rest_phone=substr($phone,4,3); 
$new_phone=str_replace($rest_phone,str_repeat('*',strlen($rest_phone)),$phone);


$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
$jinsom_machine_verify_appid=jinsom_get_option('jinsom_machine_verify_appid');
?>
<div data-page="forget-password-phone" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('找回密码','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-login-page-content" style="padding: 0;">
<?php if($username==$phone){?>
<div class="jinsom-login-input-form jinsom-forget-password-phone" user_id="<?php echo $user_id;?>">
<div class="jinsom-mobile-input">
<p class="phone"><input type="text" value="<?php echo $new_phone;?>" disabled></p>
<?php if(jinsom_get_option('jinsom_sms_style')!='close'){?>
<p class="code">
<input type="number" placeholder="<?php _e('验证码','jinsom');?>">

<?php if($jinsom_machine_verify_on_off&&in_array("code",$jinsom_machine_verify_use_for)){?>
<input class="jinsom-get-code jinsom-get-code-pass-phone" type="submit" value="<?php _e('获取验证码','jinsom');?>" id="code-8">
<?php }else{?>
<input class="jinsom-get-code jinsom-get-code-pass-phone" type="submit" value="<?php _e('获取验证码','jinsom');?>" onclick="jinsom_get_code(120,'pass-phone','','');">
<?php }?>

</p>
<?php }?>
<p class="pass"><input type="text" placeholder="<?php _e('新的密码','jinsom');?>"></p>
</div>


<div class="jinsom-mobile-login-form-btn reg">
<div class="jinsom-login-btn" onclick="jinsom_forget_password_last('phone',<?php echo $user_id;?>)"><?php _e('更新密码','jinsom');?></div>
</div>

</div>
<?php }?>

</div>

</div>
</div>        