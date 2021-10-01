<?php 
require( '../../../../../../wp-load.php' );
//邀请码导出
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
?>
<div class="jinsom-admin-key-add-form layui-form">
<form id="jinsom-export-invite-code-form" action="<?php echo get_template_directory_uri();?>/module/admin/action/invite-code-export.php" method="post" target="_blank">
<span>邀请码状态：</span>
<input type="radio" name="status" value="0" title="未使用" checked="">
<input type="radio" name="status" value="1" title="已使用">
</li>

<li>
<span>导出排列：</span>
<input type="radio" name="rank" value="1" title="一行一条数据" checked="">
<input type="radio" name="rank" value="2" title="英文逗号隔开">
</li>

</form>
<div class="jinsom-admin-add-key-btn opacity" onclick="jinsom_admin_invite_code_export()">导出邀请码</div>
</div>
