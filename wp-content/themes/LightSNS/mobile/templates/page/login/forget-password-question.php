<?php 
require( '../../../../../../../wp-load.php');
$user_id=(int)$_GET['user_id'];
$user_info=get_userdata($user_id);
$question=$user_info->question;
$username=strip_tags($_GET['username']);
?>
<div data-page="forget-password-question" class="page no-tabbar">

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

<div class="jinsom-login-input-form jinsom-forget-password-question" user_id="<?php echo $user_id;?>">
<div class="jinsom-mobile-input">
<p class="xxx"><input type="text" value="<?php echo $question;?>" disabled></p>
<p class="code"><input type="text" placeholder="<?php _e('请输入密保答案','jinsom');?>"></p>
<p class="pass"><input type="text" placeholder="<?php _e('新的密码','jinsom');?>"></p>
</div>


<div class="jinsom-mobile-login-form-btn reg">
<div class="jinsom-login-btn" onclick="jinsom_forget_password_last('question',<?php echo $user_id;?>)"><?php _e('更新密码','jinsom');?></div>
</div>

</div>

</div>

</div>
</div>       

