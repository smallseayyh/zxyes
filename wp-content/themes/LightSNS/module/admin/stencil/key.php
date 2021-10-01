<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name = $wpdb->prefix.'jin_key';
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<!-- 卡密 -->
<div class="jinsom-admin-key-form">
<div class="jinsom-admin-key-top">
<li class="opacity" onclick="jinsom_admin_key_add_form()">卡密生成</li>
<li class="opacity" onclick="jinsom_admin_key_export_form()">卡密导出</li>
<li class="opacity" onclick="jinsom_admin_key_search_form()">卡密查询</li>
<li class="opacity" onclick="jinsom_admin_key_delete_form()">卡密删除</li>
</div>


<div class="jinsom-admin-key-bottom">
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title">
<li class="layui-this"><?php echo $credit_name;?>卡</li>
<li>会员卡</li>
<li>经验卡</li>
<li>补签卡</li>
<li>改名卡</li>
<li>成长值卡</li>
</ul>
<div class="layui-tab-content">

<!-- 金币卡 -->
<div class="layui-tab-item layui-show">
<?php 
$credit_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='credit' ORDER BY ID desc limit 6;");
$credit_key_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE  type='credit';");
if($credit_key_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span>卡密</span>
<span>金额</span>
<span>状态</span>
<span>有效期</span>
</div>
<div class="content credit">
<?php 
foreach ($credit_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' '.$credit_name.'</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-key-credit-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>


<!-- 会员卡 -->
<div class="layui-tab-item">
<?php 
$vip_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='vip' ORDER BY ID desc limit 6;");
$vip_key_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE  type='vip';");
if($vip_key_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span>卡密</span>
<span>天数</span>
<span>状态</span>
<span>有效期</span>
</div>
<div class="content vip">
<?php 
foreach ($vip_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' 天</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-key-vip-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>


<!-- 经验卡 -->
<div class="layui-tab-item">
<?php 
$exp_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='exp' ORDER BY ID desc limit 6;");
$exp_key_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE  type='exp';");
if($exp_key_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span>卡密</span>
<span>数量</span>
<span>状态</span>
<span>有效期</span>
</div>
<div class="content exp">
<?php 
foreach ($exp_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' 经验值</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-key-exp-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>

<!-- 补签卡 -->
<div class="layui-tab-item">
<?php 
$sign_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='sign' ORDER BY ID desc limit 6;");
$sign_key_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE  type='sign';");
if($sign_key_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span>卡密</span>
<span>数量</span>
<span>状态</span>
<span>有效期</span>
</div>
<div class="content sign">
<?php 
foreach ($sign_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' 张</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-key-sign-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>


<!-- 改名卡 -->
<div class="layui-tab-item">
<?php 
$nickname_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='nickname' ORDER BY ID desc limit 6;");
$nickname_key_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE  type='nickname';");
if($nickname_key_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span>卡密</span>
<span>数量</span>
<span>状态</span>
<span>有效期</span>
</div>
<div class="content nickname">
<?php 
foreach ($nickname_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' 张</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-key-nickname-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>

<!-- 成长值 -->
<div class="layui-tab-item">
<?php 
$vip_number_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='vip_number' ORDER BY ID desc limit 6;");
$vip_number_key_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE  type='vip_number';");
if($vip_number_key_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span>卡密</span>
<span>数量</span>
<span>状态</span>
<span>有效期</span>
</div>
<div class="content vip_number">
<?php 
foreach ($vip_number_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' 成长值</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-key-vip-number-page" class="jinsom-mycredit-page"></div>
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

<?php if($credit_key_count>6){?>
laypage.render({//金币收入
elem: 'jinsom-key-credit-page',
theme:'#5fb878',
limit:6,
count: <?php echo $credit_key_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .credit').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/key-more.php",
data:{page:page,number:number,type:'credit'},
success: function(msg){
$('.jinsom-mycredit-table .credit').html(msg);
}
});

}
}
});
<?php }?>

<?php if($vip_key_count>6){?>
laypage.render({
elem: 'jinsom-key-vip-page',
theme:'#5fb878',
limit:6,
count: <?php echo $vip_key_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .vip').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/key-more.php",
data:{page:page,number:number,type:'vip'},
success: function(msg){
$('.jinsom-mycredit-table .vip').html(msg);
}
});

}
}
});
<?php }?>


<?php if($exp_key_count>6){?>
laypage.render({
elem: 'jinsom-key-exp-page',
theme:'#5fb878',
limit:6,
count: <?php echo $exp_key_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .exp').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/key-more.php",
data:{page:page,number:number,type:'exp'},
success: function(msg){
$('.jinsom-mycredit-table .exp').html(msg);
}
});

}
}
});
<?php }?>

<?php if($sign_key_count>6){?>
laypage.render({
elem: 'jinsom-key-sign-page',
theme:'#5fb878',
limit:6,
count: <?php echo $sign_key_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .sign').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/key-more.php",
data:{page:page,number:number,type:'sign'},
success: function(msg){
$('.jinsom-mycredit-table .sign').html(msg);
}
});

}
}
});
<?php }?>

<?php if($nickname_key_count>6){?>
laypage.render({
elem: 'jinsom-key-nickname-page',
theme:'#5fb878',
limit:6,
count: <?php echo $nickname_key_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .nickname').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/key-more.php",
data:{page:page,number:number,type:'nickname'},
success: function(msg){
$('.jinsom-mycredit-table .nickname').html(msg);
}
});

}
}
});
<?php }?>


<?php if($vip_number_key_count>6){?>
laypage.render({
elem: 'jinsom-key-vip-number-page',
theme:'#5fb878',
limit:6,
count: <?php echo $vip_number_key_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .vip_number').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/key-more.php",
data:{page:page,number:number,type:'vip_number'},
success: function(msg){
$('.jinsom-mycredit-table .vip_number').html(msg);
}
});

}
}
});
<?php }?>



});
</script>
