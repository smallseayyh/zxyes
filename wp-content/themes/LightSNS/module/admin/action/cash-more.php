<?php 
//查询提现记录 分页
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$credit_name=jinsom_get_option('jinsom_credit_name');
if(!current_user_can('level_10')){exit();}
$page=$_POST['page'];
$number=$_POST['number'];
$type=$_POST['type'];
$offset=($page-1)*$number;
global $wpdb;
$table_name = $wpdb->prefix.'jin_cash';


if($type=='all'){
$cash_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY cash_time desc limit $offset,$number;");
if($cash_data){
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
}else{
echo jinsom_empty();
}

}else if($type=='wait'){
$cash_data = $wpdb->get_results("SELECT * FROM $table_name where status=0 ORDER BY cash_time desc limit $offset,$number;");
if($cash_data){
foreach ($cash_data as $data) {
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
}else{
echo jinsom_empty();
}
}else if($type=='agree'){
$cash_data = $wpdb->get_results("SELECT * FROM $table_name where status=1 ORDER BY cash_time desc limit $offset,$number;");
if($cash_data){
foreach ($cash_data as $data) {
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
}else{
echo jinsom_empty();
}
}else if($type=='refuse'){
$cash_data = $wpdb->get_results("SELECT * FROM $table_name where status=2 ORDER BY cash_time desc limit $offset,$number;");
if($cash_data){
foreach ($cash_data as $data) {
$status='拒绝';	
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
}else{
echo jinsom_empty();
}
}

