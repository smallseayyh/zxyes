<?php
//忘记密码表单
require( '../../../../../wp-load.php' );

//第一步
if(isset($_POST['type'])&&$_POST['type']==1){?>
<div class="jinsom-pop-login-form">
<li class="username">
<input placeholder="<?php _e('手机号/邮箱','jinsom');?>" id="jinsom-pop-username" type="text">
</li>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_get_password_two_form();"><?php _e('下一步','jinsom');?></span>
</div>
</div>
<?php }?>

<?php 
if(isset($_POST['type'])&&$_POST['type']==2){
$user_id=$_POST['user_id'];
$username=$_POST['username'];
$user_info = get_userdata($user_id);
$question=$user_info->question;
$answer=$user_info->answer;
$phone=$user_info->phone;
$email=$user_info->user_email;
?>

<div class="layui-form">
<div class="jinsom-pop-login-form">
<?php 
if(!$email||jinsom_get_option('jinsom_email_style')=='close'){
echo '<input type="radio" title="邮箱" disabled="">';
}else{
echo '<input type="radio" name="style" value="email" title="'.__('邮箱','jinsom').'" checked="">';
}

if(!$phone){
echo '<input type="radio" title="'.__('手机号','jinsom').'" disabled="">';
}else{
echo '<input type="radio" name="style" value="phone" title="'.__('手机号','jinsom').'">';
}

if($question==''||$answer==''){
echo '<input type="radio" title="'.__('密保','jinsom').'" disabled="">';
}else{
echo '<input type="radio" name="style" value="question" title="'.__('密保','jinsom').'">';
}
?>

<div class="jinsom-get-password-tips"><?php _e('若以上方式不可用，请尝试联系管理员','jinsom');?></div>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_get_password_three_form('<?php echo $user_id;?>');"><?php _e('下一步','jinsom');?></span>
</div>
</div>
</div>
<?php }?>

<?php 
if(isset($_POST['type'])&&$_POST['type']==3){
$user_id=$_POST['user_id'];
$style=$_POST['style'];
$user_info = get_userdata($user_id);
$mail = $user_info->user_email;
$phone=get_user_meta($user_id,'phone',true);
$rest_mail = substr($mail, 2, 3); 
$rest_phone = substr($phone, 4, 3); 
$new_mail = str_replace($rest_mail, str_repeat('*', strlen($rest_mail)),$mail);
$new_phone=str_replace($rest_phone, str_repeat('*', strlen($rest_phone)),$phone);
$password_min = jinsom_get_option('jinsom_reg_password_min');
$password_max = jinsom_get_option('jinsom_reg_password_max');
$answer=get_user_meta($user_id,'question',true);
?>

<?php if($style=='email'){?>
<div class="jinsom-pop-login-form">
<li class="mail"><input style="cursor:not-allowed;" value="<?php echo $new_mail;?>" disabled></li>
<li class="code">
<input id="jinsom-pop-code" type="text" placeholder="<?php _e('验证码','jinsom');?>">

<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("code",jinsom_get_option('jinsom_machine_verify_use_for'))){?>
<input value="<?php _e('获取验证码','jinsom');?>" type="submit" class="jinsom-get-code opacity" id="code-3">
<script type="text/javascript">
new TencentCaptcha(document.getElementById('code-3'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_get_code(120,'pass-email',res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<input value="<?php _e('获取验证码','jinsom');?>" type="submit" class="jinsom-get-code opacity" onclick="jinsom_get_code(120,'pass-email','','');">
<?php }?>

</li>
<li class="pass"><input id="jinsom-pop-password" type="text" placeholder="<?php _e('设置你的新密码','jinsom');?>"></li>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_get_password_finish_form('<?php echo $user_id;?>');"><?php _e('完成','jinsom');?></span>
<input type="hidden" id="jinsom-pop-password-style" value="<?php echo $style;?>">
<input type="hidden" id="jinsom-pop-password-id" value="<?php echo $user_id;?>">
</div>
</div>

<?php }else if($style=='phone'){?>
<div class="jinsom-pop-login-form">
<li class="phone"><input style="cursor:not-allowed;" value="<?php echo $new_phone;?>" disabled></li>
<li class="code">
<input id="jinsom-pop-code" type="text" placeholder="<?php _e('验证码','jinsom');?>">

<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("code",jinsom_get_option('jinsom_machine_verify_use_for'))){?>
<input value="<?php _e('获取验证码','jinsom');?>" type="submit" class="jinsom-get-code opacity" id="code-4">
<script type="text/javascript">
new TencentCaptcha(document.getElementById('code-4'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_get_code(120,'pass-phone',res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<input value="<?php _e('获取验证码','jinsom');?>" type="submit" class="jinsom-get-code opacity" onclick="jinsom_get_code(120,'pass-phone','','');">
<?php }?>

</li>
<li class="pass"><input id="jinsom-pop-password" type="text" placeholder="<?php _e('设置你的新密码','jinsom');?>"></li>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_get_password_finish_form('<?php echo $user_id;?>');"><?php _e('完成','jinsom');?></span>
<input type="hidden" id="jinsom-pop-password-style" value="<?php echo $style;?>">
<input type="hidden" id="jinsom-pop-password-id" value="<?php echo $user_id;?>">
</div>
</div>
<?php }else{?>
<div class="jinsom-pop-login-form">
<li class="question"><input style="cursor:not-allowed;" value="<?php echo $answer;?>" disabled></li>
<li class="check"><input id="jinsom-pop-code" type="text"></li>
<li class="pass"><input id="jinsom-pop-password" type="text" placeholder="<?php _e('设置你的新密码','jinsom');?>"></li>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_get_password_finish_form('<?php echo $user_id;?>');"><?php _e('完成','jinsom');?></span>
<input type="hidden" id="jinsom-pop-password-style" value="<?php echo $style;?>">
<input type="hidden" id="jinsom-pop-password-id" value="<?php echo $user_id;?>">
</div>
</div>

<?php }?>

<?php }?>