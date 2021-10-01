<?php
//搜索卡密
require( '../../../../../../wp-load.php' );
if(!current_user_can('level_10')){exit();}
?>
<div class="jinsom-pop-login-form">
<li class="code"><input id="jinsom-pop-key-search" type="text" placeholder="请输入卡密"></li>
<div class="jinsom-login-btn">
<span class="ok opacity" onclick="jinsom_admin_key_search()">开始查询</span>
</div>
</div>
