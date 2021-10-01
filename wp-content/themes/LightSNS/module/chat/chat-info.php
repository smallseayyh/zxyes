<?php
//获取聊天的信息
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$type=strip_tags($_POST['type']);//one||group


//判断是否登录
if (!is_user_logged_in()){ 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if($type=='one'){//单对单聊天
$author_id=(int)$_POST['author_id'];

if($author_id==$user_id){
$data_arr['code']=0;
$data_arr['msg']=__('你不能和自己发起聊天！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$user_info = get_userdata($author_id);
$desc =$user_info->description;
$last_time=$user_info->latest_login;
if((time()-strtotime($last_time))<300){//5分钟之内为在线
$online_type_a=get_user_meta($author_id,'online_type',true);
if($online_type_a==1){
$online_status=__('手机在线','jinsom');
}else{
$online_status=__('电脑在线','jinsom');
}
}else{
$online_status=__('离线','jinsom');
}


$data_arr['code']=1;
$data_arr['msg']='获取成功！';
$data_arr['avatar']=jinsom_avatar($author_id,'40',avatar_type($author_id));
$data_arr['status']=$online_status;
$data_arr['count']=jinsom_get_chat_msg_count($user_id,$author_id);
$data_arr['nickname']=jinsom_nickname_link($author_id);
$data_arr['desc']=$desc?$desc:jinsom_get_option('jinsom_user_default_desc_b');


}


if($type=='group'){//群聊
$bbs_id=(int)$_POST['bbs_id'];



$data_arr['code']=1;
$data_arr['msg']='获取成功！';
$data_arr['avatar']=jinsom_get_bbs_avatar($bbs_id,0);
$data_arr['number']=jinsom_get_bbs_like_number($bbs_id);
$data_arr['name']='<a href="'.get_category_link($bbs_id).'" target="_blank">'.get_category($bbs_id)->name.'</a>';
$data_arr['desc']=strip_tags(get_term_meta($bbs_id,'desc',true));
$data_arr['notice']=strip_tags(get_term_meta($bbs_id,'bbs_notice',true));
// $data_arr['count']=get_term_meta($bbs_id,'bbs_notice',true);
}


header('content-type:application/json');
echo json_encode($data_arr);