<?php 
//忘记密码
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
<div data-page="forget-password" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="<?php echo $class;?> link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('忘记密码','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-login-page-content" style="padding: 0;">

<div class="jinsom-login-input-form jinsom-forget-password">
<div class="jinsom-mobile-input">
<p class="name"><input type="text" placeholder="<?php _e('手机号/邮箱','jinsom');?>"></p>
</div>

<div class="jinsom-mobile-login-form-btn reg">
<div class="jinsom-login-btn" onclick="jinsom_forget_password_type_form()"><?php _e('下一步','jinsom');?></div>
</div>

</div>


</div>

</div>
</div>        