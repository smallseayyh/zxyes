<?php
//实时获取直播评论
require( '../../../../../wp-load.php' );
// if(is_user_logged_in()){
//jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;

//ajax长轮询
if(!isset($_POST['count'])){
echo json_encode(array("code"=>3));
exit(); 
}
$count=(int)$_POST['count'];
$post_id=(int)$_POST['post_id'];


set_time_limit(0);//无限请求超时时间  
$i=0;  
while (true){
sleep(2);//2秒  
$i++;  


//$new_count=get_comments_number($post_id);//获取最新的评论数
global $wpdb;
$new_count=(int)$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID=$post_id;");




$new_count_a=$new_count-$count;//减去轮询之前的消数量
if($new_count_a>0){
$danmu_arr=array();
$args = array(
'status'=>'approve',
'type' => 'comment',
'karma'=>0,
'no_found_rows' =>false,
'number' =>$new_count_a,
'orderby' => 'comment_date',
'post_id' => $post_id
);
$comment_data=get_comments($args);
$msg='';
if(!empty($comment_data)){ 
foreach (array_reverse($comment_data) as $comment_datas) {
$comment_id=$comment_datas->comment_ID;
$comment_user_id = $comment_datas->user_id;
$comment_content = $comment_datas->comment_content;
$msg.='
<li>
<div class="left"><a href="'.jinsom_userlink($comment_user_id).'" target="_blank">'.jinsom_avatar($comment_user_id,'40',avatar_type($comment_user_id)).'</a></div>
<div class="right">
<div class="name">'.jinsom_nickname($comment_user_id).jinsom_lv($comment_user_id).jinsom_vip($comment_user_id).jinsom_honor($comment_user_id).'</div>
<div class="content">'.convert_smilies($comment_content).'</div>
</div>
</li>
';
if(strip_tags(convert_smilies($comment_content))){
array_push($danmu_arr,strip_tags(convert_smilies($comment_content)));
}
}
}

echo json_encode(array("code"=>2,"count"=>$new_count,"msg"=>$msg,"id"=>rand(10000,9999999),"danmu"=>$danmu_arr));
exit();


}//是否有新消息


if($i==14){//28秒后 强制退出轮询s
echo json_encode(array("code"=>4,"count"=>$new_count,"count_a"=>$new_count_a));
exit();  
} 


}//while



// }//是否登录