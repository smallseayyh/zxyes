<?php
//领取任务奖励
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$task_id=strip_tags($_POST['task_id']);
$type=strip_tags($_POST['type']);
$jinsom_task_day_add=jinsom_get_option('jinsom_task_day_add');
$jinsom_task_base_add=jinsom_get_option('jinsom_task_base_add');



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






if($type=='day'){//每日任务

//每日任务奖励
function jinsom_task_reward($reward_add,$times,$event_number,$task_id,$user_id){
if($times>=$event_number){
if(!jinsom_is_task($user_id,$task_id)){//是否已经领取
if($reward_add){//如果有奖励
foreach ($reward_add as $reward) {
$reward_type=$reward['reward_type'];
if(is_vip($user_id)){
$reward_number=abs($reward['reward_number_vip']);
}else{
$reward_number=abs($reward['reward_number']);	
}

if($reward_type=='credit'){
jinsom_update_credit($user_id,$reward_number,'add','task','每日任务奖励',1,'');  
}else if($reward_type=='exp'){
jinsom_update_exp($user_id,$reward_number,'add','每日任务奖励');
}else if($reward_type=='vip'){
jinsom_update_user_vip_day($user_id,$reward_number);
}else if($reward_type=='vip_number'){
jinsom_update_user_vip_number($user_id,$reward_number);
}else if($reward_type=='charm'){
jinsom_update_user_charm($user_id,$reward_number);
}else if($reward_type=='sign'){
jinsom_update_user_sign_number($user_id,$reward_number);
}else if($reward_type=='nickname'){
jinsom_update_user_nickname_card($user_id,$reward_number);
}


}
}

//写入数据库
global $wpdb;
$table_name = $wpdb->prefix.'jin_task';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,task_id,type,time) VALUES ('$user_id','$task_id','day','$time')" );
$task_times=(int)get_user_meta($user_id,'task_times',true);//用户累计完成任务总数
update_user_meta($user_id,'task_times',($task_times+1));//记录用户总共完成的任务数量

$status='ok';
}else{
$status='had';
}
}else{
$status='no';
}

return $status;
}




if($jinsom_task_day_add){
foreach ($jinsom_task_day_add as $data) {
if($task_id==$data['id']){
$event_number=$data['event_number'];
$event_type=$data['jinsom_task_day_event_type'];
$reward_add=$data['reward_add'];


if($event_type=='publish'){
$publish_post_times=(int)get_user_meta($user_id,'publish_post_times',true);//发表动态次数
$status=jinsom_task_reward($reward_add,$publish_post_times,$event_number,$task_id,$user_id);
}else if($event_type=='comment'){
$comment_post_times=(int)get_user_meta($user_id,'comment_post_times',true);//评论动态次数
$status=jinsom_task_reward($reward_add,$comment_post_times,$event_number,$task_id,$user_id);
}else if($event_type=='login'){
$status=jinsom_task_reward($reward_add,$event_number,$event_number,$task_id,$user_id);
}else if($event_type=='sign'){
if(jinsom_is_sign($user_id,date('Y-m-d',time()))){
$status=jinsom_task_reward($reward_add,$event_number,$event_number,$task_id,$user_id);
}else{
$status='no';
}
}else if($event_type=='publish_bbs'){
$publish_bbs_times=(int)get_user_meta($user_id,'publish_bbs_times',true);//发表帖子次数
$status=jinsom_task_reward($reward_add,$publish_bbs_times,$event_number,$task_id,$user_id);
}else if($event_type=='comment_bbs'){
$comment_bbs_times=(int)get_user_meta($user_id,'comment_bbs_times',true);//回复帖子次数
$status=jinsom_task_reward($reward_add,$comment_bbs_times,$event_number,$task_id,$user_id);
}else if($event_type=='follow'){
$follow_times=(int)get_user_meta($user_id,'follow_times',true);//关注次数
$status=jinsom_task_reward($reward_add,$follow_times,$event_number,$task_id,$user_id);
}else if($event_type=='chat'){
$send_msg_times=(int)get_user_meta($user_id,'send_msg_times',true);//聊天消息次数
$status=jinsom_task_reward($reward_add,$send_msg_times,$event_number,$task_id,$user_id);
}else if($event_type=='like'){
$like_post_times=(int)get_user_meta($user_id,'like_post_times',true);//喜欢内容次数
$status=jinsom_task_reward($reward_add,$like_post_times,$event_number,$task_id,$user_id);
}else if($event_type=='comment_up'){
$comment_like_times=(int)get_user_meta($user_id,'comment_like_times',true);//评论点赞次数
$status=jinsom_task_reward($reward_add,$comment_like_times,$event_number,$task_id,$user_id);
}else if($event_type=='draw'){
$draw_times=(int)get_user_meta($user_id,'draw_times',true);//抽奖次数
$status=jinsom_task_reward($reward_add,$draw_times,$event_number,$task_id,$user_id);
}else if($event_type=='gift'){
$gift_times=(int)get_user_meta($user_id,'gift_times',true);//送礼次数
$status=jinsom_task_reward($reward_add,$gift_times,$event_number,$task_id,$user_id);
}else if($event_type=='reward'){
$reward_times=(int)get_user_meta($user_id,'reward_times',true);//打赏次数
$status=jinsom_task_reward($reward_add,$reward_times,$event_number,$task_id,$user_id);
}else if($event_type=='buy'){
$buy_times=(int)get_user_meta($user_id,'buy_times',true);//购买付费内容次数
$status=jinsom_task_reward($reward_add,$buy_times,$event_number,$task_id,$user_id);
}else if($event_type=='visit'){
$visit_times=(int)get_user_meta($user_id,'visit_times',true);//访问他人主页次数
$status=jinsom_task_reward($reward_add,$visit_times,$event_number,$task_id,$user_id);
}else if($event_type=='invite'){
$today_invite_number=(int)get_user_meta($user_id,'today_invite_number',true);//今日邀请人数
$status=jinsom_task_reward($reward_add,$today_invite_number,$event_number,$task_id,$user_id);
}else if($event_type=='ad'){
$today_ad_number=(int)get_user_meta($user_id,'today_ad_number',true);//今日点击广告次数
$status=jinsom_task_reward($reward_add,$today_ad_number,$event_number,$task_id,$user_id);
}else if($event_type=='pet_times'){
$pet_times=(int)get_user_meta($user_id,'today_pet_times',true);//今日孵化次数
$status=jinsom_task_reward($reward_add,$pet_times,$event_number,$task_id,$user_id);
}else if($event_type=='pet_steal_times'){
$pet_steal_times=(int)get_user_meta($user_id,'today_pet_steal_times',true);//今日捕获次数
$status=jinsom_task_reward($reward_add,$pet_steal_times,$event_number,$task_id,$user_id);
}



}
}
}else{
$data_arr['code']=0;
$data_arr['msg']='请在后台配置每日任务的数据！';
}


}


if($type=='base'){//成长任务

function jinsom_task_base_reward($user_id,$task_id,$reward_add){
if(!jinsom_is_task($user_id,$task_id)){
if($reward_add){//如果有奖励
foreach ($reward_add as $reward) {
$reward_type=$reward['reward_type'];
$reward_number=abs($reward['reward_number']);	

if($reward_type=='credit'){
jinsom_update_credit($user_id,$reward_number,'add','task','成长任务奖励',1,'');  
}else if($reward_type=='exp'){
jinsom_update_exp($user_id,$reward_number,'add','成长任务奖励');
}else if($reward_type=='vip'){
jinsom_update_user_vip_day($user_id,$reward_number);
}else if($reward_type=='vip_number'){
jinsom_update_user_vip_number($user_id,$reward_number);
}else if($reward_type=='charm'){
jinsom_update_user_charm($user_id,$reward_number);
}else if($reward_type=='sign'){
jinsom_update_user_sign_number($user_id,$reward_number);
}else if($reward_type=='nickname'){
jinsom_update_user_nickname_card($user_id,$reward_number);
}else if($reward_type=='honor'){//头衔
jinsom_add_honor($user_id,$reward['reward_honor']);
}
}
}

//写入数据库
global $wpdb;
$table_name = $wpdb->prefix.'jin_task';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,task_id,type,time) VALUES ('$user_id','$task_id','base','$time')" );
$task_times=(int)get_user_meta($user_id,'task_times',true);//用户累计完成任务总数
update_user_meta($user_id,'task_times',($task_times+1));//记录用户总共完成的任务数量
$status='ok';
}else{
$status='had';
}
return $status;
}

$user_info = get_userdata($user_id);

if($jinsom_task_base_add){
foreach ($jinsom_task_base_add as $data) {
if($task_id==$data['id']){
$event_type=$data['event_type'];
$event_number=(int)$data['event_number'];
$event_key=$data['event_key'];
$reward_add=$data['reward_add'];

if($event_type=='phone'){
$phone=$user_info->phone;//用户手机号
if($phone){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='email'){
$email=$user_info->user_email;//用户邮箱
if($email){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='question'){
$question=$user_info->question;//用户安全问题
$answer=$user_info->answer;//用户安全答案
if($question&&$answer){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='desc'){
$description=$user_info->description;//用户个人说明
if($description){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='qq'){
$qq_openid=$user_info->qq_openid;//qq openid
if($qq_openid){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='wechat'){
$wechat_avatar=$user_info->wechat_avatar;
if($wechat_avatar){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='weibo'){
$weibo_access_token=$user_info->weibo_access_token;
if($weibo_access_token){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='honor'){
$use_honor=$user_info->use_honor;//使用的头衔
if($use_honor){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='avatar'){
$customize_avatar=$user_info->customize_avatar;//上传头像
if($customize_avatar){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='member_bg'){
$skin=$user_info->skin;//设置个人主页背景
if($skin){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='is_vip'){
if(is_vip($user_id)){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='is_verify'){
$verify=$user_info->verify;//成为认证用户
if($verify){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='reward'){
$reward=$user_info->reward;//累计打赏
if($reward>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='adopt'){
$user_adopt_number=$user_info->user_adopt_number;//累计被采纳
if($user_adopt_number>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='follower'){
$follower=jinsom_follower_count($user_id);//粉丝达到指定数量
if($follower>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='exp'){
$exp=$user_info->exp;//经验值达到
if($exp>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='vip_number'){
$vip_number=$user_info->vip_number;//成长值达到
if($vip_number>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='recharge'){
$recharge=$user_info->recharge;//累计充值
if($recharge>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='credit'){
$credit=$user_info->credit;//金币达到
if($credit>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='visitor'){
$visitor=$user_info->visitor;//人气到达
if($visitor>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='charm'){
$charm=$user_info->charm;//魅力到达
if($charm>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='sign'){
$sign=$user_info->sign_c;//签到到达
if($sign>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='pet_times'){
$pet_times=$user_info->pet_times;//累计孵化
if($pet_times>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='pet_steal_times'){
$pet_steal_times=$user_info->pet_steal_times;//累计捕获
if($pet_steal_times>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='content_number'){//内容达到指定数量
$count_user_posts=count_user_posts($user_id,'post');//内容数
if($count_user_posts>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}else if($event_type=='cuctom_key'){//自定义字段
$event_key_arr=explode("|",$event_key);
$event_number=$event_key_arr[1];
if($event_number==1){
$event_key_number=get_user_meta($user_id,$event_key_arr[0],true);
}else{
$event_key_number=(int)get_user_meta($user_id,$event_key_arr[0],true);	
}

if($event_key_number>=$event_number){
$status=jinsom_task_base_reward($user_id,$task_id,$reward_add);
}else{
$status='no';
}
}




}
}
}else{
$data_arr['code']=0;
$data_arr['msg']='请在后台配置成长任务的数据！';
}
}


if($status=='no'){
$data_arr['code']=0;
$data_arr['msg']='该任务你还没有完成！';	
}else if($status=='had'){
$data_arr['code']=0;
$data_arr['msg']='该任务你已经领取奖励！';		
}else if($status=='ok'){
$data_arr['code']=1;
$data_arr['msg']='奖励领取成功！';
$data_arr['task']=(int)get_user_meta($user_id,'task_times',true);//累计完成任务
}else{
$data_arr['code']=0;
$data_arr['msg']='领取失败！其他原因！';	
}

header('content-type:application/json');
echo json_encode($data_arr);