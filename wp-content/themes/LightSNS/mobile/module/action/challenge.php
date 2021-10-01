<?php 
//发起挑战
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$credit_name=jinsom_get_option('jinsom_credit_name');
$challenge_number_add=jinsom_get_option('jinsom_challenge_number_add');
$max_challenge_times=jinsom_get_option('jinsom_challenge_times');
$credit=(int)get_user_meta($user_id,'credit',true);

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

if(!$challenge_number_add){
$data_arr['code']=0;
$data_arr['msg']='请在后台添加挑战金额！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//使用权限
$power=jinsom_get_option('jinsom_challenge_power');
if($power=='vip'){//VIP用户
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限会员用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='verify'){//认证用户
$user_verify=get_user_meta($user_id,'verify',true);
if(!$user_verify&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限认证用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='honor'){//头衔用户
$user_honor=get_user_meta($user_id,'user_honor',true);
if(!$user_honor&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限拥有头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='admin'){//管理团队
if(!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限管理团队使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='exp'){//指定经验
$user_exp=jinsom_get_user_exp($user_id);//当前用户经验
$im_exp=jinsom_get_option('jinsom_challenge_power_exps');
if($user_exp<$im_exp&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限经验值大于'.$im_exp.'的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='honor_arr'){//指定头衔
if(!jinsom_is_admin($user_id)){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$honor_arr=jinsom_get_option('jinsom_challenge_power_honor_arr');
$publish_power_honor_arr=explode(",",$honor_arr);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($publish_power_honor_arr,$user_honor_arr)){	
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}else if($power=='verify_arr'){//指定认证类型
if(!jinsom_is_admin($user_id)){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$verify_arr=jinsom_get_option('jinsom_challenge_power_verify_arr');
$publish_power_verify_arr=explode(",",$verify_arr);
if(!in_array($user_verify_type,$publish_power_verify_arr)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定认证类型的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定认证类型的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}



$type=strip_tags($_POST['type']);
$value=$_POST['value'];
$price=(int)$_POST['price'];
$desc=strip_tags($_POST['desc']);
if($type!='a'&&$type!='b'){
$type='a';
}

if($type=='a'){
if($value!='石头'&&$value!='剪刀'&&$value!='布'){
$value='石头';
}
}else{
$value=rand(0,100);
}

$price=$challenge_number_add[$price]['number'];

if($price<=0){
$data_arr['code']=0;
$data_arr['msg']='挑战金额无效！请重新选择！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


if($credit<$price){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


$challenge_times=(int)get_user_meta($user_id,'today_challenge_times',true);
if($challenge_times>=$max_challenge_times&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='每天最多只能发起'.$max_challenge_times.'次挑战！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


$max_desc_number=40;
$desc_number=mb_strlen($desc,'utf-8');
if($desc_number>$max_desc_number){
$data_arr['code']=0;
$data_arr['msg']='宣言不能超过'.$max_desc_number.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if(!$desc){
$desc=jinsom_get_option('jinsom_challenge_default_desc');
}

jinsom_update_credit($user_id,$price,'cut','challenge','发起在线挑战',1,'');
update_user_meta($user_id,'today_challenge_times',$challenge_times+1);
global $wpdb;
$table_name=$wpdb->prefix.'jin_challenge';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,challenge_user_id,type,price,user_value,description,time) VALUES ('$user_id',0,'$type','$price','$value','$desc','$time')");


$data_arr['code']=1;
$data_arr['msg']='发起挑战成功！';
header('content-type:application/json');
echo json_encode($data_arr);
