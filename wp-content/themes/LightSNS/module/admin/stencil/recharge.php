<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name = $wpdb->prefix.'jin_credit_note';
?>
<!-- 充值记录 -->
<div class="jinsom-admin-key-form recharge">

<div class="jinsom-admin-key-bottom">
<?php 
$recharge_add_data = $wpdb->get_results("SELECT user_id,content,number,action,time FROM $table_name WHERE (action='recharge' or action='recharge-alipay' or action='recharge-wechatpay' or action='recharge-vip-wechatpay' or action='recharge-vip-alipay') ORDER BY time desc limit 9;");
$recharge_add_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE (action='recharge' or action='recharge-alipay' or action='recharge-wechatpay');");
if($recharge_add_data){
?>
<div class="jinsom-mycredit-table recharge">
<div class="title">
<span>昵称</span>
<span>类型</span>
<span>数量</span>
<span>时间</span>
</div>
<div class="content recharge">
<?php 
foreach ($recharge_add_data as $data) {
$time=$data->time;
$time_a=strtotime($time);
$time_b=date('Y-m-d',$time_a);
$today_date=date('Y-m-d');
if($today_date==$time_b){
$time_red='<font style="color:#f00;">'.$time.'</font>';
}else{
$time_red=$time;
}
if($data->action=='recharge-vip-wechatpay' || $data->action=='recharge-vip-alipay'){
echo '<li><span>'.jinsom_nickname_link($data->user_id).'</span><span>'.$data->content.'</span><span>'.$data->number.' 个月</span><span title="'.$time.'">'.$time_red.'</span></li>';
}else{
echo '<li><span>'.jinsom_nickname_link($data->user_id).'</span><span>'.$data->content.'</span><span>'.$data->number.' '.jinsom_get_option('jinsom_credit_name').'</span><span title="'.$time.'">'.$time_red.'</span></li>';	
}

}
?>
</div>	
</div>
<div id="jinsom-recharge-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>

</div>


<script type="text/javascript">
layui.use('laypage', function(){
var laypage = layui.laypage;

<?php if($recharge_add_count>9){?>
laypage.render({
elem: 'jinsom-recharge-page',
theme:'#5fb878',
limit:9,
count: <?php echo $recharge_add_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .recharge').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/recharge-more.php",
data:{page:page,number:number},
success: function(msg){
$('.jinsom-mycredit-table .recharge').html(msg);
}
});

}
}
});
<?php }?>




});
</script>
