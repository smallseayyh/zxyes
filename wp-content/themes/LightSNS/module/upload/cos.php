<?php 
//腾讯云cos对象储存
if($upload_type=='screenshot'){
require( '../../../../../../Extend/upload/cos/vendor/autoload.php');
}else{
require( '../../../../../Extend/upload/cos/vendor/autoload.php');
}
$secretId=jinsom_get_option('jinsom_upload_cos_secretid');//SecretId";
$secretKey=jinsom_get_option('jinsom_upload_cos_secretkey');//"SecretKey";
$region=jinsom_get_option('jinsom_upload_cos_region');//存储桶地域
$bucket=jinsom_get_option('jinsom_upload_cos_bucket');//存储桶名称
$cos_domain=jinsom_get_option('jinsom_upload_cos_domain');//cos自定义域名
$key=$path_url.$file_name;//对象储存文件路径

if(is_ssl()){
$ssl='https';
}else{
$ssl='http';
}

$cosClient = new Qcloud\Cos\Client(
array(
'region' => $region,
'schema' => $ssl, //协议头部，默认为http
'credentials'=> array(
'secretId'  => $secretId ,
'secretKey' => $secretKey)));


$uploadRet=$cosClient->putObject(array('Bucket'=>$bucket,'Key'=>$key,'Body'=>fopen($tmp,"rb")));
// print_r($result);



if($upload_type=='words-img'){//上传缩略图
$thum=imagecropper($tmp,280,280,$user_id,$ext,$file_name);//裁剪
$thum_tmp='../../../../uploads/user_files/'.$user_id.'/publish/words/thumbnail/'.$file_name;//缩略图线上绝对路径
if($thum){//裁剪成功
$thum_key='/user_files/'.$user_id.'/publish/words/thumbnail/'.$file_name;//对象储存的缩略图路径
$uploadRet_thum=$cosClient->putObject(array('Bucket'=>$bucket,'Key'=>$thum_key,'Body'=>fopen($thum_tmp,"rb")));
unlink($thum_tmp);//删除本地临时文件
}else{
$uploadRet_thum=$uploadRet;  
}
}



if($uploadRet['Location']!=''){
//$file_url=$uploadRet['data']['source_url'];
$file_url='https://'.$uploadRet['Location'];
if($cos_domain){
$file_url=$cos_domain.$path_url.$file_name;
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
$file_thum_url='https://'.$uploadRet_thum['Location'];
if($cos_domain){
$file_thum_url=$cos_domain.$path_url.'thumbnail/'.$file_name;
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
$data_arr['msg']='腾讯云cos上传失败！';
}

