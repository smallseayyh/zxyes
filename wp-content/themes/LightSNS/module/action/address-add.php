<?php
//添加地址
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$city=strip_tags(trim($_POST['city']));
$address=strip_tags(trim($_POST['address']));
$name=strip_tags(trim($_POST['name']));
$phone=strip_tags(trim($_POST['phone']));
$address_number=mb_strlen($address,'utf-8');
$phone_number=mb_strlen($phone,'utf-8');
$type=strip_tags(trim($_POST['type']));




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

$user_address_arr=get_user_meta($user_id,'address',true);//用户地址
if($type=='add'){//添加

if(!$address){
$data_arr['code']=0;
$data_arr['msg']=__('地址不能为空！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(!$name){
$data_arr['code']=0;
$data_arr['msg']=__('收件人名称不能为空！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(!$phone){
$data_arr['code']=0;
$data_arr['msg']=__('手机号不能为空！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if($address_number<6){
$data_arr['code']=0;
$data_arr['msg']=__('详细地址不能少于5个字符！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if($phone_number!=11){
$data_arr['code']=0;
$data_arr['msg']=__('手机号码格式有误！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}




if(is_array($user_address_arr)&&count($user_address_arr)>=5){
$data_arr['code']=0;
$data_arr['msg']=__('最多不能添加超过5个地址！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$address=$city.$address;//地址加上省份

$address_arr=array();
$address_arr['address']=$address;
$address_arr['name']=$name;
$address_arr['phone']=$phone;

if($user_address_arr){
array_push($user_address_arr,$address_arr);
update_user_meta($user_id,'address',$user_address_arr);
}else{
$user_address_arr=array();
array_push($user_address_arr,$address_arr);
update_user_meta($user_id,'address',$user_address_arr);
}

$data_arr['code']=1;
$data_arr['code']=1;
$data_arr['code']=1;
$data_arr['code']=1;
$data_arr['msg']=__('添加成功！','jinsom');

}else{//删除
$number=(int)$_POST['number'];
if($user_address_arr){

if(count($user_address_arr)>1){

// print_r($user_address_arr[$number]);

unset($user_address_arr[$number]);//删除指定的地址
$user_address_arr=array_values($user_address_arr);
update_user_meta($user_id,'address',$user_address_arr);
}else{
delete_user_meta($user_id,'address');
}

// print_r($user_address_arr);

$data_arr['code']=1;
$data_arr['msg']=__('删除成功！','jinsom');

}else{
$data_arr['code']=0;
$data_arr['msg']=__('删除失败！数据异常！','jinsom');	
}


}


header('content-type:application/json');
echo json_encode($data_arr);