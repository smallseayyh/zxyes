<?php 
//获取我的钱包各种记录
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$page=$_POST['page'];
$type=$_POST['type'];
$number = 20;
$offset = ($page-1)*$number;

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


global $wpdb;
$table_name = $wpdb->prefix . 'jin_credit_note';

if($type=='recharge'){//充值记录
$note_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  (action='recharge' or action='recharge-alipay' or action='recharge-wechatpay' or action='recharge-vip-wechatpay' or action='recharge-vip-alipay') and user_id='$user_id' ORDER BY time desc limit $offset,$number;");
}else{
$note_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  user_id='$user_id' AND type='$type' and action !='recharge-vip-wechatpay' and action !='recharge-vip-alipay'  ORDER BY time DESC limit $offset,$number;");	
}

if($note_data){
$data_arr['code']=1;
$data_arr['data']=array();
foreach ($note_data as $data){
if($type=='recharge'){
$action=$data->action;
if($action=='recharge-alipay'){
$avatar='<span style="background-color: #56abe4;"><i class="jinsom-icon jinsom-zhifubaozhifu"></i></span>';
}else if($action=='recharge-wechatpay'){
$avatar='<span style="background-color: #41b035;"><i class="jinsom-icon jinsom-weixinzhifu"></i></span>';
}else{
$avatar='<span style="background-color: #666;"><i class="jinsom-icon jinsom-qiamizhifu"></i></span>';	
}
}else{
$type=$data->action;
if($type=='recharge'||$type=='recharge-alipay'||$type=='recharge-wechatpay'){
$avatar='<span style="background-color: #56abe4;">充</span>';
}else if($type=='comment'||$type=='comment-bbs'){
$avatar='<span style="background-color: #FF9800;"><i class="jinsom-icon jinsom-pinglun2"></i></span>';//评论	
}else if($type=='like-post'){
$avatar='<span style="background-color: #F44336;"><i class="jinsom-icon jinsom-xihuan1"></i></span>';//喜欢
}else if($type=='reward'){
$avatar='<span style="background-color: #ff8140;">赏</span>';	
}else if($type=='post-delete'||$type=='bbs-post-delete'||$type=="comment-bbs-delete"||$type=='comment-delete'){
$avatar='<span style="background-color: #ff5722;">删</span>';	
}else if($type=='sign'||$type=='sign-one'){
$avatar='<span style="background-color: #2eb354;">签</span>';//签到	
}else if($type=='activity'){
$avatar='<span style="background-color: #2196F3;">活动</span>';	
}else if($type=='buy-post'){
$avatar='<span style="background-color: #ff69a0;"><i class="jinsom-icon jinsom-goumai"></i></span>';//售出
}else if($type=='comment-up'){
$avatar='<span style="background-color: #F44336;"><i class="jinsom-icon jinsom-youzan"></i></span>';//评论点赞
}else if($type=='invite-reg'){
$avatar='<span style="background-color: #8BC34A;">邀请</span>';	
}else if($type=='publish-bbs-post'||$type=="publish-post"){
$avatar='<span style="background-color: #607D8B;"><i class="jinsom-icon jinsom-fabiao1"></i></span></span>';//发布	
}else if($type=='referral'){
$avatar='<span style="background-color: #F44336;">推广</span>';
}else if($type=='reg'){
$avatar='<span style="background-color: #F44336;">注册</span>';
}else if($type=='transfer'){
$avatar='<span style="background-color: #009688;"><i class="jinsom-icon jinsom-qianbao"></i></span>';
}else if($type=='withdrawals'){
$avatar='<span style="background-color: #aa7fff;">提现</span>';
}else if($type=='recharge-vip'){
$avatar='<span style="background-color: #FFC107;"><i class="jinsom-icon jinsom-huiyuan"></i></span>';//开通会员
}else{
$avatar='<span style="background-color: #000;">其他</span>';	
}
}

$note_arr=array();
$note_arr['content']=$data->content;
$note_arr['time']=$data->time;
$note_arr['number']=$data->number;
$note_arr['avatar']=$avatar;
array_push($data_arr['data'],$note_arr);
}
}else{
$data_arr['code']=0;
}

header('content-type:application/json');
echo json_encode($data_arr);