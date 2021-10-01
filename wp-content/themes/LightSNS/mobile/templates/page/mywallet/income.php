<?php 
//收入记录
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="income" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('收入记录','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-recharge-note-content">
<div class="jinsom-chat-user-list recharge-note list-block" page="2" type="add">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_credit_note';
$credit_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='add' and user_id='$user_id' ORDER BY time desc limit 20;");
if($credit_data){
foreach ($credit_data as $data) {
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
echo '
<li>
<div class="item-content">
<div class="item-media">
'.$avatar.'
</div>
<div class="item-inner">
<div class="item-title">
<div class="name">'.$data->content.'</div>
<div class="desc">'.$data->time.'</div>
</div>
</div>
<div class="item-after">+'.$data->number.'</div>
</div>
</li>
';
}

}else{
echo jinsom_empty();
}

?>

</div>
</div>
</div>        