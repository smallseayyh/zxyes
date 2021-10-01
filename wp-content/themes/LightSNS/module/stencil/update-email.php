<?php
//修改用户邮箱表单
require( '../../../../../wp-load.php' );
if(jinsom_is_admin($current_user->ID)){
$author_id=$_POST['author_id'];
}else{
$author_id=	$current_user->ID;
}
$email=$_POST['email'];
?>
<div class="jinsom-pop-login-form">
<li class="mail"><input id="jinsom-pop-mail" type="text" placeholder="<?php _e('邮箱','jinsom');?>" value="<?php echo $email;?>"></li>
<?php if(!jinsom_is_admin($current_user->ID)&&jinsom_get_option('jinsom_email_style')!='close'){?>
<li class="code">
<input id="jinsom-pop-code" type="text" placeholder="<?php _e('验证码','jinsom');?>">

<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("code",jinsom_get_option('jinsom_machine_verify_use_for'))){?>
<input value="<?php _e('获取验证码','jinsom');?>" type="submit" class="jinsom-get-code opacity" id="code-3">
<script type="text/javascript">
new TencentCaptcha(document.getElementById('code-3'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_get_code(120,'email',res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<input value="<?php _e('获取验证码','jinsom');?>" type="submit" class="jinsom-get-code opacity" onclick="jinsom_get_code(120,'email','','');">
<?php }?>


</li>
<?php }?>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_update_mail(<?php echo $author_id;?>);"><?php _e('确定','jinsom');?></span>
</div>
</div>
