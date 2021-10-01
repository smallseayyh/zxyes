<?php
// require( '../../../../../wp-load.php' );
$upload_type='screenshot';//编辑器截图上传
$user_id=$current_user->ID;

//格式大小
$max=jinsom_get_option('jinsom_upload_publish_bbs_img_max');
$type=jinsom_get_option('jinsom_upload_publish_bbs_img_type');

//路径
$path='../../../../../uploads/user_files/'.$user_id.'/screenshot/';//这个路径和其他上传的是不一样的
if(!is_dir($path)){mkdir($path,0755,true);}
$path_url='/user_files/'.$user_id.'/screenshot/';

//上传公共文件
require_once 'upload.php';
