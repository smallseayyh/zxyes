<?php
//卡密兑换
require( '../../../../../wp-load.php' );
?>
<div class="jinsom-pop-login-form">
<li class="user"><input id="jinsom-pop-nickname" type="text" placeholder="<?php _e('请输入对方的昵称','jinsom');?>"></li>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_transfer_one();"><?php _e('立即转账','jinsom');?></span>
</div>
</div>
