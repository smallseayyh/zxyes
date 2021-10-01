<?php
//生成卡密
require( '../../../../../../wp-load.php' );

//判断是否登录
if (!is_user_logged_in()){ 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否管理员
if (!current_user_can('level_10')){ 
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(isset($_POST['type'])&&isset($_POST['number'])&&isset($_POST['expiry'])){
$type=$_POST['type'];
$number=$_POST['number'];
$add_number=$_POST['add-number'];
$expiry=$_POST['expiry'];

if($number<=0){
$data_arr['code']=0;
$data_arr['msg']='卡密面值必须大于0！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if($add_number>1000||$add_number<=0){
$data_arr['code']=0;
$data_arr['msg']='卡密生成数量范围：0-1000！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

global $wpdb;
$table_name = $wpdb->prefix . 'jin_key';
for ($i=0; $i <$add_number ; $i++){ 
$t=time().rand(1000,9999);
$key = md5($t);
$wpdb->query( "INSERT INTO $table_name (`key_number`, `type`, `status`, `number`, `expiry`) VALUES ('$key','$type',0,'$number','$expiry')");
} 
$data_arr['code']=1;
$data_arr['msg']='卡密生成成功！';	

}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';	
}
header('content-type:application/json');
echo json_encode($data_arr);