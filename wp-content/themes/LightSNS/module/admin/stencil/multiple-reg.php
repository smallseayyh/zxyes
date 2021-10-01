<?php 
//批量注册
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
?>

<div class="jinsom-multiple-reg-form layui-form">
<div class="lable">
<p>批量注册的用户名：</p>
<p>注册结果显示：<span onclick="jinsom_multiple_userdata_form()">复制用户ID</span></p>
</div>
<div class="username">
<textarea class="jinsom-multiple-reg-username" placeholder="张三,李四,王五,jinsom,小明,小红"></textarea>
<div class="show">
</div>
</div>
<div class="default">
<span>默认密码：<input type="text" class="jinsom-multiple-reg-password" placeholder="默认是12345678"></span>
<span>随机城市：<input type="checkbox" lay-skin="switch" lay-text="开|关" class="jinsom-multiple-city"></span>
<span>随机性别：<input type="checkbox" lay-skin="switch" lay-text="开|关" class="jinsom-multiple-sex"></span>
</div>
<input type="hidden" class="jinsom-multiple-userdata">
<div class="btn opacity" onclick="jinsom_multiple_reg()"><i class="fa fa-superpowers"></i> 开始注册</div>
</div>