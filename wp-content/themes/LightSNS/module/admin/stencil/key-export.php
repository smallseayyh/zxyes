<?php 
require( '../../../../../../wp-load.php' );
//卡密导出
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
?>
<div class="jinsom-admin-key-add-form layui-form">
<form id="jinsom-export-key-form" action="<?php echo get_template_directory_uri();?>/module/admin/action/key-export.php" method="post" target="_blank">
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
<input type="radio" name="status" value="2" title="全部" checked="">
<input type="radio" name="status" value="0" title="未使用">
<input type="radio" name="status" value="1" title="已使用">
</li>

<li>
<span>卡密面值：</span>
<input type="radio" name="number" value="all" title="全部面值" checked="" lay-filter="jinsom-custom-key-number">
<input type="radio" name="number" value="custom" title="指定面值" lay-filter="jinsom-custom-key-number">
<input type="number" name="custom_number" value="100" id="jinsom-custom-key-number">
</li>

<li>
<span>导出排列：</span>
<input type="radio" name="rank" value="1" title="一行一条数据" checked="">
<input type="radio" name="rank" value="2" title="英文逗号隔开">
</li>

</form>
<div class="jinsom-admin-add-key-btn opacity" onclick="jinsom_admin_key_export()">导出卡密</div>
</div>
