<?php
//直播 互动讨论
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$post_id=(int)$_POST['post_id'];
// $author_id=jinsom_get_user_id_post($post_id);
// $author_nickname=get_user_meta($author_id,'nickname',true);


//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if(get_user_meta($user_id,'user_power',true)==4){
$data_arr['code']=0;
$data_arr['msg']='你的帐号已经被限制登录！1003';
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


//判断内容是否为空
if(jinsom_trimall($_POST['content'])==''){
$data_arr['code']=0;
$data_arr['msg']='请输入内容！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



//需要绑定手机号才能使用
$bind_phone_use_for=jinsom_get_option('jinsom_bind_phone_use_for');
if($bind_phone_use_for&&in_array("comment",$bind_phone_use_for)&&!current_user_can('level_10')){
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
if($bind_email_use_for&&in_array("comment",$bind_email_use_for)&&!current_user_can('level_10')){
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


$content_number=mb_strlen($_POST['content'],'utf-8');
$jinsom_comment_words_max=100;
if($content_number>$jinsom_comment_words_max){
$data_arr['code']=0;
$data_arr['msg']='最多只能回复'.$jinsom_comment_words_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}




if(isset($_POST['content'])&&isset($_POST['post_id'])){



$comment_content=htmlspecialchars($_POST['content']);//过滤标签
$comment_content = str_replace(array("\r", "\n", "\r\n"), "<br>", $comment_content);//处理换行

//敏感词过滤
if(jinsom_get_option('jinsom_baidu_filter_on_off')&&in_array("live",jinsom_get_option('jinsom_baidu_filter_use_for'))&&!jinsom_is_admin($user_id)){
$filter=jinsom_baidu_filter($comment_content);
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




$time = current_time('mysql');
$ip = $_SERVER['REMOTE_ADDR'];
$data = array(
'comment_post_ID' =>$post_id,
'comment_content' => $comment_content,
'user_id' => $user_id,
'comment_author_IP'=>$ip,
'comment_date' => $time
);
$comment_id=wp_insert_comment($data); 
if($comment_id){


//来自终端
if(wp_is_mobile()){
add_comment_meta($comment_id,'from','mobile');//手机端
}


update_user_meta($user_id,'last_comment_time',time());//记录用户最后评论的时间戳



$data_arr['code']=1;
$data_arr['msg']='回复成功！';
$data_arr['id']=$comment_id;
$data_arr['content']=convert_smilies(jinsom_autolink($comment_content)); //评论成功
$data_arr['nickname']=jinsom_nickname($user_id).jinsom_lv($user_id).jinsom_vip($user_id).jinsom_honor($user_id); //评论成功
$data_arr['avatar']=jinsom_avatar($user_id,'40',avatar_type($user_id));
$data_arr['content_a']=strip_tags(convert_smilies(jinsom_autolink($comment_content))); //评论成功

}else{
$data_arr['code']=0;//评论失败
$data_arr['msg']='评论失败！';
}



}else{
$data_arr['code']=0;
$data_arr['msg']='数据异常！';
}

$data_arr['test']=$user_comment_times;
header('content-type:application/json');
echo json_encode($data_arr);