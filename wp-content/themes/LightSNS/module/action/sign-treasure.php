<?php
//领取累计签到宝箱奖励
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$k=(int)$_POST['number'];


//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



if($k>30||$k<0){
$data_arr['code']=0;
$data_arr['msg']=__('参数有误！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}




//领取成功逻辑
$jinsom_sign_treasure_add=jinsom_get_option('jinsom_sign_treasure_add');
if($jinsom_sign_treasure_add){
$i=0;
foreach ($jinsom_sign_treasure_add as $data){
if($i==$k){


$day=$data['day'];



$task_id=date('Y-m',time()).'_'.$day;
if(jinsom_is_task($user_id,$task_id)){
$data_arr['code']=0;
$data_arr['msg']=__('你已经领取过了！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}







global $wpdb;
$table_name=$wpdb->prefix.'jin_sign';
$month_day=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id AND ( DATE_FORMAT(date,'%Y%m')=DATE_FORMAT(CURDATE(),'%Y%m') )  GROUP BY date limit 31;");
$month_day=count($month_day);
if($month_day<$day){
$data_arr['code']=0;
$data_arr['msg']=__('你还没有达到领取条件！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}




//数据库记录领取记录
$time=current_time('mysql');
$table_name_task = $wpdb->prefix.'jin_task';
$wpdb->query( "INSERT INTO $table_name_task (user_id,task_id,type,time) VALUES ('$user_id','$task_id','sign','$time')" );

//记录实时动态
$table_name_now=$wpdb->prefix.'jin_now';
$wpdb->query( "INSERT INTO $table_name_now (user_id,post_id,type,do_time) VALUES ('$user_id','0','sign-treasure','$time')");

if($data['sign_treasure_reward_data']){
foreach ($data['sign_treasure_reward_data'] as $reward_data){
$reward_type=$reward_data['sign_treasure_reward_type'];
$reward_number=$reward_data['sign_treasure_reward_number'];
if($reward_type=='credit'){
$reward_type=jinsom_get_option('jinsom_credit_name');
jinsom_update_credit($user_id,$reward_number,'add','sign','签到宝箱奖励',1,''); 
}else if($reward_type=='exp'){
$reward_type=__('经验值','jinsom');
jinsom_update_exp($user_id,$reward_number,'add','签到宝箱奖励');
}else if($reward_type=='sign'){
$reward_type=__('补签卡','jinsom');
jinsom_update_user_sign_number($user_id,$reward_number);
}else if($reward_type=='vip'){
$reward_type=__('VIP天数','jinsom');
jinsom_update_user_vip_day($user_id,$reward_number);
}else if($reward_type=='vip_number'){
$reward_type=__('成长值','jinsom');
jinsom_update_user_vip_number($user_id,$reward_number);
}else if($reward_type=='charm'){
$reward_type=__('魅力值','jinsom');
jinsom_update_user_charm($user_id,$reward_number);
}else if($reward_type=='nickname'){
$reward_type=__('改名卡','jinsom');
jinsom_update_user_nickname_card($user_id,$reward_number);
}else if($reward_type=='honor'){
$reward_type=__('头衔','jinsom');
$reward_number=$reward_data['sign_treasure_reward_honor'];//奖励的头衔
jinsom_add_honor($user_id,$reward_number);
}


$reward_html.='<li>'.$reward_type.' * '.$reward_number.'</li>';


}
}
}
$i++;
}
}

if($reward_html){
$data_arr['content']='<li><i class="jinsom-icon jinsom-zhifuchenggong"></i>'.__('领取成功','jinsom').'</li><li>'.__('请再接再厉，获取更多奖励！','jinsom').'</li><fieldset class="layui-elem-field"><legend>'.__('获得以下奖励','jinsom').'</legend><div class="layui-field-box">'.$reward_html.'</div></fieldset>';
}else{
$data_arr['content']='<li><i class="jinsom-icon jinsom-zhifuchenggong"></i>'.__('领取成功','jinsom').'</li><li>'.__('请再接再厉，获取更多奖励！','jinsom').'</li>';
}

$data_arr['code']=1;
$data_arr['msg']=__('领取成功！','jinsom');


header('content-type:application/json');
echo json_encode($data_arr);