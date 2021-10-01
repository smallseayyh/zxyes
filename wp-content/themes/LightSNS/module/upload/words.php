<?php
require( '../../../../../wp-load.php' );
$upload_type='words-img';//动态图片上传
$user_id=$current_user->ID;

//格式大小
$max=jinsom_get_option('jinsom_upload_publish_words_max');
$type=jinsom_get_option('jinsom_upload_publish_words_type');

//路径
$path='../../../../uploads/user_files/'.$user_id.'/publish/words/';
if(!is_dir($path)){mkdir($path,0755,true);}
$path_thum='../../../../uploads/user_files/'.$user_id.'/publish/words/thumbnail/';//缩略图
if(!is_dir($path_thum)){mkdir($path_thum,0755,true);}
$path_url='/user_files/'.$user_id.'/publish/words/';

//上传公共文件
require_once 'upload.php';



//裁剪
function imagecropper($source_path, $target_width, $target_height,$user_id,$ext,$image_name) {
$source_info = getimagesize($source_path);
$source_width = $source_info[0];
$source_height = $source_info[1];
$source_mime = $source_info['mime'];
$source_ratio = $source_height / $source_width;
$target_ratio = $target_height / $target_width;
// 源图过高
if ($source_ratio > $target_ratio) {
$cropped_width = $source_width;
$cropped_height = $source_width * $target_ratio;
$source_x = 0;
$source_y = ($source_height - $cropped_height) / 2;
}
// 源图过宽
elseif ($source_ratio < $target_ratio) {
$cropped_width = $source_height / $target_ratio;
$cropped_height = $source_height;
$source_x = ($source_width - $cropped_width) / 2;
$source_y = 0;
}
// 源图适中
else {
$cropped_width = $source_width;
$cropped_height = $source_height;
$source_x = 0;
$source_y = 0;
}
switch ($source_mime) {
case 'image/gif':
$source_image = imagecreatefromgif($source_path);
break;

case 'image/jpeg':
$source_image = imagecreatefromjpeg($source_path);
break;

case 'image/png':
$source_image = imagecreatefrompng($source_path);
break;

default:
return false;
break;
}
$target_image = imagecreatetruecolor($target_width, $target_height);
$cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);
//裁剪
imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
// 缩放
imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);
//临时保存到本地
$fileName = '../../../../uploads/user_files/'.$user_id.'/publish/words/thumbnail/'.$image_name;
$aa=imagepng($target_image, $fileName);
if($aa){return true;}else{return false;}
}