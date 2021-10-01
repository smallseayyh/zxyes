<?php
//切换马甲
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$majia_user_id=$_POST['majia_user_id'];
if(jinsom_get_option('jinsom_majia_on_off')){
$majia_data=jinsom_get_option('jinsom_majia_user_id');
$majia_arr=explode(",",$majia_data);
if(in_array($user_id,$majia_arr)||current_user_can('level_10')){
wp_set_auth_cookie($majia_user_id,true);
$data_arr['code']=1;
$data_arr['msg']='切换成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='没有权限！';
}
}else{
$data_arr['code']=0;
$data_arr['msg']='网站未开启马甲功能！';
}

header('content-type:application/json');
echo json_encode($data_arr);