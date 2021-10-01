<?php
require( '../../../../../wp-load.php' );
$upload_type='words-base64';
$user_id = $current_user->ID;

//格式大小
$max = jinsom_get_option('jinsom_upload_publish_words_max');
$type = jinsom_get_option('jinsom_upload_publish_words_type');

//路径
$path='../../../../uploads/user_files/'.$user_id.'/publish/post/';
if(!is_dir($path)){mkdir($path,0755,true);}
$path_url='/user_files/'.$user_id.'/publish/post/';


//上传公共文件
require_once 'upload-base64.php';