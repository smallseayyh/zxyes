<?php 
//加载更多卡密||卡密分页
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$page=$_POST['page'];
$number=$_POST['number'];
$offset=($page-1)*$number;
global $wpdb;
$table_name = $wpdb->prefix.'jin_key';

//查询金币卡密
if(isset($_POST['type'])&&$_POST['type']=='credit'){
$credit_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='credit' ORDER BY ID desc limit $offset,$number;");	
if($credit_key_data){
foreach ($credit_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' '.jinsom_get_option('jinsom_credit_name').'</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
}else{
echo jinsom_empty();
}
}

//查询会员卡密
if(isset($_POST['type'])&&$_POST['type']=='vip'){
$vip_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='vip' ORDER BY ID desc limit $offset,$number;");	
if($vip_key_data){
foreach ($vip_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' 天</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
}else{
echo jinsom_empty();
}
}

//查询经验卡密
if(isset($_POST['type'])&&$_POST['type']=='exp'){
$exp_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='exp' ORDER BY ID desc limit $offset,$number;");	
if($exp_key_data){
foreach ($exp_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' 经验值</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
}else{
echo jinsom_empty();
}
}


//查询补签卡密
if(isset($_POST['type'])&&$_POST['type']=='sign'){
$sign_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='sign' ORDER BY ID desc limit $offset,$number;");	
if($sign_key_data){
foreach ($sign_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' 张</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
}else{
echo jinsom_empty();
}
}

//查询补签卡密
if(isset($_POST['type'])&&$_POST['type']=='nickname'){
$nickname_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='nickname' ORDER BY ID desc limit $offset,$number;");	
if($nickname_key_data){
foreach ($nickname_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' 张</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
}else{
echo jinsom_empty();
}
}


//查询成长值
if(isset($_POST['type'])&&$_POST['type']=='vip_number'){
$vip_number_key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  type='vip_number' ORDER BY ID desc limit $offset,$number;");	
if($vip_number_key_data){
foreach ($vip_number_key_data as $data) {
if($data->status==0){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
echo '<li><span>'.$data->key_number.'</span><span>'.$data->number.' 成长值</span><span>'.$status.'</span><span>'.$data->expiry.'</span></li>';
}
}else{
echo jinsom_empty();
}
}

