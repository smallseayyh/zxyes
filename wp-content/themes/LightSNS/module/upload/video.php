<?php
require( '../../../../../wp-load.php' );
$upload_type='video';//视频上传
$user_id=$current_user->ID;

//格式大小
$max=jinsom_get_option('jinsom_upload_publish_video_max');
$type=jinsom_get_option('jinsom_upload_publish_video_type');

//路径
$path='../../../../uploads/user_files/'.$user_id.'/publish/video/';
if(!is_dir($path)){mkdir($path,0755,true);}
$path_url='/user_files/'.$user_id.'/publish/video/';

//上传公共文件
require_once 'upload.php';