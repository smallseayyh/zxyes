<?php
$user_id=$current_user->ID;
$user_info=get_userdata($user_id);
$credit_name=jinsom_get_option('jinsom_credit_name');//金币名称
$jinsom_task_day_add=jinsom_get_option('jinsom_task_day_add');
$jinsom_task_base_add=jinsom_get_option('jinsom_task_base_add');
$task_times=(int)get_user_meta($user_id,'task_times',true);//用户累计完成任务总数
?>
<div class="jinsom-task-form">

<?php if(jinsom_get_option('jinsom_task_treasure_on_off')){?>
<div class="jinsom-task-form-header">
<div class="header">
<div class="title"><?php _e('做任务开宝箱','jinsom');?></div>
<div class="number"><?php echo sprintf(__( '累计完成<n>%s</n>个任务','jinsom'),$task_times);?></div>
</div>
<div class="content clear">
<?php
$jinsom_task_treasure_add=jinsom_get_option('jinsom_task_treasure_add'); 
if($jinsom_task_treasure_add){
foreach ($jinsom_task_treasure_add as $data) {
if(jinsom_is_task($user_id,$data['id'])){
$img=$data['open_img'];
}else{
$img=$data['close_img'];
}
echo '<li onclick=\'jinsom_task_treasure_form("'.$data['id'].'")\' title="'.$data['name'].'" class="opacity"><img src="'.$img.'"><p>'.$data['number'].__('任务','jinsom').'</p></li>';
}
}else{
echo jinsom_empty('请到后台-主题设置-任务系统-添加宝箱任务');
}
?>
</div>
</div>
<?php }?>


<div class="jinsom-task-form-content">
<?php if(!wp_is_mobile()){?>
<div class="header">
<li class="on"><?php _e('每日任务','jinsom');?></li>	
<li><?php _e('成长任务','jinsom');?></li>
</div>
<?php }?>
<div class="content">
<ul class="on">

<?php 
$no_status='<div class="status opacity">'.__('未完成','jinsom').'</div>';
$had_status='<div class="status opacity had">'.__('已领取','jinsom').'</div>';
if($jinsom_task_day_add){
$ok_html='';
$no_html='';
$had_html='';

foreach ($jinsom_task_day_add as $data){
$task_id=$data['id'];
$event_number=$data['event_number'];
$event_type=$data['jinsom_task_day_event_type'];
$ok_status='<div class="status opacity on" onclick=\'jinsom_task_finish("'.$task_id.'","day",this)\'>'.__('领取奖励','jinsom').'</div>';

if($event_type=='publish'){
$publish_post_times=(int)get_user_meta($user_id,'publish_post_times',true);//发表动态次数
if($publish_post_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$publish_post_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='comment'){
$comment_post_times=(int)get_user_meta($user_id,'comment_post_times',true);//评论动态次数
if($comment_post_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$comment_post_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='publish_bbs'){
$publish_bbs_times=(int)get_user_meta($user_id,'publish_bbs_times',true);//发表帖子次数
if($publish_bbs_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$publish_bbs_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='comment_bbs'){
$comment_bbs_times=(int)get_user_meta($user_id,'comment_bbs_times',true);//回复帖子次数
if($comment_bbs_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$comment_bbs_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='follow'){
$follow_times=(int)get_user_meta($user_id,'follow_times',true);//关注次数
if($follow_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$follow_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='chat'){
$send_msg_times=(int)get_user_meta($user_id,'send_msg_times',true);//聊天消息次数
if($send_msg_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$send_msg_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='like'){
$like_post_times=(int)get_user_meta($user_id,'like_post_times',true);//喜欢内容次数
if($like_post_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$like_post_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='comment_up'){
$comment_like_times=(int)get_user_meta($user_id,'comment_like_times',true);//评论点赞次数
if($comment_like_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$comment_like_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='login'){
$number='<m>1</m>/<n>1</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else if($event_type=='sign'){
if(jinsom_is_sign($user_id,date('Y-m-d',time()))){
$number='<m>1</m>/<n>1</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else if($event_type=='draw'){
$draw_times=(int)get_user_meta($user_id,'draw_times',true);//抽奖次数
if($draw_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$draw_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='gift'){
$gift_times=(int)get_user_meta($user_id,'gift_times',true);//送礼次数
if($gift_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$gift_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='reward'){
$reward_times=(int)get_user_meta($user_id,'reward_times',true);//打赏次数
if($reward_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$reward_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='buy'){
$buy_times=(int)get_user_meta($user_id,'buy_times',true);//购买付费内容次数
if($buy_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$buy_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='visit'){
$visit_times=(int)get_user_meta($user_id,'visit_times',true);//访问他人主页次数
if($visit_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$visit_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='invite'){
$today_invite_number=(int)get_user_meta($user_id,'today_invite_number',true);//邀请人数
if($today_invite_number>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$today_invite_number.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='ad'){//点击广告
$today_ad_number=(int)get_user_meta($user_id,'today_ad_number',true);//当天点击广告次数
if($today_ad_number>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$today_ad_number.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='pet_times'){
$pet_times=(int)get_user_meta($user_id,'today_pet_times',true);
if($pet_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$pet_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else if($event_type=='pet_steal_times'){
$pet_steal_times=(int)get_user_meta($user_id,'today_pet_steal_times',true);
if($pet_steal_times>=$event_number){
$number='<m>'.$event_number.'</m>/<n>'.$event_number.'</n>';
if(!jinsom_is_task($user_id,$task_id)){
$status=$ok_status;
}else{
$status=$had_status;
}
}else{
$number='<m>'.$pet_steal_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}



$reward='';
$reward_vip='';
if($data['reward_add']){
foreach ($data['reward_add'] as $reward_add) {
$reward_type=$reward_add['reward_type'];
if($reward_type=='credit'){
$reward_type=$credit_name;
}else if($reward_type=='exp'){
$reward_type=__('经验值','jinsom');
}else if($reward_type=='vip'){
$reward_type=__('VIP天数','jinsom');
}else if($reward_type=='vip_number'){
$reward_type=__('成长值','jinsom');
}else if($reward_type=='sign'){
$reward_type=__('补签卡','jinsom');
}else if($reward_type=='nickname'){
$reward_type=__('改名卡','jinsom');
}else if($reward_type=='charm'){
$reward_type=__('魅力值','jinsom');
}

$reward.='<span>'.$reward_type.' * '.$reward_add['reward_number'].'</span>';
$reward_vip.='<span>'.$reward_type.' * '.$reward_add['reward_number_vip'].'</span>';
}
$reward='<p class="normal">'.__('普通奖励','jinsom').'：'.$reward.'</p><p class="vip">'.__('VIP 奖励','jinsom').'：'.$reward_vip.'</p>';
}else{
$reward.=__('暂无奖励','jinsom');
}



$html='
<li>
<div class="info">
<div class="left">
<div class="name">'.$data['name'].'</div>
<div class="desc">'.$data['desc'].'</div>
</div>
<div class="right">
<div class="number">'.__('已完成','jinsom').'：'.$number.'</div>
'.$status.'
</div>
</div>
<div class="reward">'.$reward.'</div>
</li>';

if($status==$ok_status){
$ok_html.=$html;
}else if($status==$no_status){
$no_html.=$html;
}else{
$had_html.=$html;
}


}

echo $ok_html.$no_html.$had_html;


}else{
echo jinsom_empty('请到后台-主题设置-任务系统-添加每日任务');
}
?>


</ul>
<ul>
<?php 
if($jinsom_task_base_add){

$ok_base_html='';
$no_base_html='';
$had_base_html='';

foreach ($jinsom_task_base_add as $data) {
$task_id=$data['id'];
$event_type=$data['event_type'];
$event_number=(int)$data['event_number'];
$event_key=$data['event_key'];
$ok_status='<div class="status opacity on" onclick=\'jinsom_task_finish("'.$task_id.'","base",this)\'>'.__('领取奖励','jinsom').'</div>';


if($event_type=='phone'){
$phone=(int)get_user_meta($user_id,'phone',true);//用户手机号
if(!jinsom_is_task($user_id,$task_id)){
if($phone){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='email'){
$email=$user_info->user_email;//用户邮箱
if(!jinsom_is_task($user_id,$task_id)){
if($email){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='question'){
$question=$user_info->question;//用户安全问题
$answer=$user_info->answer;//用户安全答案
if(!jinsom_is_task($user_id,$task_id)){
if($answer&&$question){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='desc'){
$description=$user_info->description;//用户个人说明
if(!jinsom_is_task($user_id,$task_id)){
if($description){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='qq'){
$qq_openid=$user_info->qq_openid;//qq openid
if(!jinsom_is_task($user_id,$task_id)){
if($qq_openid){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='wechat'){//绑定微信
$wechat_avatar=$user_info->wechat_avatar;
if(!jinsom_is_task($user_id,$task_id)){
if($wechat_avatar){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='weibo'){//绑定微博
$weibo_access_token=$user_info->weibo_access_token;
if(!jinsom_is_task($user_id,$task_id)){
if($weibo_access_token){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='honor'){
$use_honor=$user_info->use_honor;//使用的头衔
if(!jinsom_is_task($user_id,$task_id)){
if($use_honor){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='avatar'){
$customize_avatar=$user_info->customize_avatar;//上传头像
if(!jinsom_is_task($user_id,$task_id)){
if($customize_avatar){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='member_bg'){
$skin=$user_info->skin;//设置个人主页背景
if(!jinsom_is_task($user_id,$task_id)){
if($skin){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='is_vip'){//成为VIP用户
if(!jinsom_is_task($user_id,$task_id)){
if(is_vip($user_id)){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='is_verify'){
$verify=$user_info->verify;//成为认证用户
if(!jinsom_is_task($user_id,$task_id)){
if($verify){
$number='<m>1</m>/<n>1</n>';
$status=$ok_status;
}else{
$number='<m>0</m>/<n>1</n>';
$status=$no_status;
}
}else{
$number='<m>1</m>/<n>1</n>';
$status=$had_status;	
}
}else if($event_type=='reward'){
$reward=(int)$user_info->reward;//累计打赏数
if(!jinsom_is_task($user_id,$task_id)){
if($reward>=$event_number){
$number='<m>'.$reward.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$reward.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$reward.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='adopt'){
$user_adopt_number=(int)$user_info->user_adopt_number;//累计被采纳
if(!jinsom_is_task($user_id,$task_id)){
if($user_adopt_number>=$event_number){
$number='<m>'.$user_adopt_number.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$user_adopt_number.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$user_adopt_number.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='follower'){
$follower=jinsom_follower_count($user_id);//粉丝达到指定数量
if(!jinsom_is_task($user_id,$task_id)){
if($follower>=$event_number){
$number='<m>'.$follower.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$follower.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$follower.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='exp'){
$exp=(int)$user_info->exp;//经验值达到
if(!jinsom_is_task($user_id,$task_id)){
if($exp>=$event_number){
$number='<m>'.$exp.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$exp.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$exp.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='vip_number'){
$vip_number=(int)$user_info->vip_number;//成长值达到
if(!jinsom_is_task($user_id,$task_id)){
if($vip_number>=$event_number){
$number='<m>'.$vip_number.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$vip_number.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$vip_number.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='recharge'){
$recharge=(int)$user_info->recharge;//充值达到
if(!jinsom_is_task($user_id,$task_id)){
if($recharge>=$event_number){
$number='<m>'.$recharge.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$recharge.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$recharge.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='credit'){
$credit=$user_info->credit;//金币达到
if(!jinsom_is_task($user_id,$task_id)){
if($credit>=$event_number){
$number='<m>'.$credit.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$credit.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$credit.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='visitor'){
$visitor=$user_info->visitor;//人气达到
if(!jinsom_is_task($user_id,$task_id)){
if($visitor>=$event_number){
$number='<m>'.$visitor.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$visitor.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$visitor.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='charm'){
$charm=(int)$user_info->charm;//魅力达到
if(!jinsom_is_task($user_id,$task_id)){
if($charm>=$event_number){
$number='<m>'.$charm.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$charm.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$charm.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='sign'){
$sign=(int)$user_info->sign_c;//签到达到
if(!jinsom_is_task($user_id,$task_id)){
if($sign>=$event_number){
$number='<m>'.$sign.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$sign.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$sign.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='pet_times'){
$pet_times=(int)$user_info->pet_times;
if(!jinsom_is_task($user_id,$task_id)){
if($pet_times>=$event_number){
$number='<m>'.$pet_times.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$pet_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$pet_times.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='pet_steal_times'){
$pet_steal_times=(int)$user_info->pet_steal_times;
if(!jinsom_is_task($user_id,$task_id)){
if($pet_steal_times>=$event_number){
$number='<m>'.$pet_steal_times.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$pet_steal_times.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$pet_steal_times.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='content_number'){
$count_user_posts=count_user_posts($user_id,'post');//内容数
if(!jinsom_is_task($user_id,$task_id)){
if($count_user_posts>=$event_number){
$number='<m>'.$count_user_posts.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
$number='<m>'.$count_user_posts.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
$number='<m>'.$count_user_posts.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}else if($event_type=='cuctom_key'){//自定义字段
$event_key_arr=explode("|",$event_key);
$event_number=$event_key_arr[1];
if($event_number==1){
$event_key_number=get_user_meta($user_id,$event_key_arr[0],true);
}else{
$event_key_number=(int)get_user_meta($user_id,$event_key_arr[0],true);	
}
if(!jinsom_is_task($user_id,$task_id)){
if($event_key_number>=$event_number){
if($event_number==1){$event_key_number=1;}
$number='<m>'.$event_key_number.'</m>/<n>'.$event_number.'</n>';
$status=$ok_status;
}else{
if($event_number==1){$event_key_number=0;}
$number='<m>'.$event_key_number.'</m>/<n>'.$event_number.'</n>';
$status=$no_status;
}
}else{
if($event_number==1){$event_key_number=1;}
$number='<m>'.$event_key_number.'</m>/<n>'.$event_number.'</n>';
$status=$had_status;	
}
}


$reward='';
if($data['reward_add']){
foreach ($data['reward_add'] as $reward_add) {
$reward_type=$reward_add['reward_type'];
$reward_number=$reward_add['reward_number'];
if($reward_type=='credit'){
$reward_type=$credit_name;
}else if($reward_type=='exp'){
$reward_type=__('经验值','jinsom');
}else if($reward_type=='vip'){
$reward_type=__('VIP天数','jinsom');
}else if($reward_type=='vip_number'){
$reward_type=__('成长值','jinsom');
}else if($reward_type=='sign'){
$reward_type=__('补签卡','jinsom');
}else if($reward_type=='nickname'){
$reward_type=__('改名卡','jinsom');
}else if($reward_type=='honor'){
$reward_type=__('头衔','jinsom');
$reward_number=$reward_add['reward_honor'];
}else if($reward_type=='charm'){
$reward_type=__('魅力值','jinsom');
}

$reward.='<span>'.$reward_type.' * '.$reward_number.'</span>';
}
$reward='<p class="vip">'.__('任务奖励','jinsom').'：'.$reward.'</p>';
}else{
$reward.=__('暂无奖励','jinsom');
}

$base_html='
<li>
<div class="info">
<div class="left">
<div class="name">'.$data['name'].'</div>
<div class="desc">'.$data['desc'].'</div>
</div>
<div class="right">
<div class="number">'.__('已完成','jinsom').'：'.$number.'</div>
'.$status.'
</div>
</div>
<div class="reward">'.$reward.'</div>
</li>';

if($status==$ok_status){
$ok_base_html.=$base_html;
}else if($status==$no_status){
$no_base_html.=$base_html;
}else{
$had_base_html.=$base_html;
}



}

echo $ok_base_html.$no_base_html.$had_base_html;

}else{
echo jinsom_empty('请到后台-主题设置-任务系统-添加成长任务');
}
?>
</ul>
</div>

</div>
</div>