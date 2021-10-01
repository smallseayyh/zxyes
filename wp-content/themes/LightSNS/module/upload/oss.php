<?php 
//阿里云oss对象储存
if($upload_type=='screenshot'){
require( '../../../../../../Extend/upload/oss/autoload.php');
}else{
require( '../../../../../Extend/upload/oss/autoload.php');
}
use OSS\OssClient;
use OSS\Core\OssException;

$endpoint=jinsom_get_option('jinsom_upload_aliyun_oss_url');//地域节点(EndPoint)
$aliyun_oss_id=jinsom_get_option('jinsom_upload_aliyun_oss_id');
$aliyun_oss_key=jinsom_get_option('jinsom_upload_aliyun_oss_key');
$aliyun_oss_bucket=jinsom_get_option('jinsom_upload_aliyun_oss_bucket');
$oss_domain=jinsom_get_option('jinsom_upload_aliyun_oss_domain');

$ossClient=new OssClient($aliyun_oss_id,$aliyun_oss_key,$endpoint);
$uploadRet=$ossClient->uploadFile($aliyun_oss_bucket,substr($path_url,1).$file_name,$tmp);

// print_r($uploadRet);


if($upload_type=='words-img'){//上传缩略图
$thum=imagecropper($tmp,280,280,$user_id,$ext,$file_name);//裁剪
$thum_tmp='../../../../uploads/user_files/'.$user_id.'/publish/words/thumbnail/'.$file_name;
if($thum){//裁剪成功
$thum_key='user_files/'.$user_id.'/publish/words/thumbnail/'.$file_name;//对象储存的缩略图路径
$uploadRet_thum=$ossClient->uploadFile($aliyun_oss_bucket,$thum_key,$thum_tmp);
unlink($thum_tmp);//删除本地临时文件
}else{
$uploadRet_thum=$uploadRet;  
}
}

if($uploadRet['info']['http_code']===200){
$file_url=$uploadRet['info']['url'];
if($oss_domain){
$file_url=$oss_domain.$path_url.$file_name;
}

if($upload_type=='avatar'||$upload_type=='avatar-base64'){//上传头像
update_user_meta($user_id,'avatar_type','upload');
update_user_meta($user_id,'customize_avatar',$file_url);
update_user_meta($user_id,'upload_avatar_times',$upload_times+1);
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

if($upload_type!='avatar'&&$upload_type!='avatar-base64'){
update_user_meta($user_id,'upload_times',$upload_times+1);//更新用户当天上传次数
}

if($upload_type=='words-img'){
$file_thum_url=$uploadRet_thum['info']['url'];
if($oss_domain){
$file_thum_url=$oss_domain.$path_url.'thumbnail/'.$file_name;
}

$data_arr['file_thum_url']=$file_thum_url;
$data_arr['status']=$thum;
}
$data_arr['code']=1;
$data_arr['name']=$name;
$data_arr['file_url']=$file_url;
$data_arr['self']=$self;
$data_arr['msg']='上传成功！';

}else{
$data_arr['code']=0;
$data_arr['msg']='阿里云oss上传失败！';
}