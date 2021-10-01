<?php 
//生成推广地址
require( '../../../../../../wp-load.php' );

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

$user_id=$current_user->ID;
$referral_link_name=jinsom_get_option('jinsom_referral_link_name');
$url=home_url().'/?'.$referral_link_name.'='.$user_id;
if($url){
update_user_meta($user_id,'referral-url',$url);
$data_arr['code']=1;
$data_arr['url']=$url;
$data_arr['msg']=__('推广地址已生成！','jinsom');
}else{
$data_arr['code']=0;
$data_arr['url']=$url;
$data_arr['msg']=__('推广地址获取失败！','jinsom');
}


header('content-type:application/json');
echo json_encode($data_arr);