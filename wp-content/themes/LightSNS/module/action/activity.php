<?php
//活动报名
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id = $current_user->ID;
$content=$_POST['content'];
$post_id=$_POST['post_id'];
$author_id=jinsom_get_user_id_post($post_id);
$activity_price=(int)get_post_meta($post_id,'activity_price',true);
$user_credit=(int)get_user_meta($user_id,'credit',true);

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



if($user_id==$author_id){
$data_arr['code']=0;
$data_arr['msg']='你不能报名自己发布的活动！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 	
}


//需要绑定手机号才能使用
$bind_phone_use_for=jinsom_get_option('jinsom_bind_phone_use_for');
if($bind_phone_use_for&&in_array("activity",$bind_phone_use_for)&&!current_user_can('level_10')){
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
if($bind_email_use_for&&in_array("activity",$bind_email_use_for)&&!current_user_can('level_10')){
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


//判断是否已经报名了
if(jinsom_is_join_activity($user_id,$post_id)){
$data_arr['code']=0;
$data_arr['msg']='你已经参与了！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 	
}


if($activity_price>0){
if($user_credit<$activity_price){
$data_arr['code']=0;
$data_arr['msg']='你的'.jinsom_get_option('jinsom_credit_name').'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 	
}
}


if(isset($_POST['content'])&&isset($_POST['post_id'])){

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

//来自终端
if(wp_is_mobile()){
update_comment_meta($comment_id,'from','mobile');//手机端
}else{
update_comment_meta($comment_id,'from','pc');  
}

update_post_meta($post_id, 'last_comment_time', time());//插入最后回复字段	

//更新楼层数
if(get_post_meta($post_id,'bbs_floor',true)){//判断是否存在帖子总楼层数
$bbs_floor=get_post_meta($post_id,'bbs_floor',true);//获取目前的楼层数
update_comment_meta($comment_id,'comment_floor',$bbs_floor+1);//写入当前评论的楼层
update_post_meta($post_id,'bbs_floor',$bbs_floor+1);//总楼层累加
}else{
$comment_floor_args = array(
'post_id' => $post_id,
'parent'=> 0,
'status' =>'approve',
'count'=> true,
);
$comment_number = get_comments($comment_floor_args);//获取所有一级楼层数量  
update_comment_meta($comment_id,'comment_floor',$comment_number+1);//写入当前评论的楼层
update_post_meta($post_id,'bbs_floor',$comment_number+1);//同步楼层数
}

update_comment_meta($comment_id,'activity',1);//记录评论特性为报名帖子

if($activity_price>0){//如果活动报名需要费用
jinsom_update_credit($author_id,$activity_price,'add','activity','别人参与了你的活动',1,'');
jinsom_update_credit($user_id,$activity_price,'cut','activity','你参与了活动',1,'');
}

$activity=get_post_meta($post_id,'activity',true);//活动报名人数
if($activity){
$activity_arr=explode(",",$activity);
}else{
$activity_arr=array();	
}
array_push($activity_arr,$user_id);
$str= implode(",",$activity_arr);
$status=update_post_meta($post_id,'activity',$str);	

if($status){
$data_arr['code']=1;
$data_arr['msg']='提交成功！';

jinsom_add_tips($author_id,$user_id,$post_id,'activity','参与了你发布的活动','参与了');

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$post_id','activity','$time')");


}else{
$data_arr['code']=0;
$data_arr['msg']='提交失败！';	
}




}else{
$data_arr['code']=0;
$data_arr['msg']='提交失败！';
}



}else{
$data_arr['code']=0;
$data_arr['msg']='数据异常！';
}
header('content-type:application/json');
echo json_encode($data_arr);