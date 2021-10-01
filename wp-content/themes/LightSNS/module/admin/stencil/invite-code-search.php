<?php
//搜索邀请码
require( '../../../../../../wp-load.php' );
if(!current_user_can('level_10')){exit();}
?>
<div class="jinsom-pop-login-form">
<li class="code"><input id="jinsom-pop-invite-code" type="text" placeholder="请输入邀请码"></li>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_admin_invite_code_search()">开始查询</span>
</div>
</div>
