<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name = $wpdb->prefix.'jin_cash';
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<!-- 提现记录 -->
<div class="jinsom-admin-key-form cash">

<div class="jinsom-admin-key-top">
<li class="opacity" onclick="jinsom_no()">批量导出</li>
</div>

<div class="jinsom-admin-key-bottom">
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title">
<li class="layui-this">全部记录</li>
<li>等待审核</li>
<li>审核通过</li>
<li>审核失败</li>
</ul>
<div class="layui-tab-content">


<div class="layui-tab-item layui-show">
<?php 
$table_name = $wpdb->prefix.'jin_cash';
$cash_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY cash_time desc limit 6;");
$cash_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name;");
if($cash_data){
?>
<div class="jinsom-mycredit-table cash">
<div class="title">
<span>昵称</span>
<span>数量</span>
<span>金额</span>
<span>时间</span>
<span>状态</span>
<span>操作</span>
</div>
<div class="content cash">
<?php 
foreach ($cash_data as $data) {
if($data->status==0){
$status='审核';
$do='<m style="color:#46c47c;" class="agree" onclick="jinsom_cash_agree('.$data->ID.',this)">通过</m><m style="color:#f00;" class="refuse" onclick="jinsom_cash_refuse('.$data->ID.',this)">拒绝</m><m onclick="jinsom_cash_more('.$data->ID.')">查看</m>';
}else if($data->status==1){
$status='成功';	
$do='<m onclick="jinsom_cash_more('.$data->ID.')">查看</m>';
}else{
$status='失败';
$do='<m onclick="jinsom_cash_more('.$data->ID.')">查看</m>';	
}
echo '<li>
<span>'.jinsom_nickname_link($data->user_id).'</span>
<span>'.$data->credit.' '.$credit_name.'</span>
<span>'.$data->rmb.'元</span>
<span title="'.$data->cash_time.'">'.jinsom_timeago($data->cash_time).'</span>
<span>'.$status.'</span>
<span>'.$do.'</span>
</li>';
}
?>
</div>	
</div>
<div id="jinsom-cash-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>

<!-- 等待审核 -->
<div class="layui-tab-item">
<?php 
$table_name = $wpdb->prefix.'jin_cash';
$cash_data_wait = $wpdb->get_results("SELECT * FROM $table_name where status=0 ORDER BY cash_time desc limit 6;");
$cash_count_wait=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name where status=0;");
if($cash_data_wait){
?>
<div class="jinsom-mycredit-table cash">
<div class="title">
<span>昵称</span>
<span>数量</span>
<span>金额</span>
<span>时间</span>
<span>状态</span>
<span>操作</span>
</div>
<div class="content cash-wait">
<?php 
foreach ($cash_data_wait as $data) {
$status='审核';
$do='<m style="color:#46c47c;" class="agree" onclick="jinsom_cash_agree('.$data->ID.',this)">通过</m><m style="color:#f00;" class="refuse" onclick="jinsom_cash_refuse('.$data->ID.',this)">拒绝</m><m onclick="jinsom_cash_more('.$data->ID.')">查看</m>';
echo '<li>
<span>'.jinsom_nickname_link($data->user_id).'</span>
<span>'.$data->credit.' '.$credit_name.'</span>
<span>'.$data->rmb.'元</span>
<span title="'.$data->cash_time.'">'.jinsom_timeago($data->cash_time).'</span>
<span>'.$status.'</span>
<span>'.$do.'</span>
</li>';
}
?>
</div>	
</div>
<div id="jinsom-cash-wait-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>

<!-- 审核通过 -->
<div class="layui-tab-item">
<?php 
$table_name = $wpdb->prefix.'jin_cash';
$cash_data_agree = $wpdb->get_results("SELECT * FROM $table_name where status=1 ORDER BY cash_time desc limit 6;");
$cash_count_agree=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name where status=1;");
if($cash_data_agree){
?>
<div class="jinsom-mycredit-table cash">
<div class="title">
<span>昵称</span>
<span>数量</span>
<span>金额</span>
<span>时间</span>
<span>状态</span>
<span>操作</span>
</div>
<div class="content cash-agree">
<?php 
foreach ($cash_data_agree as $data) {
$status='成功';	
$do='<m onclick="jinsom_cash_more('.$data->ID.')">查看</m>';
echo '<li>
<span>'.jinsom_nickname_link($data->user_id).'</span>
<span>'.$data->credit.' '.$credit_name.'</span>
<span>'.$data->rmb.'元</span>
<span title="'.$data->cash_time.'">'.jinsom_timeago($data->cash_time).'</span>
<span>'.$status.'</span>
<span>'.$do.'</span>
</li>';
}
?>
</div>	
</div>
<div id="jinsom-cash-agree-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>

<!-- 审核失败 -->
<div class="layui-tab-item">
<?php 
$table_name = $wpdb->prefix.'jin_cash';
$cash_data_refuse = $wpdb->get_results("SELECT * FROM $table_name where status=2 ORDER BY cash_time desc limit 6;");
$cash_count_refuse=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name where status=2;");
if($cash_data_refuse){
?>
<div class="jinsom-mycredit-table cash">
<div class="title">
<span>昵称</span>
<span>数量</span>
<span>金额</span>
<span>时间</span>
<span>状态</span>
<span>操作</span>
</div>
<div class="content cash-refuse">
<?php 
foreach ($cash_data_refuse as $data) {
$status='失败';	
$do='<m onclick="jinsom_cash_more('.$data->ID.')">查看</m>';
echo '<li>
<span>'.jinsom_nickname_link($data->user_id).'</span>
<span>'.$data->credit.' '.$credit_name.'</span>
<span>'.$data->rmb.'元</span>
<span title="'.$data->cash_time.'">'.jinsom_timeago($data->cash_time).'</span>
<span>'.$status.'</span>
<span>'.$do.'</span>
</li>';
}
?>
</div>	
</div>
<div id="jinsom-cash-refuse-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>


</div>
</div>



</div>

</div>


<script type="text/javascript">
layui.use('laypage', function(){
var laypage = layui.laypage;

<?php if($cash_count>6){?>
laypage.render({
elem: 'jinsom-cash-page',
theme:'#5fb878',
limit:6,
count: <?php echo $cash_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .cash').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/cash-more.php",
data:{page:page,number:number,type:'all'},
success: function(msg){
$('.jinsom-mycredit-table .cash').html(msg);
}
});

}
}
});
<?php }?>

<?php if($cash_count_wait>6){?>
laypage.render({
elem: 'jinsom-cash-wait-page',
theme:'#5fb878',
limit:6,
count: <?php echo $cash_count_wait;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .cash-wait').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/cash-more.php",
data:{page:page,number:number,type:'wait'},
success: function(msg){
$('.jinsom-mycredit-table .cash-wait').html(msg);
}
});

}
}
});
<?php }?>

<?php if($cash_count_agree>6){?>
laypage.render({
elem: 'jinsom-cash-agree-page',
theme:'#5fb878',
limit:6,
count: <?php echo $cash_count_agree;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .cash-agree').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/cash-more.php",
data:{page:page,number:number,type:'agree'},
success: function(msg){
$('.jinsom-mycredit-table .cash-agree').html(msg);
}
});

}
}
});
<?php }?>

<?php if($cash_count_refuse>6){?>
laypage.render({
elem: 'jinsom-cash-refuse-page',
theme:'#5fb878',
limit:6,
count: <?php echo $cash_count_refuse;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .cash-refuse').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/cash-more.php",
data:{page:page,number:number,type:'refuse'},
success: function(msg){
$('.jinsom-mycredit-table .cash-refuse').html(msg);
}
});

}
}
});
<?php }?>




});
</script>
