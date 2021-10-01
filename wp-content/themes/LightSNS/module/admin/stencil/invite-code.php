<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name = $wpdb->prefix.'jin_invite_code';
?>
<!-- 邀请码 -->
<div class="jinsom-admin-key-form">
<div class="jinsom-admin-key-top" style="margin-bottom: 30px;">
<li class="opacity" onclick="jinsom_admin_invite_code_add_form()">邀请码生成</li>
<li class="opacity" onclick="jinsom_admin_invite_code_export_form()">邀请码导出</li>
<li class="opacity" onclick="jinsom_admin_invite_code_search_form()">邀请码查询</li>
<li class="opacity" onclick="jinsom_admin_invite_code_delete_form()">邀请码删除</li>
</div>


<div class="jinsom-admin-key-bottom">

<?php 
$invite_code_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY ID desc limit 6;");
$invite_code_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name;");
if($invite_code_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span>邀请码</span>
<span>状态</span>
<span>使用者</span>
<span>使用时间</span>
</div>
<div class="content credit">
<?php 
foreach ($invite_code_data as $data) {
if(!$data->status){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
if($data->use_user_id==''){
$use_user='--';
}else{
$use_user=jinsom_nickname_link($data->use_user_id);
}
if($data->use_time==''){
$use_time='--';	
}else{
$use_time=$data->use_time;	
}


echo '<li><span>'.$data->code.'</span><span>'.$status.'</span><span>'.$use_user.'</span><span>'.$use_time.'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-invite-code-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>




</div>

</div>


<script type="text/javascript">
layui.use('laypage', function(){
var laypage = layui.laypage;

<?php if($invite_code_count>6){?>
laypage.render({
elem: 'jinsom-invite-code-page',
theme:'#5fb878',
limit:6,
count: <?php echo $invite_code_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .credit').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/invite-code-more.php",
data:{page:page,number:number},
success: function(msg){
$('.jinsom-mycredit-table .credit').html(msg);
}
});

}
}
});
<?php }?>



});
</script>
