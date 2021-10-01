<?php 
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;

//判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


$comment_id=(int)$_POST['comment_id'];
$post_id=jinsom_get_comment_post_id($comment_id);
$author_id=jinsom_get_user_id_post($post_id);//作者id
$comment_data=get_comment($comment_id);
$comment_user_id=$comment_data->user_id;//评论用户id

if(get_comment_meta($comment_id,'reward',true)&&!jinsom_is_admin_x($user_id)){
$comment_arr['code']=0;
$comment_arr['msg']='打赏的回复不能被删除！';	
header('content-type:application/json');
echo json_encode($comment_arr);
exit();
}


//=======================删除动态评论============================
if(isset($_POST['type'])&&$_POST['type']=='post'){
if(jinsom_is_admin_x($user_id)||$user_id==$author_id||$user_id==$comment_user_id){//管理人员、作者、评论者、

if($user_id!=$comment_user_id){//提醒对方
jinsom_im_tips($comment_user_id,__('你评论的内容已经被删除','jinsom').'<br>评论内容：'.$comment_data->comment_content.'<br>删除者：'.jinsom_nickname($user_id).'</br>评论的文章：<a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}

if(jinsom_is_admin_x($user_id)){
$delete_content='<m class="delete"><i class="fa fa-trash"></i> 该评论已被管理团队删除。</m>';
}else{
if($user_id!=$comment_user_id){
$delete_content='<m class="delete"><i class="fa fa-trash"></i> 该评论已被作者删除。</m>';
}else{
$delete_content='<m class="delete"><i class="fa fa-trash"></i> 该评论已被用户自己删除。</m>';
}
}


jinsom_update_comment_conetnt($delete_content,$comment_id);	
update_comment_meta($comment_id,'delete',1);//更新该评论为已经删除
delete_comment_meta($comment_id,'img');
$comment_arr['code']=1;
$comment_arr['delete_content']=$delete_content;
$comment_arr['msg']='评论已删除！';


//扣除对应的金币和经验
$publish_credit = (int)jinsom_get_option('jinsom_comment_post_credit');//每次评论可获得的金币
$publish_exp = (int)jinsom_get_option('jinsom_comment_post_exp');//每次评论可获得的经验
jinsom_update_exp($comment_user_id,$publish_exp,'cut','评论被删除，扣除');
if($publish_credit<0){
jinsom_update_credit($comment_user_id,$publish_credit,'add','comment-delete','评论被删除，返还',1,'');
}else{
jinsom_update_credit($comment_user_id,$publish_credit,'cut','comment-delete','评论被删除，扣除',1,''); 
}

}else{
$comment_arr['code']=0;
$comment_arr['msg']='你没有权限！';
}

}

//==============================删除一级回帖----一级=====================================
if(isset($_POST['type'])&&$_POST['type']=='bbs-post'){
$bbs_id=(int)$_POST['bbs_id'];

//获取版主和管理人员
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
if(empty($admin_a)){$admin_a=9909999;}
if(empty($admin_b)){$admin_b=9909999;}
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
$is_bbs_admin=(jinsom_is_admin_x($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
$comment_data=get_comment($comment_id);

$answer_adopt=get_post_meta($post_id,'answer_adopt',true);
if($answer_adopt!==$comment_id){//评论该楼层是否是被采纳的帖子
if($is_bbs_admin||$user_id==$author_id||$user_id==$comment_user_id){//管理人员、作者、评论者、大小版主


//如果是活动报名的回复，则同时删除活动报名人数
$comment_activity=get_comment_meta($comment_id,'activity',true);
if($comment_activity){
$activity=get_post_meta($post_id,'activity',true);
$activity_arr=explode(",",$activity);
$key = array_search($comment_user_id,$activity_arr);
array_splice($activity_arr,$key,1);
$str= implode(",",$activity_arr);
update_post_meta($post_id,'activity',$str);	
}

if($user_id!=$comment_user_id){//提醒对方
jinsom_im_tips($comment_user_id,__('你回复的内容已经被删除','jinsom').'<br>回复内容：'.strip_tags($comment_data->comment_content).'<br>删除者：'.jinsom_nickname($user_id).'</br>回复的帖子：<a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}


wp_delete_comment($comment_id);
delete_comment_meta($comment_id,'img');
$comment_arr['code']=1;
$comment_arr['msg']='评论已删除！';

//扣除对应的金币和经验
$publish_credit = (int)get_term_meta($bbs_id,'bbs_credit_reply_number',true);//每次回帖可获得的金币
$publish_exp = (int)get_term_meta($bbs_id,'bbs_exp_reply_number',true);//每次评回帖可获得的经验
jinsom_update_exp($comment_user_id,$publish_exp,'cut','回帖被删除，扣除');
if($publish_credit<0){
jinsom_update_credit($comment_user_id,$publish_credit,'add','comment-bbs-delete','回帖被删除，返还',1,'');
}else{
jinsom_update_credit($comment_user_id,$publish_credit,'cut','comment-bbs-delete','回帖被删除，扣除',1,'');
}

}else{
$comment_arr['code']=0;
$comment_arr['msg']='你没有权限！';
}


}else{
$comment_arr['code']=0;
$comment_arr['msg']='被采纳的帖子不能被删除！';	
}
}



//=========================删除二级回帖----二级===============================

if(isset($_POST['type'])&&$_POST['type']=='bbs-post-floor'){
$bbs_id=(int)$_POST['bbs_id'];

//获取版主和管理人员
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
if(empty($admin_a)){$admin_a=9909999;}
if(empty($admin_b)){$admin_b=9909999;}
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
$is_bbs_admin=(jinsom_is_admin_x($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
$comment_data=get_comment($comment_id);

$floor_comment_id=jinsom_get_comment_floor_user_id($comment_id);//获取楼主id

$comments_author=jinsom_get_comments_author_id($comment_id);//评论的当前文章的作者id
if($is_bbs_admin||$user_id==$comments_author||$user_id==$author_id||$user_id==$floor_comment_id){//管理人员、作者、评论者、楼主、大小版主

if($user_id!=$comment_user_id){
jinsom_im_tips($comment_user_id,__('你二级回帖的内容已经被删除','jinsom').'<br>回帖内容：'.$comment_data->comment_content.'<br>删除者：'.jinsom_nickname($user_id).'</br>回复的帖子：<a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}

wp_delete_comment($comment_id);
$comment_arr['code']=1;
$comment_arr['msg']='评论已删除！';

//扣除对应的金币和经验
$publish_credit = (int)get_term_meta($bbs_id,'bbs_credit_reply_number',true);//每次回帖可获得的金币
$publish_exp = (int)get_term_meta($bbs_id,'bbs_exp_reply_number',true);//每次评回帖可获得的经验
jinsom_update_exp($comment_user_id,$publish_exp,'cut','楼层回帖被删除，扣除');
if($publish_credit<0){
jinsom_update_credit($comment_user_id,$publish_credit,'add','comment-bbs-delete','楼层回帖被删除，返还',1,''); 
}else{
jinsom_update_credit($comment_user_id,$publish_credit,'cut','comment-bbs-delete','楼层回帖被删除，返还',1,'');
}

}else{
$comment_arr['code']=0;
$comment_arr['msg']='你没有权限！';	
}
}


header('content-type:application/json');
echo json_encode($comment_arr);