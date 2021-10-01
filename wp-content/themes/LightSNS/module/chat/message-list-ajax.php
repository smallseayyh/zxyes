<?php
require( '../../../../../wp-load.php' );
if (is_user_logged_in()) {
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;

//ajax长轮询
if(!isset($_POST['count'])){
echo json_encode(array("code"=>3));	
exit(); 
}
$count=$_POST['count'];
$chat_user_id=$_POST['user_id'];


set_time_limit(0);//无限请求超时时间  
$i=0;  
while (true){
sleep(2);//2秒  
$i++;  


$new_count=jinsom_get_chat_msg_count($user_id,$chat_user_id);//获取数据库最新的消息数目，对方给我发我的
$new_count_a=$new_count-$count;//减去轮询之前的消息  得出对方在这个时间段给我发的消息数
if($new_count_a>0){

global $wpdb;
$table_name = $wpdb->prefix . 'jin_message';
$get_msg=$wpdb->get_results("SELECT * FROM $table_name WHERE from_id = '$chat_user_id' AND user_id='$user_id'  ORDER BY msg_date DESC LIMIT 0,$new_count_a;");	


$msg='';
foreach (array_reverse($get_msg) as $get_msgs) {

// $status=$get_msgs->status;
// if($status!=0){//获取的消息 如果已经被读，则退出这一次ajax
// echo json_encode(array("code"=>5,"count"=>$new_count));
// exit();
// }



$time1=strtotime($get_msgs->msg_date);
$time2=time();
$time3=$time2-$time1;
if($time3<300){
$chat_time=date('H:i:s',$time1);
}elseif($time3>=300&&$time3<60*60*24){
$chat_time=date('H:i:s',$time1);	
}else{
$chat_time=date('Y/m/d H:i:s',$time1);			
}

$msg .= '<p class="jinsom-chat-message-list-time">'.$chat_time.'</p>';
if($get_msgs->from_id==$user_id){

$msg .= '
<li class="myself">
<div class="jinsom-chat-message-list-user-info">
'.jinsom_avatar($user_id,'50',avatar_type($user_id) ).'
</div>
<div class="jinsom-chat-message-list-content">'.do_shortcode(wpautop(jinsom_autolink(convert_smilies($get_msgs->msg_content)))).'</div>
</li>';
}else{
$msg .= '
<li>
<div class="jinsom-chat-message-list-user-info">
<m onclick="jinsom_chat_group_show_user_info('.$chat_user_id.',this)">
'.jinsom_avatar($chat_user_id,'50',avatar_type($chat_user_id) ).jinsom_verify($chat_user_id).'
</m>
</div>
<div class="jinsom-chat-message-list-content">'.do_shortcode(wpautop(jinsom_autolink(convert_smilies($get_msgs->msg_content)))).'</div>
</li>
';
}

}//foreach
//将未读的消息设置为已经读取
jinsom_update_had_read_msg($chat_user_id);
echo json_encode(array("code"=>2,"count"=>$new_count,"msg"=>$msg));
exit();


}//是否有新消息


if($i==8){//16秒后 强制退出轮询
echo json_encode(array("code"=>9));
exit();  
} 


}//while



}//是否登录