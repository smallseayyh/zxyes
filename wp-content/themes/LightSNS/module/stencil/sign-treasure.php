<?php
//本月累计签到宝箱
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$number=(int)$_POST['number'];
if($number>30||$number<0){
exit();
}
?>
<div class="jinsom-sign-add-form-content">
<?php 
$jinsom_sign_treasure_add=jinsom_get_option('jinsom_sign_treasure_add');
if($jinsom_sign_treasure_add){
$i=0;
foreach ($jinsom_sign_treasure_add as $data) {
if($i==$number){

echo '<div class="treasure-img"><img src="'.$data['img'].'"></div>';

if($data['sign_treasure_reward_data']){

echo '
<fieldset class="layui-elem-field">
<legend>'.__('签到宝箱奖励','jinsom').'</legend>
<div class="layui-field-box">';

foreach ($data['sign_treasure_reward_data'] as $reward_data) {
$reward_type=$reward_data['sign_treasure_reward_type'];
$reward_number=$reward_data['sign_treasure_reward_number'];
if($reward_type=='credit'){
$reward_type=jinsom_get_option('jinsom_credit_name');
}else if($reward_type=='exp'){
$reward_type=__('经验值','jinsom');
}else if($reward_type=='sign'){
$reward_type=__('补签卡','jinsom');
}else if($reward_type=='vip'){
$reward_type=__('VIP天数','jinsom');
}else if($reward_type=='vip_number'){
$reward_type=__('成长值','jinsom');
}else if($reward_type=='charm'){
$reward_type=__('魅力值','jinsom');
}else if($reward_type=='nickname'){
$reward_type=__('改名卡','jinsom');
}else if($reward_type=='honor'){
$reward_type=__('头衔','jinsom');
$reward_number=$reward_data['sign_treasure_reward_honor'];
}

echo '<li>'.$reward_type.' * '.$reward_number.'</li>';


}
echo '</div></fieldset>';


}else{
echo '<div class="no-reward">'.__('该宝箱没有奖励','jinsom').'</div>';
}




}
$i++;
}


}



?>

</div>