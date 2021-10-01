<?php 
//又拍云upyun对象储存
if($upload_type=='screenshot'){
require( '../../../../../../Extend/upload/upyun/vendor/autoload.php');
}else{
require( '../../../../../Extend/upload/upyun/vendor/autoload.php');
}
use Upyun\Upyun;
use Upyun\Config;

$secretId=jinsom_get_option('jinsom_upload_upyun_id');
$secretKey=jinsom_get_option('jinsom_upload_upyun_key');
$bucket=jinsom_get_option('jinsom_upload_upyun_bucket');
$upyun_domain=jinsom_get_option('jinsom_upload_upyun_domain');
$key=$path_url.$file_name;//对象储存文件路径


$bucketConfig=new Config($bucket,$secretId,$secretKey);
$client=new Upyun($bucketConfig);




$uploadRet=$client->write($key,fopen($tmp,'r'));
//print_r($uploadRet);



if($upload_type=='words-img'){//上传缩略图
$thum=imagecropper($tmp,280,280,$user_id,$ext,$file_name);//裁剪
$thum_tmp='../../../../uploads/user_files/'.$user_id.'/publish/words/thumbnail/'.$file_name;//缩略图线上绝对路径
if($thum){//裁剪成功
$thum_key='/user_files/'.$user_id.'/publish/words/thumbnail/'.$file_name;//对象储存的缩略图路径
$uploadRet_thum=$client->write($thum_key,fopen($thum_tmp,'r'));
$file_thum_url=$upyun_domain.$thum_key;
unlink($thum_tmp);//删除本地临时文件
}else{
$file_thum_url=$upyun_domain.$path_url.$file_name;
}
}


if($uploadRet['x-upyun-content-type']!=''){
$file_url=$upyun_domain.$path_url.$file_name;

if($upload_type=='avatar'||$upload_type=='avatar-base64'){//上传头像
update_user_meta($user_id,'avatar_type','upload');
update_user_meta($user_id,'customize_avatar',$file_url);
update_user_meta($user_id,'upload_avatar_times',$upload_times+1);
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

if($upload_type!='avatar'&&$upload_type!='avatar-base64'){
update_user_meta($user_id,'upload_times',$upload_times+1);//更新用户当天上传次数
}


$data_arr['code']=1;
$data_arr['name']=$name;
$data_arr['file_url']=$file_url;
$data_arr['self']=$self;
$data_arr['msg']='上传成功！';



}else{
$data_arr['code']=0;
$data_arr['msg']='又拍云upyun上传失败！';
}

