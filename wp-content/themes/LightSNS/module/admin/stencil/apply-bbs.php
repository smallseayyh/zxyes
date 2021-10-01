<?php 
//论坛开通申请
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name = $wpdb->prefix.'jin_bbs_note';
?>

<div class="jinsom-admin-key-form">

<div class="jinsom-admin-key-bottom bbs">

<?php 
$bbs_data = $wpdb->get_results("SELECT * FROM $table_name where type='bbs' ORDER BY time desc limit 6;");
$bbs_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name where type='bbs';");
if($bbs_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span>用户</span>
<span>论坛名称</span>
<span>时间</span>
<span>操作</span>
</div>
<div class="content credit">
<?php 
foreach ($bbs_data as $data) {
$id=$data->ID;
$user_id=$data->user_id;
$status=$data->status;
if($status==0||$status==2){//未审核和拒绝
$bbs='<a>'.$data->title.'</a>';
}else{
$bbs='<a href="'.get_category_link($data->bbs_id).'" target="_blank" title="论坛ID：'.$data->bbs_id.'">'.get_category($data->bbs_id)->name.'</a>';
}
if($status==0){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_read_form('.$id.')" style="color:#4CAF50;">点击查看</m>';
}else if($status==1){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_read_form('.$id.')">已经通过</m>';	
}else if($status==2){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_read_form('.$id.')">已经拒绝</m>';	
}
echo '<li id="jinsom-admin-apply-bbs-admin-'.$id.'"><span>'.jinsom_nickname_link($user_id).'</span><span>'.$bbs.'</span><span>'.jinsom_timeago($data->time).'</span><span>'.$do.'</span></li>';
}
?>
</div>	
</div>
<div id="jinsom-bbs-admin-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>




</div>

</div>


<script type="text/javascript">
layui.use('laypage', function(){
var laypage = layui.laypage;

<?php if($bbs_count>6){?>
laypage.render({
elem: 'jinsom-bbs-admin-page',
theme:'#5fb878',
limit:6,
count: <?php echo $bbs_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .credit').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/apply-bbs-more.php",
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
