<?php 
//充值记录
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="recharge-note" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('充值记录','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-recharge-note-content">
<div class="jinsom-chat-user-list recharge-note list-block" page="2" type="recharge">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_credit_note';
$credit_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  (action='recharge' or action='recharge-alipay' or action='recharge-wechatpay' or action='recharge-vip-wechatpay' or action='recharge-vip-alipay') and user_id='$user_id' ORDER BY time desc limit 20;");
if($credit_data){
foreach ($credit_data as $data) {
$action=$data->action;
if($action=='recharge-alipay'){
$avatar='<span style="background-color: #56abe4;"><i class="jinsom-icon jinsom-zhifubaozhifu"></i></span>';
}else if($action=='recharge-wechatpay'){
$avatar='<span style="background-color: #41b035;"><i class="jinsom-icon jinsom-weixinzhifu"></i></span>';
}else{
$avatar='<span style="background-color: #666;"><i class="jinsom-icon jinsom-qiamizhifu"></i></span>';	
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