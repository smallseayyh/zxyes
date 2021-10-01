<?php 
require( '../../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$challenge_number_add=jinsom_get_option('jinsom_challenge_number_add');
$credit_name=jinsom_get_option('jinsom_credit_name');
$jinsom_challenge_default_desc=jinsom_get_option('jinsom_challenge_default_desc');

$shitou_arr=array('石头','剪刀','布');
shuffle($shitou_arr);
?>
<div data-page="challenge-publish" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>


<div class="page-content jinsom-publish-challenge-content">

<div class="type clear">
<li class="on a" data="a">石头剪刀布</li>
<li class="b" data="b">数字比大小</li>
</div>

<div class="tips">
<div class="a">自己手动选择石头剪刀布，与对手发起挑战</div>
<div class="b">系统自动从0-100随机选一个数字，与对手挑战，数字大的赢</div>
</div>

<div class="shitou">
<?php 
$i=0;
foreach ($shitou_arr as $data){
if($i==0){
$on='on';
}else{
$on='';
}
if($data=='石头'){
echo '<li class="'.$on.'"><i class="jinsom-icon jinsom-shitou1"></i><p>石头</p></li>';
}else if($data=='剪刀'){
echo '<li class="'.$on.'"><i class="jinsom-icon jinsom-jiandao1"></i><p>剪刀</p></li>';
}else{
echo '<li class="'.$on.'"><i class="jinsom-icon jinsom-bu"></i><p>布</p></li>';
}
$i++;
}
?>
</div>

<div class="price clear">
<?php 
if($challenge_number_add){
$i=1;
foreach ($challenge_number_add as $data) {
if($i==1){
$on='on';
}else{
$on='';
}
echo '<li class="'.$on.'">'.$data['number'].$credit_name.'</li>';
$i++;
}
}else{
echo jinsom_empty('请在后台添加挑战金额！');
}
?>
</div>
<div class="desc"><textarea placeholder="请填写你的挑战宣言"><?php echo $jinsom_challenge_default_desc;?></textarea></div>
<div class="btn" onclick="jinsom_challenge()">发起挑战</div>

<?php echo do_shortcode(jinsom_get_option('jinsom_challenge_publish_footer_html'));?>
</div>
</div>   

