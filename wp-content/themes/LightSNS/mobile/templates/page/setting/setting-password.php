<?php 
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$author_id=$_GET['author_id'];	
}else{
$author_id=$user_id;
}
$user_info = get_userdata($author_id);

?>
<div data-page="setting-phone" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">密码</div>
<div class="right">
<a href="#" class="link icon-only" onclick="jinsom_update_password(<?php echo $author_id;?>)">修改</a>
</div>
</div>
</div>

<div class="page-content jinsom-setting-content update-phone">


<div class="jinsom-setting-update-phone-email-input">
<p class="pass"><input type="text" placeholder="新密码" id="jinsom-mobile-update-password-a"></p>
<p class="pass"><input type="text" placeholder="再输入一遍新密码" id="jinsom-mobile-update-password-b"></p>
</div>

</div>       

