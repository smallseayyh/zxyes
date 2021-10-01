<?php
//领取vip成长值
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$check=get_user_meta($user_id,'get_vip_number',true);
$vip_number=(int)get_user_meta($user_id,'vip_number',true);
$vip_number_everyday=jinsom_get_option('jinsom_vip_number_everyday');
$date=date('Y-m-d',time());
if(!$vip_number_everyday){$vip_number_everyday=100;}
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


if($check==$date){
$data_arr['code']=1;
$data_arr['msg']='你今日已经领取过成长值了！';
}else{
update_user_meta($user_id,'get_vip_number',$date);
update_user_meta($user_id,'vip_number',$vip_number+$vip_number_everyday);

$data_arr['code']=2;
$data_arr['number']=$vip_number_everyday;
$data_arr['msg']='领取成功！';

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','0','get_vip_number','$time')");

}

header('content-type:application/json');
echo json_encode($data_arr);