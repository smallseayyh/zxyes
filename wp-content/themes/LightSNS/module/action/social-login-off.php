<?php
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

//解绑社交登录
$user_id=$current_user->ID;
if(current_user_can('level_10')){
$author_id=$_POST['author_id'];
}else{
$author_id=$current_user->ID;
}
$user_info=get_userdata($author_id);
$type=strip_tags($_POST['type']);

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否黑名单
if(jinsom_is_black($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if(jinsom_get_reg_type('email')||jinsom_get_reg_type('phone')){
if(jinsom_get_reg_type('email')&&!jinsom_get_reg_type('phone')){//只开启了邮箱注册
if($user_info->user_email){//绑定了邮箱
jinsom_social_login_off($type,$author_id);
$data_arr['code']=1;
$data_arr['msg']='解绑成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='请先绑定邮箱！';
}
}elseif(jinsom_get_reg_type('phone')&&!jinsom_get_reg_type('email')){//只开启了手机号注册
if($user_info->phone){//绑定了手机号
jinsom_social_login_off($type,$author_id);
$data_arr['code']=1;
$data_arr['msg']='解绑成功！';
}else{
$data_arr['msg']='请先绑定手机号！';
}
}else{//手机号+邮箱注册都开启了
if($user_info->user_email||$user_info->phone){//绑定了邮箱或者手机号其中一个
jinsom_social_login_off($type,$author_id);
$data_arr['code']=1;
$data_arr['msg']='解绑成功！';
}else{
$data_arr['msg']='请先绑定手机号或邮箱！';
}
}
}else{//邮箱和手机号注册都没有开启
jinsom_social_login_off($type,$author_id);
$data_arr['code']=1;
$data_arr['msg']='解绑成功！';
}

header('content-type:application/json');
echo json_encode($data_arr);



//解绑社交登录
function jinsom_social_login_off($type,$author_id){
if($type=='qq'){//QQ
delete_user_meta($author_id,'qq_openid');
delete_user_meta($author_id,'qq_avatar');
delete_user_meta($author_id,'qq_unionid');	
}else if($type=='wechat'){//微信
delete_user_meta($author_id,'weixin_uid');
delete_user_meta($author_id,'weixin_pc_uid');
delete_user_meta($author_id,'wechat_unionid');
delete_user_meta($author_id,'wechat_avatar');
}else if($type=='weibo'){//微博
delete_user_meta($author_id,'weibo_uid');
delete_user_meta($author_id,'weibo_access_token');
delete_user_meta($author_id,'weibo_avatar');	
}else if($type=='github'){//github
delete_user_meta($author_id,'github_openid');
delete_user_meta($author_id,'github_avatar');	
}else if($type=='alipay'){//支付宝
delete_user_meta($author_id,'alipay_openid');
delete_user_meta($author_id,'alipay_avatar');	
}


//如果解绑的 是当前使用的头像类型，则切换为默认类型
$avatar_type=get_user_meta($author_id,'avatar_type',true);
if($avatar_type==$type){
$upload_avatar=get_user_meta($author_id,'customize_avatar',true);
if($upload_avatar){
update_user_meta($author_id,'avatar_type','upload');
}else{
update_user_meta($author_id,'avatar_type','default');  
}
}


}



