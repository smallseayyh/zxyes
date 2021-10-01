<?php 
//获取视频信息
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$post_id=$_POST['post_id'];
$post_data = get_post($post_id, ARRAY_A);
$author_id=$post_data['post_author'];
$post_power=get_post_meta($post_id,'post_power',true);
$default_tool=get_term_meta( 1 , 'v', true );
if($default_tool){
$video_url=get_post_meta($post_id,'video_url',true);	
}else{
$video_url=get_post_meta($post_id,'video_pay_url',true);	
}



$video_time=get_post_meta($post_id,'video_time',true);
if($video_time<=15){
$stop_time=3;
}else if($video_time>15&&$video_time<=60){
$stop_time=10;
}else if($video_time>60&&$video_time<=300){
$stop_time=20;
}else if($video_time>300&&$video_time<=600){
$stop_time=30;
}else if($video_time>600&&$video_time<=3600){
$stop_time=120;
}else{
$stop_time=360;
}


if($post_power==1){//付费 
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['time']=$stop_time;
$data_arr['type']='pay';
}else{
$data_arr['code']=1;
}
}else if($post_power==2){//密码
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人 
$data_arr['code']=0;
$data_arr['time']=$stop_time;
$data_arr['type']='pass';
}else{
$data_arr['code']=1;	
}
}else if($post_power==4){//VIP
if(!is_vip($user_id)&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、VIP用户
$data_arr['code']=0;
$data_arr['time']=$stop_time;
$data_arr['type']='vip';
}else{
$data_arr['code']=1;	
}
}else if($post_power==5){//登录
if(!is_user_logged_in()){//没有登录的用户
$data_arr['code']=0;
$data_arr['time']=$stop_time;
$data_arr['type']='login';
}else{
$data_arr['code']=1;	
}
}else if($post_power==6){//回复
if($user_id!=$author_id&&!jinsom_is_comment($user_id,$post_id)&&!jinsom_is_admin($user_id)){//没有回复的用户
$data_arr['code']=0;
$data_arr['time']=$stop_time;
$data_arr['type']='comment';
}else{
$data_arr['code']=1;	
}
}else if($post_power==7){//认证
if($user_id!=$author_id&&!get_user_meta($user_id,'verify',true)&&!jinsom_is_admin($user_id)){//没有认证的用户
$data_arr['code']=0;
$data_arr['time']=$stop_time;
$data_arr['type']='verify';
}else{
$data_arr['code']=1;	
}
}else if($post_power==8){//粉丝可见
if($user_id!=$author_id&&!jinsom_is_follow_author($author_id,$user_id)&&!jinsom_is_admin($user_id)){//没有关注
$data_arr['code']=0;
$data_arr['time']=$stop_time;
$data_arr['type']='follow';
}else{
$data_arr['code']=1;	
}
}else{//公开
$data_arr['code']=1;	
}

$data_arr['url']=$video_url;




header('content-type:application/json');
echo json_encode($data_arr);
