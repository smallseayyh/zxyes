<?php 
//远程FTP

if($upload_type=='words-base64'||$upload_type=='video-img-base64'||$upload_type=='avatar-base64'){//base64
$tmp='../../../../uploads'.$path_url.$file_name;
}

$ftp_server=jinsom_get_option('jinsom_upload_ftp_server');
$ftp_username=jinsom_get_option('jinsom_upload_ftp_username');
$ftp_password=jinsom_get_option('jinsom_upload_ftp_password');
$ftp_domain=jinsom_get_option('jinsom_upload_ftp_domain');
// $ftp_mode=jinsom_get_option('jinsom_upload_ftp_mode');
$ftp_mode=FTP_BINARY;//||FTP_ASCII
$conn_id=ftp_connect($ftp_server);
$login_result=ftp_login($conn_id,$ftp_username, $ftp_password);

if((!$conn_id)||(!$login_result)){
echo json_encode(array("code"=>0,"msg"=>"远程FTP无法链接！"));
exit;
}

ftp_pasv($conn_id, true);//开启被动模式

$key=$path_url.$file_name;
dir_mkdirs($key,$conn_id);//创建目录





if(ftp_put($conn_id,$key,$tmp,$ftp_mode)){
$file_url=$ftp_domain.$key;


if($upload_type=='words-base64'){//base64
$file_thum_url=$file_url;
}else{

if($upload_type=='words-img'){//上传缩略图
$thum=imagecropper($tmp,280,280,$user_id,$ext,$file_name);//裁剪
$thum_tmp='../../../../uploads/user_files/'.$user_id.'/publish/words/thumbnail/'.$file_name;//缩略图线上绝对路径
if($thum){//裁剪成功
$thum_key='/user_files/'.$user_id.'/publish/words/thumbnail/'.$file_name;//对象储存的缩略图路径
dir_mkdirs($thum_key,$conn_id);//创建缩略图目录
$uploadRet_thum=ftp_put($conn_id,$thum_key,$thum_tmp,$ftp_mode);
$file_thum_url=$ftp_domain.$thum_key;
unlink($thum_tmp);//删除本地临时文件
}else{
$file_thum_url=$file_url;
}
}

}



if($upload_type=='avatar'||$upload_type=='avatar-base64'){//上传头像
update_user_meta($user_id,'avatar_type','upload');
update_user_meta($user_id,'customize_avatar',$file_url);
update_user_meta($user_id,'upload_avatar_times',$upload_times+1);//更新用户当天上传次数
}else if($upload_type=='words-img'){
$data_arr['file_thum_url']=$file_thum_url;
$data_arr['status']=$thum;
}else if($upload_type=='im-one'){
$img='<a href="'.$file_url.'" data-fancybox="gallery"><img src="'.$file_url.'" class="jinsom-group-img"></a>'; 
jinsom_add_im_one_img($author_id,$user_id,$img);
$data_arr['img']=$img;	
}else if($upload_type=='im-group'){
$img='<a href="'.$file_url.'" data-fancybox="gallery"><img src="'.$file_url.'" class="jinsom-group-img"></a>'; 
jinsom_add_group_img($bbs_id,$user_id,$img);
$data_arr['img']=$img;	
}else if($upload_type=='term'){
update_term_meta($bbs_id,'bbs_avatar',$file_url);
}else if($upload_type=='screenshot'){
$data_arr['url']=$file_url;
$data_arr['state']='SUCCESS';
}else if($upload_type=='live-comment'){//直播评论上图
$time = current_time('mysql');
$ip = $_SERVER['REMOTE_ADDR'];
$data = array(
'comment_post_ID' =>$post_id,
'comment_content' => '<a href="'.$file_url.'" data-fancybox="gallery" data-no-instant><img src="'.$file_url.'" class="jinsom-live-comment-img"></a>',
'user_id' => $user_id,
'comment_author_IP'=>$ip,
'comment_date' => $time
);
wp_insert_comment($data);
}
if($upload_type!='avatar'){
update_user_meta($user_id,'upload_times',$upload_times+1);//更新用户当天上传次数
}


$data_arr['code']=1;
$data_arr['name']=$name;
$data_arr['file_url']=$file_url;    
$data_arr['self']=$self; 
$data_arr['msg']='上传成功！';


}else{
$data_arr['code']=0;
$data_arr['msg']='远程FTP上传失败！';
}

ftp_close($conn_id);




//创建多层目录
function dir_mkdirs($path,$conn_id){
$path_arr = explode('/',$path); // 取目录数组
$file_name = array_pop($path_arr); // 弹出文件名
$path_div = count($path_arr); // 取层数
foreach($path_arr as $val){ // 创建目录
if(ftp_chdir($conn_id,$val) == FALSE){
$tmp = ftp_mkdir($conn_id,$val);
if($tmp == FALSE){
echo json_encode(array("code"=>0,"msg"=>"录创建失败，请检查权限及路径是否正确！"));
exit;
}
ftp_chdir($conn_id,$val);
}
}
}