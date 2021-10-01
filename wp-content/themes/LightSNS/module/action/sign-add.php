<?php
//补签
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$day=(int)$_POST['day'];


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



if($day>31||$day<1){
$data_arr['code']=0;
$data_arr['msg']=__('补签的日期不合法！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

$sign_card=(int)get_user_meta($user_id,'sign_card',true);
if($sign_card<=0){
$data_arr['code']=0;
$data_arr['msg']=__('你的补签卡不足！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

global $wpdb;
$table_name=$wpdb->prefix.'jin_sign';
$sign_c=(int)get_user_meta($user_id,'sign_c',true);//累计签到天数
$sign_c=$sign_c+1;
$time=mktime(0,0,0,date('m',time()),$day,date('Y',time()));
$date=date('Y-m-d',$time);

$status=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name where user_id=$user_id and date='$date' limit 1");
if($status){
$data_arr['code']=0;
$data_arr['msg']=__('当天已经补签了，请勿重复操作！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//补签成功逻辑

$wpdb->query( "INSERT INTO $table_name (user_id,sign_day,strtotime,date) VALUES ('$user_id','$sign_c','$time','$date')");
update_user_meta($user_id,'sign_c',$sign_c);//更新累计签到天数
update_user_meta($user_id,'sign_card',$sign_card-1);

//更新奖励
$jinsom_sign_add=jinsom_get_option('jinsom_sign_add');
$reward_html='';
if($jinsom_sign_add){
// $day=date('d',time());
foreach ($jinsom_sign_add as $data){
if($data['day']==$day){
if($data['sign_reward_data']){
foreach ($data['sign_reward_data'] as $reward_data){
$reward_type=$reward_data['sign_reward_type'];
$reward_number=$reward_data['sign_reward_number'];
if($reward_type=='credit'){
$reward_type=jinsom_get_option('jinsom_credit_name');
jinsom_update_credit($user_id,$reward_number,'add','sign','签到奖励',1,''); 
}else if($reward_type=='exp'){
$reward_type=__('经验值','jinsom');
jinsom_update_exp($user_id,$reward_number,'add','签到奖励');
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
$reward_number=$reward_data['sign_reward_honor'];//奖励的头衔
jinsom_add_honor($user_id,$reward_number);
}


$reward_html.='<li>'.$reward_type.' * '.$reward_number.'</li>';


}
}
}
}
}

if($reward_html){
$data_arr['content']='<li><i class="jinsom-icon jinsom-zhifuchenggong"></i>'.__('补签成功','jinsom').'</li><li>'.sprintf(__( '累计签到%s天','jinsom'),$sign_c).'</li><fieldset class="layui-elem-field"><legend>'.__('获得以下奖励','jinsom').'</legend><div class="layui-field-box">'.$reward_html.'</div></fieldset>';
}else{
$data_arr['content']='<li><i class="jinsom-icon jinsom-zhifuchenggong"></i>'.__('补签成功','jinsom').'</li><li>'.sprintf(__( '累计签到%s天','jinsom'),$sign_c).'</li>';
}

$data_arr['code']=1;
$data_arr['sign_c']=$sign_c;//累计
$data_arr['msg']=__('补签成功！','jinsom');


header('content-type:application/json');
echo json_encode($data_arr);