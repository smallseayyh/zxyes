<?php 
//查询单聊聊天记录 分页
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$page=$_POST['page'];
$number=$_POST['number'];
$offset=($page-1)*$number;
global $wpdb;
$table_name = $wpdb->prefix.'jin_message_group';
$datas = $wpdb->get_results("SELECT * FROM $table_name where type=1 ORDER BY ID desc limit $offset,$number;");	
if($datas){
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
}else{
echo jinsom_empty();
}

