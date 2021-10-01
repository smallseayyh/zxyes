<div class="jinsom-task-treasure-form">
<div class="number"><n><?php echo (int)jinsom_task_treasure_people_number($task_id);?></n><?php _e('人已领取','jinsom');?></div>
<?php 
if($jinsom_task_treasure_add){
foreach ($jinsom_task_treasure_add as $data) {
if($task_id==$data['id']){
echo '
<div class="jinsom-task-treasure-img"><img src="'.$data['open_img'].'"></div>
<div class="jinsom-task-treasure-name">'.$data['name'].'</div>
';
$reward='';
if($data['reward_add']){
foreach ($data['reward_add'] as $reward_add) {
$reward_type=$reward_add['reward_type'];
$reward_number=$reward_add['reward_number'];
if($reward_type=='credit'){
$reward_type=jinsom_get_option('jinsom_credit_name');
}else if($reward_type=='exp'){
$reward_type=__('经验值','jinsom');
}else if($reward_type=='vip'){
$reward_type=__('VIP天数','jinsom');
}else if($reward_type=='vip_number'){
$reward_type=__('成长值','jinsom');
}else if($reward_type=='charm'){
$reward_type=__('魅力值','jinsom');
}else if($reward_type=='sign'){
$reward_type=__('补签卡','jinsom');
}else if($reward_type=='nickname'){
$reward_type=__('改名卡','jinsom');
}else if($reward_type=='honor'){
$reward_type=__('头衔','jinsom');
$reward_number=$reward_add['reward_honor'];
}

$reward.='<span>'.$reward_type.' * '.$reward_number.'</span>';
}
}else{
$reward.=__('暂无奖励','jinsom');
}

echo '<div class="jinsom-task-treasure-reward">'.$reward.'</div>';

if(jinsom_is_task($user_id,$task_id)){
echo '<div class="jinsom-task-treasure-btn opacity had">'.__('已经领取','jinsom').'</div>';
}else{
echo '<div class="jinsom-task-treasure-btn opacity" onclick=\'jinsom_task_treasure("'.$task_id.'",this)\'>'.__('马上领取','jinsom').'</div>';
}



}
}
}
?>
</div>


