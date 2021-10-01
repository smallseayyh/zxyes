<?php 
//查询通知记录 分页
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$page=$_POST['page'];
$number=$_POST['number'];
$offset=($page-1)*$number;
global $wpdb;
$table_name = $wpdb->prefix.'jin_notice';


$notice_data = $wpdb->get_results("SELECT * FROM $table_name where notice_type='notice' ORDER BY notice_time desc limit $offset,$number;");
if($notice_data){

foreach ($notice_data as $data) {
$id=$data->ID;
$do='
<m class="read" onclick="jinsom_admin_notice_read_form('.$id.')" style="color:#4CAF50;">查看</m>
<m class="edit" onclick="jinsom_admin_notice_edit_form('.$id.')" style="color:#2196F3;">编辑</m>
<m class="del" onclick="jinsom_admin_notice_del('.$id.',this)" style="color:#dd2b1e;">删除</m>';
echo '<li><span>'.strip_tags($data->notice_content).'</span><span>'.$do.'</span><span>'.$data->notice_time.'</span></li>';
}

}else{
echo jinsom_empty();
}


