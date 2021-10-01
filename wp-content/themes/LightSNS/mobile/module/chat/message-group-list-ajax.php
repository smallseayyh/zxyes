<?php
require( '../../../../../../wp-load.php' );
if (is_user_logged_in()) {
$user_id=$current_user->ID;

//群组
//ajax长轮询
if(!isset($_POST['count'])){
echo json_encode(array("code"=>3));	
exit(); 
}
 
$count=$_POST['count'];
$bbs_id=$_POST['bbs_id'];

set_time_limit(0);//无限请求超时时间  
$i=0;  
while (true){
usleep(500000);//0.5秒  
$i++;  


$new_count=jinsom_get_chat_group_msg_count($bbs_id);
$new_count_a=$new_count-$count;
if($new_count_a>0){
$get_msg=jinsom_get_group_msg($bbs_id,0,$new_count_a);
$msg='';
foreach (array_reverse($get_msg) as $get_msgs) {
$type=$get_msgs->type;
$time1=strtotime($get_msgs->msg_time);
$time2=time();
$time3=$time2-$time1;
if($time3<300){
$chat_time=date('H:i:s',$time1);
}elseif($time3>=300&&$time3<60*60*24){
$chat_time=date('H:i:s',$time1);	
}else{
$chat_time=date('Y/m/d H:i:s',$time1);			
}

if($type==1){


if($get_msgs->user_id==$user_id){//自己的情况
// $msg .= '
// <li class="myself">
// <div class="jinsom-chat-message-list-user-info">
// '.jinsom_avatar($user_id, '50' , avatar_type($user_id) ).'
// </div>
// <div class="jinsom-chat-message-list-content">'.convert_smilies($get_msgs->content).'</div>
// </li>';
}else{
$msg .= '<p class="jinsom-chat-message-list-time">'.$chat_time.'</p>';
$msg .= '
<li>
<div class="jinsom-chat-message-list-user-info">
<a href="'.jinsom_mobile_author_url($get_msgs->user_id).'" class="link">
'.jinsom_avatar($get_msgs->user_id, '50' , avatar_type($get_msgs->user_id) ).jinsom_verify($get_msgs->user_id).'
<span class="name">'.jinsom_nickname($get_msgs->user_id).jinsom_lv($get_msgs->user_id).jinsom_vip($get_msgs->user_id).jinsom_honor($get_msgs->user_id).'</span>
</a>
</div>
<div class="jinsom-chat-message-list-content">'.jinsom_autolink(convert_smilies($get_msgs->content)).'</div>
</li>
';
}

}else if($type==2){
$msg .= '<p class="jinsom-chat-message-tips"><span>'.jinsom_nickname($get_msgs->user_id).' 加入了群聊</span></p>';	
}else if($type==3){
$msg .= '<p class="jinsom-chat-message-tips"><span>'.jinsom_nickname($get_msgs->user_id).' 退出了群聊</span></p>';	
}


}//foreach
echo json_encode(array("code"=>2,"count"=>$new_count,"msg"=>$msg));
jinsom_upadte_user_online_time();//更新在线状态
exit();

}//是否有新消息


if($i==80){//40秒后 强制退出轮询
echo json_encode(array("code"=>9));
exit();  
} 


}//while



}//是否登录
