<?php
//生成邀请码
require( '../../../../../../wp-load.php' );
if(!current_user_can('level_10')){exit();}
?>
<div class="jinsom-pop-login-form">
<li class="code"><input id="jinsom-pop-invite-code-number" type="number" placeholder="请输入需要生成的数量"></li>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_admin_invite_code_add()">开始生成</span>
</div>
</div>
