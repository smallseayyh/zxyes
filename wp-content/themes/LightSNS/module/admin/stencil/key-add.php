<?php 
require( '../../../../../../wp-load.php' );
//卡密生成
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<div class="jinsom-admin-key-add-form layui-form">
<form id="jinsom-add-key-form">
<li>
<span>卡密类型：</span>
<input type="radio" name="type" value="credit" title="<?php echo $credit_name;?>" checked="" data="<?php echo $credit_name;?>" lay-filter="add-key">
<input type="radio" name="type" value="vip" title="会员卡" lay-filter="add-key" data="天">
<input type="radio" name="type" value="exp" title="经验卡" lay-filter="add-key" data="经验">
<input type="radio" name="type" value="sign" title="补签卡" lay-filter="add-key" data="张">
<input type="radio" name="type" value="nickname" title="改名卡" lay-filter="add-key" data="张">
<input type="radio" name="type" value="vip_number" title="成长值" lay-filter="add-key" data="成长值">
</li>
<li class="number">
<span>卡密面值：</span>
<input type="number" name="number">
<i><?php echo $credit_name;?></i>
</li>
<li>
<span>生成数量：</span>
<input type="number" name="add-number" value="100">
</li>
<li>
<span>有效期：</span>
<input type="text" name="expiry" value="2023-1-1" id="jinsom-key-expiry">
</li>
</form>
<div class="jinsom-admin-add-key-btn opacity" onclick="jinsom_admin_key_add()">生成卡密</div>
</div>
