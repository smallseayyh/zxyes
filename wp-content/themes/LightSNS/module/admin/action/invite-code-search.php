<?php
//邀请码查询
require( '../../../../../../wp-load.php' );

//判断是否登录
if (!is_user_logged_in()){ 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否管理员
if (!current_user_can('level_10')){ 
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(isset($_POST['code'])){
$code=$_POST['code'];

if($code==''){
$data_arr['code']=0;
$data_arr['msg']='请输入要查询的邀请码！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



global $wpdb;
$table_name = $wpdb->prefix . 'jin_invite_code';
$datas = $wpdb->get_results("SELECT * FROM $table_name WHERE code='$code' limit 1;");	

if($datas){
$html='';
foreach ($datas as $data) {
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
$html.='
<div class="jinsom-invite-code-search-results">
<p><span>邀请码：</span>'.$data->code.'</p>
<p><span>状态：</span>'.$status.'</p>
<p><span>使用者：</span>'.$use_user.'</p>
<p><span>使用时间：</span>'.$use_time.'</p>
</div>
';
}

$data_arr['code']=1;
$data_arr['msg']=$html;

}else{
$data_arr['code']=0;
$data_arr['msg']='你输入的邀请码不存在！';
}

	

}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';	
}
header('content-type:application/json');
echo json_encode($data_arr);