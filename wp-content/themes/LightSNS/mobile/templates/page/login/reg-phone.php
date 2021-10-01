<?php 
//手机号注册
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
<div data-page="reg-phone" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="<?php echo $class;?> link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('手机号注册','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-login-page-content" style="padding: 0;">

<div class="jinsom-login-input-form jinsom-reg-phone">
<div class="jinsom-mobile-input">
<p class="name"><input type="text" placeholder="<?php _e('昵称','jinsom');?>"></p>
<p class="phone"><input type="text" placeholder="<?php _e('手机号','jinsom');?>"></p>
<?php if(jinsom_get_option('jinsom_sms_style')!='close'){?>
<p class="code">
<input type="number" placeholder="<?php _e('验证码','jinsom');?>">

<?php if($jinsom_machine_verify_on_off&&in_array("code",$jinsom_machine_verify_use_for)){?>
<input class="jinsom-get-code jinsom-get-code-phone" type="submit" value="<?php _e('获取验证码','jinsom');?>" id="code-1">
<?php }else{?>
<input class="jinsom-get-code jinsom-get-code-phone" type="submit" value="<?php _e('获取验证码','jinsom');?>" onclick="jinsom_get_code(120,'phone','','');">
<?php }?>

</p>
<?php }?>
<p class="pass"><input type="text" placeholder="<?php _e('设置你的密码','jinsom');?>"></p>
<div class="jinsom-reg-doc">
<input type="checkbox">
<span><?php _e('同意','jinsom');?>
<?php
$jinsom_reg_doc_add=jinsom_get_option('jinsom_reg_doc_add');
if($jinsom_reg_doc_add){
foreach ($jinsom_reg_doc_add as $data) {
echo '<a onclick=\'jinsom_iframe("'.$data['url'].'")\'>《'.$data['name'].'》</a>';
}
}
?>
</span>
</div>
</div>


<div class="jinsom-mobile-login-form-btn reg">
<?php if($jinsom_machine_verify_on_off&&in_array("reg",$jinsom_machine_verify_use_for)){?>
<div class="jinsom-login-btn" id="reg-1"><?php _e('注册','jinsom');?></div>
<?php }else{?>
<div class="jinsom-login-btn" onclick="jinsom_pop_reg_phone('','')"><?php _e('注册','jinsom');?></div>
<?php }?>
</div>

</div>


</div>

</div>
</div>        