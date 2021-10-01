<?php 
require( '../../../../../../wp-load.php' );
//卡密删除
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
?>
<div class="jinsom-admin-key-add-form layui-form">
<form id="jinsom-delete-key-form">
<li>
<span>卡密类型：</span>
<input type="radio" name="type" value="all" title="全部" checked="">
<input type="radio" name="type" value="credit" title="<?php echo jinsom_get_option('jinsom_credit_name');?>卡">
<input type="radio" name="type" value="vip" title="会员卡">
<input type="radio" name="type" value="exp" title="经验卡">
<input type="radio" name="type" value="sign" title="补签卡">
<input type="radio" name="type" value="nickname" title="改名卡">
<input type="radio" name="type" value="vip_number" title="成长值">
</li>
<li>
<span>卡密状态：</span>
<input type="radio" name="status" value="1" title="已使用" checked="">
<input type="radio" name="status" value="0" title="未使用">
<input type="radio" name="status" value="3" title="过期">
</li>


</form>
<div class="jinsom-admin-add-key-btn opacity" onclick="jinsom_admin_key_delete()">删除卡密</div>
</div>
