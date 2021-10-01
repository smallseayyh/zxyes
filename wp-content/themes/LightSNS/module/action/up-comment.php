<?php 
//置顶评论
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$bbs_id=(int)$_POST['bbs_id'];
$comment_id=(int)$_POST['comment_id'];
$post_id=jinsom_get_comment_post_id($comment_id);
$author_id=jinsom_get_user_id_post($post_id);


if($bbs_id){//帖子


if((int)get_post_meta($post_id,'answer_adopt',true)==$comment_id){
$data_arr['code']=0;
$data_arr['msg']='已经采纳的回复不能置顶！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//获取版主和管理人员
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
if(empty($admin_a)){$admin_a=9909999;}
if(empty($admin_b)){$admin_b=9909999;}
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
if($is_bbs_admin||$user_id==$author_id){//管理人员、作者、评论者、大小版主
$up_comment=get_post_meta($post_id,'up-comment',true);
if($up_comment==$comment_id){
delete_post_meta($post_id,'up-comment');
$data_arr['code']=2;
$data_arr['msg']='已经取消该评论置顶！';
$data_arr['name']=__('置顶','jinsom');
}else{
update_post_meta($post_id,'up-comment',$comment_id);
$data_arr['code']=1;
$data_arr['msg']='已经置顶该评论！';	
$data_arr['name']=__('取消置顶','jinsom');
}
}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';
$data_arr['name']=__('置顶','jinsom');
}
}else{//动态类
if(jinsom_is_admin($user_id)||$user_id==$author_id){//管理人员、作者
$up_comment=get_post_meta($post_id,'up-comment',true);
if($up_comment==$comment_id){
delete_post_meta($post_id,'up-comment');
$data_arr['code']=2;
$data_arr['msg']='已经取消该评论置顶！';
$data_arr['name']=__('置顶','jinsom');
}else{
update_post_meta($post_id,'up-comment',$comment_id);
$data_arr['code']=1;
$data_arr['msg']='已经置顶该评论！';	
$data_arr['name']=__('取消置顶','jinsom');
}
}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';
$data_arr['name']=__('置顶','jinsom');
}
}


header('content-type:application/json');
echo json_encode($data_arr);