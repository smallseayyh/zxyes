<?php 
require( '../../../../../../wp-load.php');
?>
<div data-page="reg-phone" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">7777</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-login-form" style="background-image:url(https://open.saintic.com/api/bingPic);">

<div class="jinsom-login-form-opacity">
<div class="jinsom-login-input-form">
<div class="jinsom-mobile-input">
<p class="name"><input type="text" id="username" placeholder="<?php echo jinsom_get_option('jinsom_login_placeholder');?>"></p>
<p class="pass"><input type="password" id="password" placeholder="请输入密码"></p>
</div>
<div class="jinsom-login-btn" onclick="jinsom_login()">登 录</div>
</div>
</div><!-- opacity -->

</div>
</div>        