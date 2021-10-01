<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name = $wpdb->prefix.'jin_notice';
?>
<!-- 全站通知-->
<div class="jinsom-admin-key-form">
<div class="jinsom-admin-key-top admin_notice" style="margin-bottom: 30px;">
<li class="opacity" onclick="jinsom_admin_notice_add_form()">发表全站通知</li>
</div>


<div class="jinsom-admin-key-bottom admin_notice">

<?php 
$notice_data = $wpdb->get_results("SELECT * FROM $table_name where notice_type='notice' ORDER BY notice_time desc limit 6;");
$notice_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name where notice_type='notice';");
if($notice_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span>通知内容</span>
<span>发表时间</span>
<span>操作</span>
</div>
<div class="content credit">
<?php 
foreach ($notice_data as $data) {
$id=$data->ID;
$do='
<m class="read" onclick="jinsom_admin_notice_read_form('.$id.')" style="color:#4CAF50;">查看</m>
<m class="edit" onclick="jinsom_admin_notice_edit_form('.$id.')" style="color:#2196F3;">编辑</m>
<m class="del" onclick="jinsom_admin_notice_del('.$id.',this)" style="color:#dd2b1e;">删除</m>';
echo '<li><span>'.strip_tags($data->notice_content).'</span><span>'.$do.'</span><span>'.$data->notice_time.'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-notice-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>




</div>

</div>


<script type="text/javascript">
layui.use('laypage', function(){
var laypage = layui.laypage;

<?php if($notice_count>6){?>
laypage.render({
elem: 'jinsom-notice-page',
theme:'#5fb878',
limit:6,
count: <?php echo $notice_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .credit').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/notice-more.php",
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
