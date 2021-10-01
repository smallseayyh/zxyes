<?php
//黑名单保释
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$author_id=$_POST['author_id'];
$credit=(int)get_user_meta($user_id,'credit',true);
$jinsom_blacklist_bail_number=jinsom_get_option('jinsom_blacklist_bail_number');

$blacklist_time=strtotime(get_user_meta($author_id,'blacklist_time',true));
$day=ceil(($blacklist_time-time())/86400);
$bail_credit=$jinsom_blacklist_bail_number*$day;

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['cover']=$jinsom_luck_gift_default_cover;
$data_arr['msg']=jinsom_no_login_tips();
$data_arr['cover']=$jinsom_luck_gift_default_cover;
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['cover']=$jinsom_luck_gift_default_cover;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if($credit<$bail_credit){
$data_arr['code']=0;
$data_arr['msg']='你的'.jinsom_get_option('jinsom_credit_name').'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


jinsom_update_credit($user_id,$bail_credit,'cut','blacklist_bail','保释黑名单用户',1,'');
jinsom_add_tips($author_id,$user_id,0,'blacklist_bail','保释了你','保释了');
update_user_meta($author_id,'blacklist_time',date('Y-m-d'));

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$user_id','$post_id','blacklist_bail','$time','$author_id')");


$data_arr['code']=1;
$data_arr['msg']='保释成功！';
header('content-type:application/json');
echo json_encode($data_arr);