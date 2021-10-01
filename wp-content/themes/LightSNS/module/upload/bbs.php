<?php
require( '../../../../../wp-load.php' );
$upload_type='bbs';//论坛上传(文章、文章权限、帖子、帖子权限、帖子回复)
$user_id=$current_user->ID;

//格式大小
$max=jinsom_get_option('jinsom_upload_publish_bbs_img_max');
$type=jinsom_get_option('jinsom_upload_publish_bbs_img_type');

//路径
$path='../../../../uploads/user_files/'.$user_id.'/bbs/';
if(!is_dir($path)){mkdir($path,0755,true);}
$path_url='/user_files/'.$user_id.'/bbs/';

//上传公共文件
require_once 'upload.php';