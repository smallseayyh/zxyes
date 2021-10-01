<?php 
//推广说明页
require( '../../../../../wp-load.php' );
$jinsom_referral_link_times = jinsom_get_option('jinsom_referral_link_times');
$jinsom_referral_link_reg_credit = jinsom_get_option('jinsom_referral_link_reg_credit');
$jinsom_referral_link_reg_exp = jinsom_get_option('jinsom_referral_link_reg_exp');
$jinsom_referral_link_credit = jinsom_get_option('jinsom_referral_link_credit');
$jinsom_referral_link_exp = jinsom_get_option('jinsom_referral_link_exp');
?>
<div class="jinsom-referral-info-form">
<div class="jinsom-referral-info-box">
<div class="header">推广链接产生访问<span>(每次访问)</span></div>
<div class="get">
<?php 
if($jinsom_referral_link_credit){
echo '<m>+'.$jinsom_referral_link_credit.' <span class="jinsom-icon jinsom-jinbi"></span></m>';
}
if($jinsom_referral_link_exp){
echo '<m>+'.$jinsom_referral_link_exp.' <span class="jinsom-icon jinsom-jingyan"></span></m>';
}

?>
</div>
<div class="layui-text">
<ul>
<li>每天前<?php echo $jinsom_referral_link_times;?>次有效访问可获得奖励</li>
<li>同一ip多次访问判为一次有效访问</li>
<li>自己访问自己的推广地址不会获得任何奖励</li>
</ul>
</div>
</div>

<div class="jinsom-referral-info-box" style="margin-bottom:0; ">
<div class="header">推广链接产生注册<span>(每位注册)</span></div>
<div class="get">
<?php 
if($jinsom_referral_link_reg_credit){
echo '<m>+'.$jinsom_referral_link_reg_credit.' <span class="jinsom-icon jinsom-jinbi"></span></m>';
}
if($jinsom_referral_link_reg_exp){
echo '<m>+'.$jinsom_referral_link_reg_exp.' <span class="jinsom-icon jinsom-jingyan"></span></m>';
}
?>
</div>
<div class="layui-text">
<ul>
<li style="color: #FF5722;">禁止使用任何作弊工具，一经发现则作封号处理。</li>
</ul>
</div>
</div>



</div>
