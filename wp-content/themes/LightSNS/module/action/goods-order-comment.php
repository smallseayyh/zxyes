<?php
//商品评价
require( '../../../../../wp-load.php' );
$post_id=(int)$_POST['post_id'];
$star=(int)$_POST['star'];
$content=strip_tags($_POST['content']);
$trade_no=strip_tags($_POST['trade_no']);
$user_id = $current_user->ID;

if($star<=0||$star>5){
$star=5;
}

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


if($content==''){
$data_arr['code']=0;
$data_arr['msg']= __('评价内容不能为空！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$content_number=mb_strlen($content,'utf-8');
if($content_number>300){
$data_arr['code']=0;
$data_arr['msg']= __('评价字数不能超过300字！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}




//判断是否存在该订单
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$status=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name where trade_no='$trade_no' and user_id=$user_id and status=2 ;");
if(!$status){
$data_arr['code']=0;
$data_arr['msg']= __('订单信息异常！','jinsom');
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
'comment_karma'=>9,
'comment_date' => $time
);
$comment_id=wp_insert_comment($data); 

if($comment_id){
update_comment_meta($comment_id,'star',$star);
update_comment_meta($comment_id,'comment_type','goods_comment');

//来自终端
if(wp_is_mobile()){
update_comment_meta($comment_id,'from','mobile');//手机端
}

$star_percent=(int)get_post_meta($post_id,'star_percent',true);
if(!$star_percent){$star_percent=100;}
$args = array(
'status'=>'approve',
'type' => 'comment',
'karma'=>9,
'no_found_rows' =>false,
'post_id' => $post_id
);
$args['count']=true;
$comment_count=get_comments($args);
$new_star_percent=(int)(($star*20+$comment_count*$star_percent)/($comment_count+1));
update_post_meta($post_id,'star_percent',$new_star_percent);

//更新订单信息
$wpdb->query("UPDATE $table_name SET status=3,comment_id=$comment_id  WHERE trade_no = '$trade_no';");


//记录实时动态
global $wpdb;
$table_name_now=$wpdb->prefix.'jin_now';
$wpdb->query( "INSERT INTO $table_name_now (user_id,post_id,type,do_time) VALUES ('$user_id','$post_id','goods-comment','$time')");


$data_arr['code']=1;
$data_arr['msg']= __('评价成功！','jinsom');
}else{
$data_arr['code']=0;
$data_arr['msg']= __('评价失败！','jinsom');
}



header('content-type:application/json');
echo json_encode($data_arr);