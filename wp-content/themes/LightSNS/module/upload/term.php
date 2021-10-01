<?php
require( '../../../../../wp-load.php' );
$upload_type='term';//论坛、子论坛、话题头像上传
$user_id=$current_user->ID;
$bbs_id=strip_tags($_POST['bbs_id']);

$max=10;    
$type='jpg,png,gif,jpeg,svg';   

//路径
$path='../../../../uploads/user_files/'.$user_id.'/setting/';
if(!is_dir($path)){mkdir($path,0755,true);}
$path_url='/user_files/'.$user_id.'/setting/';

//上传公共文件
require_once 'upload.php';