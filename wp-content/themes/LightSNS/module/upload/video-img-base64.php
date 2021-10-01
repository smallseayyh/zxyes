<?php
require( '../../../../../wp-load.php' );
$upload_type='video-img-base64';
$user_id = $current_user->ID;

//格式大小
$max = 10;
$type = 'png,jpeg,jpg,gif';

//路径
$path='../../../../uploads/user_files/'.$user_id.'/video-img/';
if(!is_dir($path)){mkdir($path,0755,true);}
$path_url='/user_files/'.$user_id.'/video-img/';


//上传公共文件
require_once 'upload-base64.php';