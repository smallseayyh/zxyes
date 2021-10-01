<?php
//点击广告
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$today_ad_number=(int)get_user_meta($user_id,'today_ad_number',true);//点击广告次数

//判断是否登录
if (!is_user_logged_in()){ 
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

if($today_ad_number>=200&&!jinsom_is_admin($user_id)){//如果当天点击广告次数大于200次，直接封号
update_user_meta($user_id,'user_power',4);	
delete_user_meta($user_id,'session_tokens');
$data_arr['code']=0;
$data_arr['msg']='异常，封号处理！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


update_user_meta($user_id,'today_ad_number',$today_ad_number+1);
$data_arr['code']=1;
$data_arr['msg']='点击成功！';

header('content-type:application/json');
echo json_encode($data_arr);