<?php
//补签
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$day=(int)$_POST['day'];
if($day>31||$day<1){
exit();
}
?>
<div class="jinsom-sign-add-form-content">
<?php 
$jinsom_sign_add=jinsom_get_option('jinsom_sign_add');
$reward_type='';
if($jinsom_sign_add){
foreach ($jinsom_sign_add as $data) {
if($data['day']==$day){
if($data['sign_reward_data']){

echo '
<fieldset class="layui-elem-field">
<legend>'.__('当天签到奖励','jinsom').'</legend>
<div class="layui-field-box">';

foreach ($data['sign_reward_data'] as $reward_data) {
$reward_type=$reward_data['sign_reward_type'];
$reward_number=$reward_data['sign_reward_number'];
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
$reward_number=$reward_data['sign_reward_honor'];
}

echo '<li>'.$reward_type.' * '.$reward_number.'</li>';


}
echo '</div></fieldset>';


}else{
echo '<div class="no-reward">'.__('当天没有签到奖励','jinsom').'</div>';
}




}
}


}

// if(!$reward_type){
// echo '<div class="no-reward">'.__('当天没有签到奖励','jinsom').'</div>';
// }

if($day<date('d',time())){
if(jinsom_is_sign($user_id,date('Y-m',time()).'-'.$day)){
echo '<div class="btn no">'.__('已经签到','jinsom').'</div>';
}else{
$sign_card=(int)get_user_meta($user_id,'sign_card',true);	
echo '<div class="opacity btn" onclick="jinsom_sign_add('.$day.')">'.__('立即补签','jinsom').' <m>(补签卡*'.$sign_card.')</m></div>';	
}
}else if($day==date('d',time())){

}

// echo date('Y-m',time()).'-'.$day;

?>

</div>