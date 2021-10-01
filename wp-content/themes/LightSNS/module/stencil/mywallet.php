<?php 
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$user_id=(int)$_POST['user_id'];
if(!$user_id){
$user_id=$current_user->ID;
}
}
$credit=(int)get_user_meta($user_id,'credit',true);
$credit_name=jinsom_get_option('jinsom_credit_name');
$vip_time=get_user_meta($user_id,'vip_time',true);
$vip_number=(int)get_user_meta($user_id,'vip_number',true);
$jinsom_transfer_on_off = jinsom_get_option('jinsom_transfer_on_off');//转账功能
$jinsom_cash_on_off = jinsom_get_option('jinsom_cash_on_off');
$jinsom_cash_ratio=jinsom_get_option('jinsom_cash_ratio');
$jinsom_recharge_number_add=jinsom_get_option('jinsom_recharge_number_add');//金币充值套餐
$jinsom_recharge_vip_add=jinsom_get_option('jinsom_recharge_vip_add');//VIP套餐
?>
<!-- 我的钱包 -->
<div class="jinsom-mycredit-form">

<div class="jinsom-mycredit-top">
<div class="left clear"> 
<div class="jinsom-mycredit-user-avatar">
<a href="<?php echo jinsom_userlink($user_id);?>" target="_blank">
<?php echo jinsom_avatar( $user_id , '80' , avatar_type($user_id) );?>
</a>
<?php echo jinsom_verify($user_id);?>
</div>
<div class="jinsom-mycredit-user-info">
<div class="name">
<?php echo jinsom_nickname_link($user_id);?> 
<?php echo jinsom_sex($user_id);?>
<?php echo jinsom_lv($user_id);?>
<?php echo jinsom_vip($user_id);?>
</div>
<div class="vip">
<?php if(is_vip($user_id)){?>
<p style="color: #f00;"><?php _e('会员成长值','jinsom');?>：<?php echo $vip_number;?></p>
<p><m><?php echo $vip_time;?></m> <?php _e('到期','jinsom');?><span onclick="jinsom_recharge_vip_form()"><?php _e('续费','jinsom');?></span></p>
<?php }else{?>
<p style="color: #aaa;"><?php _e('普通用户','jinsom');?></p>
<p style="color: #d2e047;"><?php _e('开通会员，尊享会员特权','jinsom');?><span onclick="jinsom_recharge_vip_form()"><?php _e('开通','jinsom');?></span></p>
<?php }?>
</div>
</div>
</div>

<div class="right">
<div class="jinsom-mycredit-credit-info">
<div class="credit">
我的<?php echo $credit_name;?>：
<span class="jinsom-icon jinsom-jinbi"></span>
<i><?php echo $credit;?></i>
<?php if($jinsom_cash_on_off){?>
<n>≈</n>
<m><?php echo intval($credit/$jinsom_cash_ratio);?><?php _e('元','jinsom');?></m>
<?php }?>
</div>

<div class="jinsom-mycredit-credit-type">
<?php if($jinsom_recharge_number_add){?>
<li class="recharge opacity" onclick="jinsom_recharge_credit_form()"><?php _e('充值','jinsom');?></li>
<?php }?>
<?php if($jinsom_transfer_on_off){?>
<li class="transfer opacity" onclick="jinsom_transfer_one_form()"><?php _e('转账','jinsom');?></li>
<?php }?>
<?php if($jinsom_cash_on_off){?>
<li class="withdrawals opacity" onclick="jinsom_cash_form()"><?php _e('提现','jinsom');?></li>
<?php }?>
</div>


</div>
</div>

</div><!-- jinsom-mycredit-top -->



<div class="jinsom-mycredit-bottom">

<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title">
<li class="layui-this"><?php _e('收入记录','jinsom');?></li>
<li><?php _e('支出记录','jinsom');?></li>
<?php if($jinsom_recharge_number_add){?>
<li><?php _e('充值记录','jinsom');?></li>
<?php }?>
<?php if($jinsom_cash_on_off){?>
<li><?php _e('提现记录','jinsom');?></li>
<?php }?>
<li><?php _e('经验记录','jinsom');?></li>
</ul>
<div class="layui-tab-content">

<!-- 收入记录 -->
<div class="layui-tab-item layui-show">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_credit_note';
$credit_add_data = $wpdb->get_results("SELECT content,number,time FROM $table_name WHERE  type='add' and user_id='$user_id' and action !='recharge-vip-wechatpay' and action !='recharge-vip-alipay' ORDER BY time desc limit 6;");
$credit_add_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE  type='add' and user_id='$user_id' and action !='recharge-vip-wechatpay' and action !='recharge-vip-alipay';");
if($credit_add_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span><?php _e('事项','jinsom');?></span>
<span><?php _e('金额','jinsom');?></span>
<span><?php _e('时间','jinsom');?></span>
</div>
<div class="content credit-add">
<?php 
foreach ($credit_add_data as $data) {
echo '<li><span>'.$data->content.'</span><span>+'.$data->number.' '.$credit_name.'</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-credit-add-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>


<!-- 支出记录 -->
<div class="layui-tab-item">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_credit_note';
$credit_cut_data = $wpdb->get_results("SELECT content,number,time FROM $table_name WHERE  type='cut' and user_id='$user_id' ORDER BY time desc limit 6;");
$credit_cut_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE  type='cut' and user_id='$user_id';");
if($credit_cut_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span><?php _e('事项','jinsom');?></span>
<span><?php _e('金额','jinsom');?></span>
<span><?php _e('时间','jinsom');?></span>
</div>
<div class="content credit-cut">
<?php 
foreach ($credit_cut_data as $data) {
echo '<li><span>'.$data->content.'</span><span>-'.$data->number.' '.$credit_name.'</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-credit-cut-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>


<!-- 充值记录 -->
<?php if($jinsom_recharge_number_add){?>
<div class="layui-tab-item">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_credit_note';
$recharge_add_data = $wpdb->get_results("SELECT content,number,action,time FROM $table_name WHERE (action='recharge' or action='recharge-alipay' or action='recharge-wechatpay' or action='recharge-vip-wechatpay' or action='recharge-vip-alipay') and user_id='$user_id' ORDER BY time desc limit 6;");
$recharge_add_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE (action='recharge' or action='recharge-alipay' or action='recharge-wechatpay' or action='recharge-vip-wechatpay' or action='recharge-vip-alipay') and user_id='$user_id';");
if($recharge_add_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span><?php _e('充值类型','jinsom');?></span>
<span><?php _e('数量','jinsom');?></span>
<span><?php _e('时间','jinsom');?></span>
</div>
<div class="content recharge-add">
<?php 
foreach ($recharge_add_data as $data) {
$time=$data->number;
if($time<1){
$time=(int)($time*30).__('天','jinsom');
}else{
$time=$time.__('个月','jinsom');	
}

if($data->action=='recharge-vip-wechatpay' || $data->action=='recharge-vip-alipay'){
echo '<li><span>'.$data->content.'</span><span>+'.$time.'</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';
}else{
echo '<li><span>'.$data->content.'</span><span>+'.$data->number.' '.$credit_name.'</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';	
}
}
?>
</div>	
</div>
<div id="jinsom-recharge-add-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>
<?php }?>

<!-- 提现记录 -->
<?php if($jinsom_cash_on_off){?>
<div class="layui-tab-item">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_cash';
$cash_data = $wpdb->get_results("SELECT ID,status,credit,cash_time FROM $table_name WHERE user_id='$user_id' ORDER BY cash_time desc limit 6;");
$cash_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE user_id='$user_id';");
if($cash_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span><?php _e('提现状态','jinsom');?></span>
<span><?php echo $credit_name;?></span>
<span><?php _e('时间','jinsom');?></span>
</div>
<div class="content withdrawals">
<?php 
foreach ($cash_data as $data) {
if($data->status==0){
$status='等待审核中';
}else if($data->status==1){
$status='<font style="color:#46c47c;">提现成功</font>';	
}else{
$status='<font style="color:#f00;">提现失败，点击查看原因。</font>';	
}
echo '<li onclick="jinsom_cash_more('.$data->ID.')"><span>'.$status.'</span><span>'.$data->credit.' '.$credit_name.'</span><span title="'.$data->cash_time.'">'.jinsom_timeago($data->cash_time).'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-credit-withdrawals-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>
<?php }?>

<div class="layui-tab-item">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_exp_note';
$exp_add_data = $wpdb->get_results("SELECT content,number,time,type FROM $table_name WHERE user_id='$user_id' ORDER BY time desc limit 6;");
$exp_add_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE user_id='$user_id';");
if($exp_add_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span><?php _e('事项','jinsom');?></span>
<span><?php _e('数量','jinsom');?></span>
<span><?php _e('时间','jinsom');?></span>
</div>
<div class="content exp-add">
<?php 
foreach ($exp_add_data as $data) {
if($data->type=='add'){
echo '<li><span>'.$data->content.'</span><span>+'.$data->number.' 经验</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';	
}else{
echo '<li><span>'.$data->content.'</span><span>-'.$data->number.' 经验</span><span title="'.$data->time.'">'.jinsom_timeago($data->time).'</span></li>';		
}
}
?>
</div>	
</div>
<div id="jinsom-exp-add-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>





</div><!-- layui-tab-content -->


</div> 
</div>
</div>


<script type="text/javascript">
layui.use('laypage', function(){
var laypage = layui.laypage;

<?php if($credit_add_count>6){?>
laypage.render({//金币收入
elem: 'jinsom-credit-add-page',
theme:'#5fb878',
limit:6,
groups:3,
count: <?php echo $credit_add_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .credit-add').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/credit.php",
data:{page:page,number:number,user_id:<?php echo $user_id;?>,type:'credit-add'},
success: function(msg){
$('.jinsom-mycredit-table .credit-add').html(msg);
}
});

}
}
});
<?php }?>

<?php if($credit_cut_count>6){?>
laypage.render({//金币支出
elem: 'jinsom-credit-cut-page',
theme:'#5fb878',
limit:6,
groups:3,
count: <?php echo $credit_cut_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .credit-cut').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/credit.php",
data:{page:page,number:number,user_id:<?php echo $user_id;?>,type:'credit-cut'},
success: function(msg){
$('.jinsom-mycredit-table .credit-cut').html(msg);
}
});

}
}
});
<?php }?>


<?php if($recharge_add_count>6){?>
laypage.render({//充值记录
elem: 'jinsom-recharge-add-page',
theme:'#5fb878',
limit:6,
groups:3,
count: <?php echo $recharge_add_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .recharge-add').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/credit.php",
data:{page:page,number:number,user_id:<?php echo $user_id;?>,type:'recharge-add'},
success: function(msg){
$('.jinsom-mycredit-table .recharge-add').html(msg);
}
});

}
}
});
<?php }?>

<?php if($cash_count>6){?>
laypage.render({//提现记录
elem: 'jinsom-credit-withdrawals-page',
theme:'#5fb878',
limit:6,
groups:3,
count: <?php echo $cash_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .withdrawals').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/credit.php",
data:{page:page,number:number,user_id:<?php echo $user_id;?>,type:'withdrawals'},
success: function(msg){
$('.jinsom-mycredit-table .withdrawals').html(msg);
}
});

}
}
});
<?php }?>


<?php if($exp_add_count>6){?>
laypage.render({//经验记录
elem: 'jinsom-exp-add-page',
theme:'#5fb878',
limit:6,
groups:3,
count: <?php echo $exp_add_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .exp-add').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/credit.php",
data:{page:page,number:number,user_id:<?php echo $user_id;?>,type:'exp-add'},
success: function(msg){
$('.jinsom-mycredit-table .exp-add').html(msg);
}
});

}
}
});
<?php }?>

});
</script>
