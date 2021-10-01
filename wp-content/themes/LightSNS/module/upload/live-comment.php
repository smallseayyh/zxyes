<?php
require( '../../../../../wp-load.php' );
$upload_type='live-comment';//直播互动
$user_id=$current_user->ID;
$post_id=(int)$_POST['post_id'];

//格式大小
$max=5;
$type='jpg,png,jpeg,gif';

//路径
$path='../../../../uploads/user_files/'.$user_id.'/live-comment/';
if(!is_dir($path)){mkdir($path,0755,true);}
$path_url='/user_files/'.$user_id.'/live-comment/';

$data_arr['nickname']=jinsom_nickname($user_id).jinsom_lv($user_id).jinsom_vip($user_id).jinsom_honor($user_id); //评论成功
$data_arr['avatar']=jinsom_avatar($user_id,'40',avatar_type($user_id));


//上传公共文件
require_once 'upload.php';