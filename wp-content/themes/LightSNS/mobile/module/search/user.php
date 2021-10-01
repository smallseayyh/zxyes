<?php 
//搜索用户
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$keyword=$_POST['key'];

$user_query = new WP_User_Query( array ( 
'meta_key' => 'nickname',
'meta_value' => $keyword,//搜昵称
'orderby' => 'ID',
'order' =>'DESC',
'meta_compare' =>'LIKE',
'count_total'=>false,
'number'=> 50
));
if(!empty($user_query->results)){
$data_arr['code']=1;
$data_arr['data']=array();
foreach ($user_query->results as $user){
$follow_arr=array();
$follow_user_id=$user->ID;
$follow_arr['name']=jinsom_nickname($follow_user_id);
$follow_arr['nickname']=get_user_meta($follow_user_id,'nickname',true);
$follow_arr['avatar']=jinsom_avatar($follow_user_id,'40',avatar_type($follow_user_id));
$follow_arr['verify']=jinsom_verify($follow_user_id);
$follow_arr['vip']=jinsom_vip($follow_user_id);
array_push($data_arr['data'],$follow_arr);
}
}else{
$data_arr['code']=0;	
$data_arr['content']=jinsom_empty();	
}


header('content-type:application/json');
echo json_encode($data_arr);