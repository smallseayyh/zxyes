<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
global $wpdb;
$table_name = $wpdb->prefix.'jin_message_group';
?>
<!-- 单聊记录 -->
<div class="jinsom-admin-key-form recharge">

<div class="jinsom-admin-key-bottom">
<?php 
$datas = $wpdb->get_results("SELECT * FROM $table_name where type=1 ORDER BY ID desc limit 9;");
$datas_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name where type=1;");
if($datas){
?>
<div class="jinsom-mycredit-table chat-note">
<div class="title">
<span style="width:150px;">发送者</span>
<span style="width:150px;">群组名称</span>
<span style="width:250px;">聊天内容</span>
<span style="width:132px;">时间</span>
<span style="width:128px;">操作</span>
</div>
<div class="content chat-note">
<?php 
foreach ($datas as $data) {
$time=$data->msg_time;
$time_a=strtotime($time);
$time_b=date('Y-m-d',$time_a);
$today_date=date('Y-m-d');
if($today_date==$time_b){
$time='<font style="color:#f00;">'.$time.'</font>';
}

$content_match=preg_match ("/<img([^>]*)\s*src=('|\")([^'\"]+)('|\")/",$data->content);
if($content_match){
$content='[图片]';
}else{
$content=$data->content;
}


echo '
<li>
<span>'.jinsom_nickname_link($data->user_id).'</span>
<span><a href="'.get_category_link($data->bbs_id).'" target="_blank">'.get_category($data->bbs_id)->name.'</a></span>
<span onclick="jinsom_admin_read_chat_note(2,'.$data->ID.')">'.$content.'</span>
<span>'.$time.'</span>
<span>
<i class="read" onclick="jinsom_admin_read_chat_note(2,'.$data->ID.')">查看</i>
<i class="del" onclick="jinsom_admin_del_chat_note(2,'.$data->ID.',this)">删除</i>
</span>
</li>';
}
?>
</div>	
</div>
<div id="jinsom-chat-note-page" class="jinsom-mycredit-page"></div>
<?php 
}else{
echo jinsom_empty();
}?>
</div>

</div>


<script type="text/javascript">
layui.use('laypage', function(){
var laypage = layui.laypage;

<?php if($datas_count>9){?>
laypage.render({
elem: 'jinsom-chat-note-page',
theme:'#5fb878',
limit:9,
count: <?php echo $datas_count;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-mycredit-table .chat-note').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/chat-note-group-more.php",
data:{page:page,number:number},
success: function(msg){
$('.jinsom-mycredit-table .chat-note').html(msg);
}
});

}
}
});
<?php }?>




});
</script>
