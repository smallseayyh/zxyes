<?php 
//版主申请
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name = $wpdb->prefix.'jin_bbs_note';
?>

<div class="jinsom-admin-key-form">

<div class="jinsom-admin-key-bottom bbs-admin">

<?php 
$bbs_data = $wpdb->get_results("SELECT * FROM $table_name where type='bbs_admin' ORDER BY time desc limit 6;");
$bbs_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name where type='bbs_admin';");
if($bbs_data){
?>
<div class="jinsom-mycredit-table">
<div class="title">
<span>用户</span>
<span>类型</span>
<span>论坛</span>
<span>时间</span>
<span>操作</span>
</div>
<div class="content credit">
<?php 
foreach ($bbs_data as $data) {
$id=$data->ID;
$user_id=$data->user_id;
$bbs_id=$data->bbs_id;
if($data->note_type=='a'){
$admin_name=get_term_meta($bbs_id,'admin_a_name',true);
}else{
$admin_name=get_term_meta($bbs_id,'admin_b_name',true);
}
$bbs='<a href="'.get_category_link($bbs_id).'" target="_blank" title="论坛ID：'.$bbs_id.'">'.get_category($bbs_id)->name.'</a>';
if($data->status==0){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_admin_read_form('.$id.')" style="color:#4CAF50;">点击查看</m>';
}else if($data->status==1){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_admin_read_form('.$id.')">已经通过</m>';	
}else if($data->status==2){
$do='<m class="read" onclick="jinsom_admin_apply_bbs_admin_read_form('.$id.')">已经拒绝</m>';	
}
echo '<li id="jinsom-admin-apply-bbs-admin-'.$id.'"><span>'.jinsom_nickname_link($user_id).'</span><span>'.$admin_name.'</span><span>'.$bbs.'</span><span>'.jinsom_timeago($data->time).'</span><span>'.$do.'</span></li>';
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
url:jinsom.jinsom_ajax_url+"/admin/action/apply-bbs-admin-more.php",
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
