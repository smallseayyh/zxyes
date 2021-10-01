<?php
//话题权限
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$topic_name=$_POST['topic_name'];
$topic_data=get_term_by('name',$topic_name,'post_tag');
$topic_id=$topic_data->term_id;

$power=get_term_meta($topic_id,'power',true);
if(!$power){$power='login';}
$power_user=get_term_meta($topic_id,'power_user',true);
$power_user_arr=explode(",",$power_user);

$topic_default_name=jinsom_get_option('jinsom_topic_name');

if($power=='login'){
if(is_user_logged_in()){
$data_arr['code']=1;
}else{
$data_arr['code']=0;	
$data_arr['msg']=__('你还没有登录，请登录之后再重试！','jinsom');
}
}else if($power=='admin'){
if(jinsom_is_admin($user_id)){
$data_arr['code']=1;	
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$topic_default_name.'只允许官方使用！';
}
}else if($power=='vip'){
if(is_vip($user_id)||jinsom_is_admin($user_id)){
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$topic_default_name.'只允许VIP用户使用！';	
}
}else if($power=='verify'){
$user_verify=get_user_meta($user_id,'verify',true);
if($user_verify||jinsom_is_admin($user_id)){
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$topic_default_name.'只允许认证用户使用！';	
}
}else if($power=='user'){
if(in_array($user_id,$power_user_arr)||jinsom_is_admin($user_id)){
$data_arr['code']=1;	
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$topic_default_name.'只允许指定用户使用！';	
}
}


header('content-type:application/json');
echo json_encode($data_arr);