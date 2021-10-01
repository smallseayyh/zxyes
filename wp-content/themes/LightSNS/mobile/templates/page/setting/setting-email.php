<?php 
require( '../../../../../../../wp-load.php');
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
$jinsom_machine_verify_appid=jinsom_get_option('jinsom_machine_verify_appid');
$user_id=$current_user->ID;
$user_info=get_userdata($user_id);
if(jinsom_is_admin($user_id)){
$author_id=$_GET['author_id'];	
}else{
$author_id=$user_id;
}
$user_info = get_userdata($author_id);
?>
<div data-page="setting-email" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<?php if(jinsom_get_option('jinsom_bind_email_on_off')&&!$user_info->user_email&&!jinsom_is_admin($user_id)){?>
<a href="#" class="link icon-only"></a>
<?php }else{?>
<a href="#" class="back link icon-only"><i class="jinsom-icon jinsom-fanhui2"></i></a>
<?php }?>
</div>
<div class="center sliding">绑定邮箱</div>
<div class="right">
<a href="#" class="link icon-only" onclick="jinsom_update_email(<?php echo $author_id;?>)">确定</a>
</div>
</div>
</div>

<div class="page-content jinsom-setting-content update-phone">


<div class="jinsom-setting-update-phone-email-input jinsom-reg-mail">
<p class="mail"><input type="text" placeholder="邮箱号" id="jinsom-mobile-update-email"></p>
<?php if(!jinsom_is_admin($current_user->ID)&&jinsom_get_option('jinsom_email_style')!='close'){?>
<p class="code">
<input type="text" placeholder="验证码" id="jinsom-mobile-update-code">

<?php if($jinsom_machine_verify_on_off&&in_array("code",$jinsom_machine_verify_use_for)){?>
<input class="jinsom-get-code jinsom-get-code-email" type="submit" value="获取验证码" id="code-4">
<?php }else{?>
<input class="jinsom-get-code jinsom-get-code-email" type="submit" value="获取验证码" onclick="jinsom_get_code(120,'email','','');">
<?php }?>

</p>
<?php }?>
<?php if(jinsom_is_admin($current_user->ID)){?>
<p class="tips">你是管理团队，可直接修改邮箱</p>
<?php }?>
</div>

</div>       

