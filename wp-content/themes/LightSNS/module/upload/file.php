<?php
require( '../../../../../wp-load.php' );
$upload_type='file';//附件上传
$user_id= $current_user->ID;
$file_type=$_POST['type'];


//格式大小
$max=jinsom_get_option('jinsom_upload_file_max');
if($file_type=='video'){
$type=jinsom_get_option('jinsom_upload_publish_video_type');
}else if($file_type=='music'){
$type=jinsom_get_option('jinsom_upload_publish_music_type');
}else{
$type=jinsom_get_option('jinsom_upload_file_type');	
}

//路径
$path='../../../../uploads/user_files/'.$user_id.'/publish/file/';
if(!is_dir($path)){mkdir($path,0755,true);}
$path_url='/user_files/'.$user_id.'/publish/file/';


//上传公共文件
require_once 'upload.php';