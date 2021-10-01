<?php
//生成邀请码
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

if(isset($_POST['number'])){
$number=$_POST['number'];

if($number>1000||$number<=0){
$data_arr['code']=0;
$data_arr['msg']='邀请码成数量范围：0-1000！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



global $wpdb;
$table_name = $wpdb->prefix . 'jin_invite_code';


for ($i=0; $i <$number ; $i++){ 
$code=jinsom_make_invite_code();
$wpdb->query( "INSERT INTO $table_name (code,status) VALUES ('$code',0)" );
}

$data_arr['code']=1;
$data_arr['msg']='邀请码生成成功！';	

}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';	
}
header('content-type:application/json');
echo json_encode($data_arr);