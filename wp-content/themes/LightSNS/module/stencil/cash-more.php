<?php
//查看提现详情
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;


$ID=(int)$_POST['ID'];
global $wpdb;
$table_name = $wpdb->prefix.'jin_cash';
$cash_data = $wpdb->get_results("SELECT * FROM $table_name WHERE ID='$ID' limit 1;");
foreach ($cash_data as $data) {
if($data->type=='alipay'){
$type=__('支付宝','jinsom');
}else{
$type=__('微信','jinsom');
}
if($data->status==0){
$status=__('等待审核中','jinsom');
}else if($data->status==1){
$status='<font style="color:#46c47c;">'.__('提现成功','jinsom').'</font>';	
}else{
$status='<font style="color:#f00;">'.__('提现失败','jinsom').'</font>';	
}
?>
<div class="jinsom-cash-form-more">
<li><?php _e('提现数量','jinsom');?>：<?php echo $data->credit.jinsom_get_option('jinsom_credit_name');?></li>
<li><?php _e('提现金额','jinsom');?>：<?php echo $data->rmb.__('元','jinsom');?></li>
<li><?php _e('提现类型','jinsom');?>：<?php echo $type;?></li>
<li><?php _e('收款姓名','jinsom');?>：<?php echo $data->name;?></li>
<li><?php _e('收款帐号','jinsom');?>：<?php echo $data->phone_email;?></li>
<li><?php _e('提现状态','jinsom');?>：<?php echo $status;?></li>
<?php if($data->status!=0&&$data->status!=1){?>
<li class="reason"><?php _e('失败原因','jinsom');?>：<m><?php echo $data->mark;?></m></li>
<?php }?>
</div>
<?php }?>
