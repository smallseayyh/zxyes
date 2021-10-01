<?php
//评论点赞
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id = $current_user->ID;



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

if(isset($_POST['comment_id'])){

$user_id=$current_user->ID;
$comment_like_post_credit = (int)jinsom_get_option('jinsom_comment_like_post_credit');//评论点赞获取的金币
$comment_like_post_exp = (int)jinsom_get_option('jinsom_comment_like_post_exp');//评论点赞获取的经验
$comment_like_max = (int)jinsom_get_option('jinsom_comment_like_max');//评论点赞每天上限次数
$comment_like_times = (int)get_user_meta( $user_id, 'comment_like_times', true );//用户当天评论点赞次数
$comment_id=$_POST['comment_id'];
$post_id=jinsom_get_comment_post_id($comment_id);
$comment_author_id=jinsom_get_comments_author_id($comment_id);


//判断是否被对方拉黑
if(jinsom_is_blacklist($comment_author_id,$user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='点赞失败！对方已经将你加入黑名单！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


$type=$_POST['type'];
global $wpdb;
$table_name = $wpdb->prefix . 'jin_comment_up';
if($type==2){
$wpdb->query( "INSERT INTO $table_name (comment_id,user_id,status) VALUES ('$comment_id','$user_id',1)" );
if($comment_author_id!=$user_id){
jinsom_add_tips($comment_author_id,$user_id,$post_id,'comment-up','赞了你的评论',$comment_id);
}
if($comment_like_times<$comment_like_max){
jinsom_update_credit($user_id,$comment_like_post_credit,'add','comment-up','点赞了一条评论',1,'');  
jinsom_update_exp($user_id,$comment_like_post_exp,'add','点赞了一条评论');
}
update_user_meta($user_id,'comment_like_times',$comment_like_times+1);//更新评论点赞次数上限
}

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$user_id','$post_id','comment_up','$time','$comment_author_id')");


$data_arr['code']=1;
$data_arr['msg']='点赞成功！';

}else{
$data_arr['code']=0;
$data_arr['msg']='参数异常！';
}
header('content-type:application/json');
echo json_encode($data_arr);