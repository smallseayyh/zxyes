<?php
//弹窗邮箱注册表单
require( '../../../../../../wp-load.php' );
$jinsom_reg_doc_url = jinsom_get_option('jinsom_reg_doc_url');
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
?>
<div class="jinsom-pop-login-form">
<li class="username"><input id="jinsom-pop-username" type="text" placeholder="<?php _e('昵称','jinsom');?>"></li>
<li class="mail"><input id="jinsom-pop-mail" type="text" placeholder="<?php _e('邮箱号','jinsom');?>"></li>
<?php if(jinsom_get_option('jinsom_email_style')!='close'&&jinsom_get_option('jinsom_reg_email_code_on_off')){?>
<li class="code">
<input id="jinsom-pop-code" type="text" placeholder="<?php _e('验证码','jinsom');?>">

<?php if($jinsom_machine_verify_on_off&&in_array("code",$jinsom_machine_verify_use_for)){?>
<input value="<?php _e('获取验证码','jinsom');?>" type="submit" class="jinsom-get-code opacity" id="code-1">
<script type="text/javascript">
new TencentCaptcha(document.getElementById('code-1'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_get_code(120,'email',res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<input value="<?php _e('获取验证码','jinsom');?>" type="submit" class="jinsom-get-code opacity" onclick="jinsom_get_code(120,'email','','');">
<?php }?>


</li>
<?php }?>
<li class="pass"><input id="jinsom-pop-password" type="text" placeholder="<?php _e('设置你的密码','jinsom');?>"></input></li>
<div class="jinsom-reg-doc">
<input type="checkbox" id="jinsom-reg-doc">
<span><?php _e('同意','jinsom');?>
<?php
$jinsom_reg_doc_add=jinsom_get_option('jinsom_reg_doc_add');
if($jinsom_reg_doc_add){
foreach ($jinsom_reg_doc_add as $data) {
echo '<a href="'.$data['url'].'" target="_blank">《'.$data['name'].'》</a>';
}
}
?>
</span>
</div>
<div class="jinsom-login-btn">

<?php if($jinsom_machine_verify_on_off&&in_array("reg",$jinsom_machine_verify_use_for)){?>
<span class="reg opacity" id="reg-1"><?php _e('注册','jinsom');?></span>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('reg-1'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_pop_reg_mail(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<span class="reg opacity" onclick="jinsom_pop_reg_mail('','');"><?php _e('注册','jinsom');?></span>
<?php }?>

<span class="login opacity" onclick="jinsom_pop_login_style();"><?php _e('登录','jinsom');?></span>
</div>
</div>
