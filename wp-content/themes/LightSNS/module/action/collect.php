<?php
//收藏内容
require( '../../../../../wp-load.php');
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;

//判断是否登录
if(!is_user_logged_in()) { 
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

if(isset($_POST['post_id'])){
$post_id=(int)$_POST['post_id'];
$type=strip_tags($_POST['type']);
$url=strip_tags($_POST['url']);


global $wpdb;
$table_name=$wpdb->prefix.'jin_collect';


if($type=='post'||$type=='goods'){
$collect_count=$wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id AND post_id= $post_id and type='$type' limit 1;");
if($collect_count){//数据库里面有收藏的记录
$wpdb->query( "DELETE FROM $table_name WHERE user_id = $user_id AND post_id= $post_id and type='$type';" );
$data_arr['code']=2;
$data_arr['msg']='已经取消收藏！';
$data_arr['text']='收藏';
}else{
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,time) VALUES ('$user_id','$post_id','$type','$time')" );	
$data_arr['code']=1;
$data_arr['msg']='收藏成功！';
$data_arr['text']='已收藏';
}
}else{
$collect_count=$wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id AND url= '$url' and type='$type' limit 1;");
if($collect_count){//数据库里面有收藏的记录
$wpdb->query( "DELETE FROM $table_name WHERE user_id = $user_id AND url= '$url' and type='$type';" );
$data_arr['code']=2;
$data_arr['msg']='已经取消收藏！';
$data_arr['text']='收藏';
}else{
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,url,type,time) VALUES ('$user_id','$url','$type','$time')" );	
$data_arr['code']=1;
$data_arr['msg']='收藏成功！';
$data_arr['text']='已收藏';
}	
}


	
}else{
$data_arr['code']=0;
$data_arr['msg']='参数异常！';
}


header('content-type:application/json');
echo json_encode($data_arr);	