<?php
//喜欢动态
require( '../../../../../wp-load.php');
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





if(isset($_POST['post_id'])){
$post_id=(int)$_POST['post_id'];
$author_id=jinsom_get_user_id_post($post_id);


if(get_post_type($post_id)=='goods'){
$type='goods';
}else{
$type='post';
}


//判断是否被对方拉黑
if(jinsom_is_blacklist($author_id,$user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='喜欢失败！对方已经将你加入黑名单！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


global $wpdb;
$table_name=$wpdb->prefix.'jin_like';
$like_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE user_id = $user_id AND post_id= $post_id and status=1 limit 1;");
if($like_count){//数据库里面有喜欢的记录
$wpdb->query("UPDATE $table_name SET status = 0 WHERE user_id = $user_id AND post_id= $post_id ;");
$data_arr['code']=2;
$data_arr['msg']='已经取消喜欢！';
}else{
$no_like_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE user_id = $user_id AND post_id= $post_id and status=0  limit 1;");
$time=current_time('mysql');
if($no_like_count==0&&$user_id){//第一次喜欢，添加喜欢提示
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,like_time,status) VALUES ('$user_id','$post_id','$type','$time',1)" );


if($type=='post'){
jinsom_add_tips(jinsom_get_user_id_post($post_id),$user_id,$post_id,'like','喜欢了你的内容','喜欢了');
}else{
jinsom_add_tips(jinsom_get_user_id_post($post_id),$user_id,$post_id,'like','收藏了你的商品','收藏了');
}

$like_post_credit=(int)jinsom_get_option('jinsom_like_post_credit');//喜欢文章获取的金币数量
$like_post_exp=(int)jinsom_get_option('jinsom_like_post_exp');//喜欢文章获取的经验值
$like_post_max=(int)jinsom_get_option('jinsom_like_post_max');//每天喜欢文章 上限
$like_post_times=(int)get_user_meta( $user_id,'like_post_times', true );//用户当天喜欢文章次数
if($like_post_times<$like_post_max){
jinsom_update_credit($user_id,$like_post_credit,'add','like-post','喜欢了内容',1,'');
jinsom_update_exp($user_id,$like_post_exp,'add','喜欢了内容');
}
update_user_meta($user_id,'like_post_times',$like_post_times+1);//更新喜欢次数上限

//记录今日喜欢次数
$today_like=(int)get_option('today_like');
update_option('today_like',$today_like+1);

$data_arr['code']=1;
$data_arr['msg']='喜欢成功！';//第一次喜欢这篇内容;

}else{//数据库里面有不喜欢的记录
$wpdb->query("UPDATE $table_name SET status=1,like_time='$time',type='$type' WHERE user_id = $user_id AND post_id= $post_id ;");	
$data_arr['code']=1;
$data_arr['msg']='喜欢成功！';//曾经喜欢过这篇内容，后面取消喜欢了;
}
}
	
}else{
$data_arr['code']=0;
$data_arr['msg']='参数异常！';
}

$data_arr['post_type']=get_post_type($post_id);

//兼容待优化
$count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE post_id= $post_id and status=1;");
update_post_meta($post_id,'like_number',$count);

header('content-type:application/json');
echo json_encode($data_arr);	