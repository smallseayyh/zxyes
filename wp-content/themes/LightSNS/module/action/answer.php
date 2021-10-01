<?php
//问答采纳
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;

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


//追加悬赏
if(isset($_POST['type'])&&$_POST['type']=='add'){
$number=$_POST['number'];
$post_id=$_POST['post_id'];
$credit=get_user_meta($user_id,'credit',true);

if(!is_numeric($number)||$number<=0){
$data_arr['code']=0;
$data_arr['msg']='金额不合法！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



if($credit<$number){
$data_arr['code']=0;
$data_arr['msg']='你的'.jinsom_get_option('jinsom_credit_name').'不足！';
}else{
$answer_number=(int)get_post_meta($post_id,'answer_number',true);

$status=update_post_meta($post_id,'answer_number',$answer_number+$number);
if($status){
jinsom_update_credit($user_id,$number,'cut','add-answer','追加悬赏',1,'');
$data_arr['code']=1;
$data_arr['msg']='追加成功！';	
}else{
$data_arr['code']=0;
$data_arr['msg']='追加失败！';
}

}

}


//采纳答案
if(isset($_POST['type'])&&$_POST['type']=='adopt'){
$comment_id=$_POST['comment_id'];
$post_id=$_POST['post_id'];
$author_id=jinsom_get_post_author_id($post_id);
$answer_number=get_post_meta($post_id,'answer_number',true);


//置顶的回复不能采纳
if((int)get_post_meta($post_id,'up-comment',true)==$comment_id){
$data_arr['code']=0;
$data_arr['msg']='已经置顶的回复不能采纳！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if(get_post_meta($post_id,'answer_adopt',true)){
$data_arr['code']=0;
$data_arr['msg']='该问题已经采纳过了！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


$status=update_post_meta($post_id,'answer_adopt',$comment_id);

if($status){
//消息提醒
$get_comment=get_comment($comment_id);
$comment_user_id=$get_comment->user_id;
jinsom_add_tips($comment_user_id,$author_id,$post_id,'answer','你的回帖被采纳','被采纳');
jinsom_update_credit($comment_user_id,$answer_number,'add','answer-ok','你的回帖被采纳',1,'');

//更新采纳数
$user_adopt_number=(int)get_user_meta($comment_user_id,'user_adopt_number',true);
update_user_meta($comment_user_id,'user_adopt_number',$user_adopt_number+1);

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$author_id','$post_id','answer','$time','$comment_user_id')");


$data_arr['code']=1;
$data_arr['msg']='采纳成功！';	
}else{
$data_arr['code']=0;
$data_arr['msg']='采纳失败！';		
}

}

header('content-type:application/json');
echo json_encode($data_arr);
