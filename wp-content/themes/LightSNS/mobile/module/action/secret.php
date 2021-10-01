<?php
//喜欢秘密
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
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
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



if(isset($_POST['post_id'])){
$post_id=(int)$_POST['post_id'];
$author_id=jinsom_get_user_id_post($post_id);



if($type=='like'){
$nice=get_post_meta($post_id,'nice',true);
if($nice){
$nice_arr=explode(",",$nice);
if(in_array($user_id,$nice_arr)){
$data_arr['code']=0;
$data_arr['msg']='你已经点赞了！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}else{
array_push($nice_arr,$user_id);
$nice_str=implode(",", $nice_arr);
update_post_meta($post_id,'nice',$nice_str);

$nice_num=(int)get_post_meta($post_id,'nice_num',true);
update_post_meta($post_id,'nice_num',$nice_num+1);	
}

}else{
update_post_meta($post_id,'nice_num',1);
update_post_meta($post_id,'nice',$user_id);
}

jinsom_add_tips($author_id,$user_id,$post_id,'secret','你发布的秘密有新的点赞','赞了');
$data_arr['code']=1;
$data_arr['msg']='点赞成功！';
}else if($type=='delete'){//删除秘密

if(jinsom_is_admin($user_id)||$user_id==$author_id){
$status=wp_delete_post($post_id);
if($status){
$data_arr['code']=1;
$data_arr['msg']='删除成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='删除失败！';	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';	
}

}else if($type=='comment-delete'){//删除评论
$comment_id=(int)$_POST['comment_id'];
$comment_data=get_comment($comment_id);
$comment_user_id=$comment_data->user_id;//评论用户id

if(jinsom_is_admin($user_id)||$user_id==$author_id||$user_id==$comment_user_id){
$status=wp_delete_comment($comment_id);
if($status){
$data_arr['code']=1;
$data_arr['msg']='删除成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='删除失败！';	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';	
}


}else if($type=='comment'){//评论
$content=strip_tags($_POST['content']);
$content_number=mb_strlen($content,'utf-8');
$content_max=100;

if(jinsom_trimall($content)==''){
$data_arr['code']=0;
$data_arr['msg']='评论内容不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if($content_number>$content_max&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='评论内容不能超过'.$content_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

$time = current_time('mysql');
$ip = $_SERVER['REMOTE_ADDR'];
$data = array(
'comment_post_ID' =>$post_id,
'comment_content' => $content,
'user_id' => $user_id,
'comment_author_IP'=>$ip,
'comment_date' => $time
);
$comment_id=wp_insert_comment($data); 
if($comment_id){
update_post_meta($post_id,'last_comment_time', time());

$jinsom_secret_rand_name=jinsom_get_option('jinsom_secret_rand_name');
if($jinsom_secret_rand_name){
$name_arr=explode(",",$jinsom_secret_rand_name);
$rand=rand(0,count($name_arr));
$name=$name_arr[$rand];
}else{
$name='匿名';	
}

update_comment_meta($comment_id,'secret_name',$name);//秘密昵称
if(jinsom_get_option('jinsom_secret_rand_avatar_url')){//开启随机头像
$rand_avatar_number=jinsom_get_option('jinsom_secret_rand_avatar_number');
$avatar_url=jinsom_get_option('jinsom_secret_rand_avatar_url').rand(1,$rand_avatar_number).'.png';
}else{
if(jinsom_get_option('jinsom_default_avatar')){
$avatar_url=jinsom_get_option('jinsom_default_avatar');	
}else{
$avatar_url=get_template_directory_uri().'/images/default-cover.jpg';		
}
}
update_comment_meta($comment_id,'secret_avatar',$avatar_url);//秘密头像


$html='
<li id="jinsom-secret-comment-'.$comment_id.'">
<div class="left">
<div class="avatarimg"><img src="'.$avatar_url.'" class="avatar"></div>
<div class="name">'.$name_arr[$rand].'</div>
</div>
<div class="right">
<div class="content">
<a>'.$content.'</a>
<div class="after"></div>
</div>
<div class="bar">
<span class="delete" onclick="jinsom_secret_comment_delete('.$comment_id.')">删除</span>	
<span class="time">刚刚</span>	
</div>
</div>
</li>
';




jinsom_add_tips($author_id,$user_id,$post_id,'secret','你发布的秘密有新的评论','评论了');
$data_arr['code']=1;
$data_arr['html']=$html;
$data_arr['comment_id']=$comment_id;
$data_arr['msg']='评论成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='评论失败！';
}

}




	
}else{
$data_arr['code']=0;
$data_arr['msg']='参数异常！';
}



header('content-type:application/json');
echo json_encode($data_arr);	