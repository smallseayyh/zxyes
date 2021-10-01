<?php 
require( '../../../../../../wp-load.php' );
//邀请码删除
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
?>
<div class="jinsom-admin-key-add-form layui-form">
<form id="jinsom-delete-invite-code-form">
<li>
<span>状态：</span>
<input type="radio" name="status" value="1" title="已使用" checked="">
<input type="radio" name="status" value="0" title="未使用">
</li>

</form>
<div class="jinsom-admin-add-key-btn opacity" onclick="jinsom_admin_invite_code_delete()">删除邀请码</div>
</div>
