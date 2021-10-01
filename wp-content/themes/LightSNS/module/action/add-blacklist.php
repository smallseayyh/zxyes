<?php
//添加黑名单
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;
$author_id=$_POST['author_id'];
$blacklist=get_user_meta($user_id,'blacklist',true);
if($blacklist){
$black_arr=explode(",",$blacklist);	
}else{
$black_arr=array();	
}

if($author_id==$user_id){
$data_arr['code']=0;
$data_arr['msg']='你不能拉黑你自己！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if(jinsom_is_admin($author_id)){
$data_arr['code']=0;
$data_arr['msg']='你不能将网站管理加入黑名单！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

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

if(isset($_POST['author_id'])){
if(in_array($author_id,$black_arr)){
//array_merge(array_diff($black_arr, array($author_id)));//移除黑名单
$key = array_search($author_id,$black_arr);
array_splice($black_arr,$key,1);

$data_arr['code']=1;
$data_arr['msg']='已经取消黑名单！';
}else{
array_push($black_arr,$author_id);//加入黑名单
$data_arr['code']=2;
$data_arr['msg']='已经加入黑名单！';
}
if(empty($black_arr)){
update_user_meta($user_id,'blacklist','');
}else{
$str= implode(",",$black_arr);
update_user_meta($user_id,'blacklist',$str);	
}

}else{
$data_arr['code']=0;
$data_arr['msg']='参数有误！';	
}


header('content-type:application/json');
echo json_encode($data_arr);
