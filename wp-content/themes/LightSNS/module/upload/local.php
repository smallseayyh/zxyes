<?php 
$file_url=content_url().'/uploads'.$path_url.$file_name;

if($upload_type=='words-base64'||$upload_type=='video-img-base64'||$upload_type=='avatar-base64'){//base64
if($upload_type=='avatar-base64'){
update_user_meta($user_id,'avatar_type','upload');
update_user_meta($user_id,'customize_avatar',$file_url);
update_user_meta($user_id,'upload_avatar_times',$upload_times+1);
}

$data_arr['code']=1;
$data_arr['name']=$name;
$data_arr['file_url']=$file_url;    
$data_arr['self']=$self; 
$data_arr['msg']='上传成功！'; 


}else{//电脑端上传

if($upload_type=='words-img'){
$thum=imagecropper($tmp,280,280,$user_id,$ext,$file_name);
$file_thum_url=content_url().'/uploads/user_files/'.$user_id.'/publish/words/thumbnail/'.$file_name;	
$data_arr['file_thum_url']=$file_thum_url;
$data_arr['status']=$thum;
}

if(move_uploaded_file($tmp,$path.$file_name)){
 
if($upload_type=='avatar'){//上传头像
update_user_meta($user_id,'avatar_type','upload');
update_user_meta($user_id,'customize_avatar',$file_url);
update_user_meta($user_id,'upload_avatar_times',$upload_times+1);//更新用户当天上传次数
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
$data_arr['msg']='本地上传失败！';
}


}