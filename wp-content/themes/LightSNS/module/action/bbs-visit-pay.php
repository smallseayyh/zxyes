<?php
//付费访问论坛
require('../../../../../wp-load.php');
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;
$bbs_id=(int)$_POST['bbs_id'];
$bbs_name=jinsom_get_option('jinsom_bbs_name');//论坛名称

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

$bbs_visit_power=get_term_meta($bbs_id,'bbs_visit_power',true);

if($bbs_visit_power!=11){
$data_arr['code']=0;
$data_arr['msg']='数据异常！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


$visit_power_pay_price=(int)get_term_meta($bbs_id,'visit_power_pay_price',true);//需要付费的金币
$user_credit=(int)get_user_meta($user_id,'credit',true);
if($user_credit<$visit_power_pay_price){
$data_arr['code']=3;
$data_arr['msg']='你的'.jinsom_get_option('jinsom_credit_name').'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$visit_power_pay_user_id=get_term_meta($bbs_id,'visit_power_pay_user_id',true);//论坛收入所属人
jinsom_update_credit($user_id,$visit_power_pay_price,'cut','visit_bbs','加入付费'.$bbs_name,1,'');
jinsom_update_credit($visit_power_pay_user_id,$visit_power_pay_price,'add','visit_bbs',__('别人加入你的付费论坛','jinsom'),1,'');
$bbs_pay_user_list=get_term_meta($bbs_id,'visit_power_pay_user_list',true);
if($bbs_pay_user_list){
$bbs_pay_user_list_arr=explode(",",$bbs_pay_user_list);
array_push($bbs_pay_user_list_arr,$user_id);
$str=implode(",",$bbs_pay_user_list_arr);
$str=rtrim($str,',');
update_term_meta($bbs_id,'visit_power_pay_user_list',$str);
}else{
update_term_meta($bbs_id,'visit_power_pay_user_list',$user_id);
}


if($visit_power_pay_user_id){
jinsom_add_tips($visit_power_pay_user_id,$user_id,$bbs_id,'visit_bbs','加入了你的付费'.$bbs_name,__('加入了','jinsom'));
}

$data_arr['code']=1;
$data_arr['msg']='支付成功！';

header('content-type:application/json');
echo json_encode($data_arr);