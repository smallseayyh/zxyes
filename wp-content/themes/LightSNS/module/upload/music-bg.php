<?php
require( '../../../../../wp-load.php' );
$upload_type='bg-music';//上传背景音乐
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$user_id=$_POST['author_id'];
}


if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='VIP会员才允许上传背景音乐！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//格式大小
$max=jinsom_get_option('jinsom_upload_publish_music_max');
$type=jinsom_get_option('jinsom_upload_publish_music_type');

//路径
$path='../../../../uploads/user_files/'.$user_id.'/bg-music/';
if(!is_dir($path)){mkdir($path,0755,true);}
$path_url='/user_files/'.$user_id.'/bg-music/';

//上传公共文件
require_once 'upload.php';