<?php 
//用户选择头衔
require( '../../../../../wp-load.php' );
if(current_user_can('level_10')){
$user_id=$_POST['user_id'];
}else{
$user_id=$current_user->ID;
}
$honor=$_POST['honor'];

if (!is_user_logged_in()){
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();     
}

//=========判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}



if(isset($_POST['honor'])){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$honor_arr=explode(",",$user_honor);
if(in_array($honor,$honor_arr)){
update_user_meta($user_id,'use_honor',$honor);//将当前头衔设置为用户使用的头衔
$data_arr['code']=1;
$data_arr['honor']=$honor;
$data_arr['msg']='使用成功！';	
}else{
$data_arr['code']=0;
$data_arr['msg']='你没拥有这个头衔！';	
}

}else{
$data_arr['code']=0;
$data_arr['msg']='你没有可选择的头衔！';	
}


}else{
$data_arr['code']=0;
$data_arr['msg']='参数异常！';
}

header('content-type:application/json');
echo json_encode($data_arr);