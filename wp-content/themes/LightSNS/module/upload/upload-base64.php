<?php
require('../admin/ajax.php');//验证

$self=($user_id==$_POST['user_id'])?1:0;
if(current_user_can('level_10')){
$user_id=$_POST['user_id'];	
}


$base64=str_replace(" ","+",$_POST['base64']);

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否黑名单
if(jinsom_is_black($user_id)&&!jinsom_is_admin($current_user->ID)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//上传上限拦截
if(!jinsom_is_admin($current_user->ID)){
if($upload_type=='avatar-base64'){
$upload_tips=__('你当天的上传头像次数已达上限<br>开通VIP可提升上限','jinsom');
$upload_times=(int)get_user_meta($user_id,'upload_avatar_times',true);
if(is_vip($user_id)){
$jinsom_upload_limit=(int)jinsom_get_option('jinsom_upload_avatar_limit_vip');
}else{
$jinsom_upload_limit=(int)jinsom_get_option('jinsom_upload_avatar_limit');
}
}else{
$upload_tips=__('你当天的上传图片次数已达上限<br>开通VIP可提升上限','jinsom');
$upload_times=(int)get_user_meta($user_id,'upload_times',true);
if(is_vip($user_id)){
$jinsom_upload_limit=(int)jinsom_get_option('jinsom_upload_limit_vip');
}else{
$jinsom_upload_limit=(int)jinsom_get_option('jinsom_upload_limit');	
}	
}


if($upload_times>=$jinsom_upload_limit){
$data_arr['code']=3;
$data_arr['msg']=$upload_tips;
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


}



//接收参数
if(isset($_POST) and $_SERVER['REQUEST_METHOD']=="POST"){
$rand_number=rand(1000000,99999999);
$base64= str_replace(array('_','-'), array('/','+'),$base64);
$b64img= substr($base64, 0,100);
if(preg_match('/^(data:\s*image\/(\w+);base64,)/',$b64img, $matches)){
$type = $matches[2];
$file_name = $rand_number.'_'.time().".".$type;
$tmp=$path.$file_name;

$base64= str_replace($matches[1],'',$base64);
$base64= base64_decode($base64);
file_put_contents($tmp,$base64);

}

$jinsom_upload_style = jinsom_get_option('jinsom_upload_style');
if($jinsom_upload_style=='aliyun_oss'){//阿里云
require_once 'oss.php';
}elseif($jinsom_upload_style=='local'){//本地
require_once 'local.php';
}else if($jinsom_upload_style=='cos'){//腾讯云cos
require_once 'cos.php';
}else if($jinsom_upload_style=='qiniu'){//七牛云kodo
require_once 'qiniu.php';
}else if($jinsom_upload_style=='upyun'){//又拍云upyun
require_once 'upyun.php';
}else if($jinsom_upload_style=='obs'){//华为云obs
require_once 'obs.php';
}else if($jinsom_upload_style=='bos'){//百度云bos
require_once 'bos.php';
}else if($jinsom_upload_style=='ftp'){//远程ftp
require_once 'ftp.php';
}else if($jinsom_upload_style=='custom'){//自定义
require_once(do_shortcode($data['jinsom_upload_custom_obj_require_url']));
}

if($jinsom_upload_style!='local'){
unlink($path.$file_name);//删除本地临时文件
}

header('content-type:application/json');
echo json_encode($data_arr);


}
