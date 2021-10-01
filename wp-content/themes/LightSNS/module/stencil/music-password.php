<?php
//输入密码  音乐动态
require( '../../../../../wp-load.php' );

$post_id=$_POST['post_id'];
?>
<div class="jinsom-pop-login-form">
<li class="pass"><input id="jinsom-pop-music-password" type="text" placeholder="<?php _e('输入密码','jinsom');?>" ></li>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_get_password_posts(<?php echo $post_id;?>,'pop',this);"><?php _e('确定密码','jinsom');?></span>
</div>
</div>
