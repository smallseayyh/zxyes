<?php 
//忘记密码-邮箱
require( '../../../../../../../wp-load.php');
$user_id=(int)$_GET['user_id'];
$user_info=get_userdata($user_id);
$email=$user_info->user_email;
$username=strip_tags($_GET['username']);
$rest_email=substr($email,2,3); 
$new_email=str_replace($rest_email,str_repeat('*',strlen($rest_email)),$email);


$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
$jinsom_machine_verify_appid=jinsom_get_option('jinsom_machine_verify_appid');
?>
<div data-page="forget-password-email" class="page no-tabbar">

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
<?php if($username==$email){?>
<div class="jinsom-login-input-form jinsom-forget-password-email" user_id="<?php echo $user_id;?>">
<div class="jinsom-mobile-input">
<p class="mail"><input type="text" value="<?php echo $new_email;?>" disabled></p>

<?php if(jinsom_get_option('jinsom_email_style')!='close'){?>
<p class="code">
<input type="number" placeholder="<?php _e('验证码','jinsom');?>">

<?php if($jinsom_machine_verify_on_off&&in_array("code",$jinsom_machine_verify_use_for)){?>
<input class="jinsom-get-code jinsom-get-code-pass-email" type="submit" value="<?php _e('获取验证码','jinsom');?>" id="code-9">
<?php }else{?>
<input class="jinsom-get-code jinsom-get-code-pass-email" type="submit" value="<?php _e('获取验证码','jinsom');?>" onclick="jinsom_get_code(120,'pass-email','','');">
<?php }?>
</p>
<?php }?>

<p class="pass"><input type="text" placeholder="<?php _e('新的密码','jinsom');?>"></p>
</div>


<div class="jinsom-mobile-login-form-btn reg">
<div class="jinsom-login-btn" onclick="jinsom_forget_password_last('email',<?php echo $user_id;?>)"><?php _e('更新密码','jinsom');?></div>
</div>

</div>
<?php }?>

</div>

</div>
</div>        