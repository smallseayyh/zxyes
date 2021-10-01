<?php
//卡密兑换
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;

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



if(isset($_POST['key'])){
$key=$_POST['key'];
global $wpdb;
$table_name = $wpdb->prefix.'jin_key';
$key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE key_number='$key' limit 1;");
if($key_data){
foreach ($key_data as $data) {
$status=$data->status;
$expiry=strtotime($data->expiry);

if($status==1){
$data_arr['code']=0;
$data_arr['msg']='你输入的卡密已经被使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(time()>$expiry){
$data_arr['code']=0;
$data_arr['msg']='你输入的卡密已经过期！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

$use_time=current_time('mysql');
$type=$data->type;
$number=$data->number;
$wpdb->query("UPDATE $table_name SET status=1,user_id='$user_id',use_time='$use_time' WHERE key_number='$key';");//更新为已经使用
if($type=='credit'){
jinsom_update_credit($user_id,$number,'add','recharge','卡密充值',1,'');  
$data_arr['code']=1;
$data_arr['number']=$number;
$data_arr['msg']='兑换成功！<span class="jinsom-icon jinsom-jinbi"></span> +'.$number;	

//更新用户充值总数
$user_recharge=(int)get_user_meta($user_id,'recharge',true);
update_user_meta($user_id,'recharge',$user_recharge+$number);

//更新今日充值金额
$today_credit=(int)get_option('today_credit');
update_option('today_credit',$today_credit+$number);

}else if($type=='vip'){
jinsom_update_user_vip_day($user_id,$number);
$data_arr['code']=1;
$data_arr['msg']='兑换成功！VIP +'.$number.'天';		
}else if($type=='exp'){
jinsom_update_exp($user_id,$number,'add','使用经验礼包');
$data_arr['code']=1;
$data_arr['msg']='兑换成功！<span class="jinsom-icon jinsom-jingyan"></span> +'.$number;	
}else if($type=='sign'){
jinsom_update_user_sign_number($user_id,$number);
$data_arr['code']=1;
$data_arr['msg']='兑换成功！补签卡 +'.$number.'张';		
}else if($type=='nickname'){
jinsom_update_user_nickname_card($user_id,$number);
$data_arr['code']=1;
$data_arr['msg']='兑换成功！改名卡 +'.$number.'张';		
}else if($type=='vip_number'){
$vip_number=(int)get_user_meta($user_id,'vip_number',true);
update_user_meta($user_id,'vip_number',$vip_number+$number);
$data_arr['code']=1;
$data_arr['msg']='兑换成功！ +'.$number.'会员成长值';		
}
$data_arr['type']=$type;

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','0','use_key','$time')");

}	
}else{
$data_arr['code']=0;
$data_arr['msg']='你输入的卡密有误！';	
}

}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';
}

header('content-type:application/json');
echo json_encode($data_arr);