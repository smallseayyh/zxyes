<?php
require( '../../../../../wp-load.php' );
$upload_type='avatar-base64';//上传头像
$user_id=$current_user->ID;

//头像格式大小
$max=jinsom_get_option('jinsom_upload_publish_avatar_max');
$type=jinsom_get_option('jinsom_upload_publish_avatar_type');

//路径
$path='../../../../uploads/user_files/'.$user_id.'/avatar/';
if(!is_dir($path)){mkdir($path,0755,true);}
$path_url='/user_files/'.$user_id.'/avatar/';


//上传公共文件
require_once 'upload-base64.php';