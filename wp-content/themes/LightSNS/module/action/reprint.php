<?php 
//转发内容
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

$user_id=$current_user->ID;
$user_publish_times = (int)get_user_meta($user_id,'publish_post_times',true);//个人当天已发布的动态次数
$ip = $_SERVER['REMOTE_ADDR'];
$time = current_time('mysql');

if(!jinsom_get_option('jinsom_publish_reprint_on_off')){
$data_arr['code']=0;
$data_arr['msg']='转发功能已经关闭！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


$comment_a=$_POST['comment_a'];
$post_id=(int)$_POST['post_id'];
$author_id=jinsom_get_user_id_post($post_id);	

$post_power=(int)get_user_meta($post_id,'post_power',true);
if($post_power==1||$post_power==2||$post_power==3||$post_power==4||$post_power==5){
$data_arr['code']=0;
$data_arr['msg']='该内容不支持转发！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//=========未登录
if (!is_user_logged_in()){
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//=========判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//需要绑定手机号才能使用
$bind_phone_use_for=jinsom_get_option('jinsom_bind_phone_use_for');
if($bind_phone_use_for&&in_array("publish",$bind_phone_use_for)&&!current_user_can('level_10')){
if(!get_user_meta($user_id,'phone',true)){
$data_arr['code']=2;
$data_arr['user_id']=$user_id;
$data_arr['msg']='你需要绑定手机号码才能进行操作！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}
}

//需要绑定邮箱才能使用
$bind_email_use_for=jinsom_get_option('jinsom_bind_email_use_for');
if($bind_email_use_for&&in_array("publish",$bind_email_use_for)&&!current_user_can('level_10')){
$user_info=get_userdata($user_id);
if(!$user_info->user_email){
$data_arr['code']=4;
$data_arr['user_id']=$user_id;
$data_arr['msg']='你需要绑定邮箱才能进行操作！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}
}


if(jinsom_is_blacklist($author_id,$user_id)){
$data_arr['code']=0;
$data_arr['msg']='转发失败！对方已经将你加入黑名单！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}



$content=htmlspecialchars($_POST['content']);
$content_number=mb_strlen($content,'utf-8');//判断分享的字数


if($content_number>500){
$data_arr['code']=0;
$data_arr['msg']='转发的字数不能超过500字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



//敏感词过滤
if(jinsom_get_option('jinsom_baidu_filter_on_off')&&in_array("publish",jinsom_get_option('jinsom_baidu_filter_use_for'))&&!jinsom_is_admin($user_id)){
$filter=jinsom_baidu_filter($content);
if($filter['conclusionType']==2){
$data_arr['code']=0;
$a=$filter['data'][0]['hits'][0]['words'][0];
if($a==''){$a=$filter['data'][0]['msg'];}
$data_arr['msg']='内容含有敏感词：'.$a;
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}


//一级转发
if(isset($_POST['type'])&&$_POST['type']=='a'){
$publish_data = array(
'post_content' => $content, 
'post_status' => 'publish',
'comment_status'=>'open'
);
$reprint_post_id = wp_insert_post($publish_data);

if($reprint_post_id){
update_post_meta($reprint_post_id,'reprint_post_id',$post_id);
update_post_meta($reprint_post_id,'post_type','reprint');

//更新来自
if(wp_is_mobile()){
update_post_meta($reprint_post_id,'post_from','mobile');
}else{
update_post_meta($reprint_post_id,'post_from','pc'); 
}

//评论给当前的内容
if($comment_a=='true'){
$comment_data = array(
'comment_post_ID' => $post_id,
'comment_content' => '<i class="jinsom-reprint-icon"></i><i class="jinsom-icon jinsom-zhuanzai"></i> '.$content,
'user_id' => $user_id,
'comment_author_IP'=>$ip,
'comment_date' => $time,
'comment_approved' => 1,
);
$comment_id=wp_insert_comment($comment_data); 

//更新评论来自
if(wp_is_mobile()){
update_comment_meta($comment_id,'from',1);//手机端
}else{
update_comment_meta($comment_id,'from',2);  
}
}

update_comment_meta($comment_id,'comment_type','reprint');//更新为转发的评论

//当天发布动态次数累加1
update_user_meta($user_id,'publish_post_times',$user_publish_times+1);


if($author_id!=$user_id){//判断当文章作者不是本人才触发提醒。
jinsom_add_tips($author_id,$user_id,$post_id,'reprint','转发了你的内容','转发了');
}
//原文的分享次数+1
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);
update_post_meta($post_id,'reprint_times',$reprint_times+1);
$data_arr['code']=1;
$data_arr['msg']='转发成功！';


//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$reprint_post_id','reprint','$time')");


}else{
$data_arr['code']=0;
$data_arr['msg']='转发失败！';
}

}




//======================二级转发======================
if(isset($_POST['type'])&&$_POST['type']=='b'){

$comment_b=$_POST['comment_b'];

$old_content='//<jin class="jinsom-post-at" data="'.jinsom_userlink($author_id).'" onclick="jinsom_post_link(this);">@'.get_user_meta($author_id,'nickname',true).'</jin>:'.jinsom_get_post_content($post_id);


$old_post_id=get_post_meta($post_id,'reprint_post_id',true);
$publish_data = array(
'post_content' => $content.$old_content, 
'post_status' => 'publish',
'comment_status'=>'open'
);
$reprint_post_id = wp_insert_post($publish_data);

if($reprint_post_id){
update_post_meta($reprint_post_id,'reprint_post_id',$old_post_id);
update_post_meta($reprint_post_id,'post_type','reprint');

//更新来自
if(wp_is_mobile()){
update_post_meta($reprint_post_id,'post_from','mobile');
}else{
update_post_meta($reprint_post_id,'post_from','pc'); 
}

//评论给当前的内容
if($comment_a=='true'){
$comment_data = array(
'comment_post_ID' => $post_id,
'comment_content' => '<i class="jinsom-reprint-icon"></i><i class="jinsom-icon jinsom-zhuanzai"></i> '.$content,
'user_id' => $user_id,
'comment_author_IP'=>$ip,
'comment_date' => $time,
'comment_approved' => 1,
);
$comment_id=wp_insert_comment($comment_data); 

//更新评论来自
if(wp_is_mobile()){
update_comment_meta($comment_id,'from',1);//手机端
}else{
update_comment_meta($comment_id,'from',2);  
}

update_comment_meta($comment_id,'comment_type','reprint');//更新为转发的评论
}

//评论给原文内容
if($comment_b=='true'){
$data = array(
'comment_post_ID' => $old_post_id,
'comment_content' => '<i class="jinsom-reprint-icon"></i><i class="jinsom-icon jinsom-zhuanzai"></i> '.$content,
'comment_parent' => 0,
'user_id' =>$user_id,
'comment_date' => $time,
'comment_approved' => 1,
);
$comment_id_source=wp_insert_comment($data); 
if(wp_is_mobile()){
update_comment_meta($comment_id_source,'from',1);//手机端
}else{
update_comment_meta($comment_id_source,'from',2);  
}
update_comment_meta($comment_id_source,'reprint',1);//更新为转发的评论
}
//原文的分享次数+1
$reprint_times=(int)get_post_meta($old_post_id,'reprint_times',true);
update_post_meta($old_post_id,'reprint_times',$reprint_times+1);

//当天发布动态次数累加1
update_user_meta($user_id,'publish_post_times',$user_publish_times+1);


if($author_id!=$user_id){//判断当文章作者不是本人才触发提醒。
jinsom_add_tips($author_id,$user_id,$post_id,'reprint','转发了你的内容','转发了');
}

$data_arr['code']=1;
$data_arr['msg']='转发成功！';

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$reprint_post_id','reprint-floor','$time')");


}else{
$data_arr['code']=0;
$data_arr['msg']='转发失败！';
}

}



header('content-type:application/json');
echo json_encode($data_arr);