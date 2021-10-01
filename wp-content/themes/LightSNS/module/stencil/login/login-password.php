<?php
//密码登录
require( '../../../../../../wp-load.php' );?>
<div class="jinsom-pop-login-form">
<li class="username"><input placeholder="<?php echo jinsom_get_option('jinsom_login_placeholder');?>" id="jinsom-pop-username" type="text"></input></li>
<li class="pass"><input id="jinsom-pop-password" type="password" placeholder="<?php _e('密码','jinsom');?>"></input></li>
<div class="jinsom-login-btn">
<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("login",jinsom_get_option('jinsom_machine_verify_use_for'))){?>
<span class="login opacity" id="login-2"><?php _e('登录','jinsom');?></span>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('login-2'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_pop_login(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<span class="login opacity" onclick="jinsom_pop_login('','')"><?php _e('登录','jinsom');?></span>
<?php }?>	
<span class="reg opacity" onclick="jinsom_login_form('注册帐号','reg-style',400)"><?php _e('注册','jinsom');?></span>
</div>
</div>