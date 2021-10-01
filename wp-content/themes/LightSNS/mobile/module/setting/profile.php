<?php 
//修改资料
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;

//判断是否登录
if(!$user_id){
$data_arr['code']=0;
$data_arr['msg']='你的登录身份已经失效！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
if(jinsom_is_admin($user_id)){
$author_id=(int)$_POST['author_id'];	
}else{
$author_id=$user_id;
}

$key_arr=array('birthday','gender','city_lock','publish_city','im_privacy','hide_like','hide_buy','user_notice','system_notice','comment_notice');
$jinsom_member_profile_setting_add=jinsom_get_option('jinsom_member_profile_setting_add');
if($jinsom_member_profile_setting_add){
foreach ($jinsom_member_profile_setting_add as $data) {
array_push($key_arr,$data['value']);
}
}

$type=strip_tags($_POST['type']);
if(!in_array($type,$key_arr)){
$data_arr['code']=0;
$data_arr['msg']='参数异常！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if($_POST['value']){
update_user_meta($author_id,$type,htmlentities($_POST['value'],ENT_QUOTES,'UTF-8'));
}else{
delete_user_meta($author_id,$type);
}

$data_arr['code']=1;
$data_arr['msg']='修改成功！';
$data_arr['self']=$current_user->ID==$_POST['author_id']?1:0;
header('content-type:application/json');
echo json_encode($data_arr);

