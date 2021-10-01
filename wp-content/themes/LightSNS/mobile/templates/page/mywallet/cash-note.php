<?php 
//提现记录
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="cash-note" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('提现记录','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content">
<div class="jinsom-chat-user-list recharge-note list-block" page="2" type="withdrawals">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_cash';
$cash_data = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' ORDER BY cash_time desc limit 30;");
if($cash_data){
foreach ($cash_data as $data) {
if($data->status==0){
$status=__('审核中','jinsom');
}else if($data->status==1){
$status='<font style="color:#46c47c;">'.__('提现成功','jinsom').'</font>';	
}else{
$status='<font style="color:#f00;">'.__('提现失败','jinsom').'</font>';
}
echo '
<li>
<a href="'.get_template_directory_uri().'/mobile/templates/page/mywallet/cash-note-more.php?ID='.$data->ID.'" class="link">
<div class="item-content">
<div class="item-media">
<span style="background-color: #aa7fff;">'.__('提现','jinsom').'</span>
</div>
<div class="item-inner">
<div class="item-title">
<div class="name">'.$status.'</div>
<div class="desc">'.jinsom_timeago($data->cash_time).'</div>
</div>
</div>
<div class="item-after cut">'.$data->credit.jinsom_get_option('jinsom_credit_name').'</div>
</div>
</a>
</li>
';
}
//echo '<div class="jinsom-empty-page">只展示前30条记录</div>';
}else{
echo jinsom_empty();
}

?>

</div>
</div>
</div>        