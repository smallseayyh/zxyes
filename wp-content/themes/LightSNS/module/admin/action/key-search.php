<?php
//卡密查询
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

if(isset($_POST['key'])){
$key=$_POST['key'];

if($key==''){
$data_arr['code']=0;
$data_arr['msg']='请输入要查询的卡密！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



global $wpdb;
$table_name = $wpdb->prefix . 'jin_key';
$datas = $wpdb->get_results("SELECT * FROM $table_name WHERE key_number='$key' limit 1;");	

if($datas){
$html='';
foreach ($datas as $data) {
if(!$data->status){
$status='<font style="color:#5fb878;">未使用</font>';
}else{
$status='<font style="color:#f00;">已使用</font>';
}
if($data->type=='credit'){
$type=jinsom_get_option('jinsom_credit_name').'卡';
}else if($data->type=='vip'){
$type='会员卡';
}else if($data->type=='exp'){
$type='经验卡';
}else if($data->type=='sign'){
$type='补签卡';
}else if($data->type=='nickname'){
$type='改名卡';
}else if($data->type=='vip_number'){
$type='成长值卡';
}
if(!$data->user_id){
$use_user='--';
}else{
$use_user=jinsom_nickname_link($data->user_id);
}

$html.='
<div class="jinsom-invite-code-search-results">
<p><span>卡密：</span>'.$data->key_number.'</p>
<p><span>类型：</span>'.$type.'</p>
<p><span>状态：</span>'.$status.'</p>
<p><span>额度：</span>'.$data->number.'</p>
<p><span>使用者：</span>'.$use_user.'</p>
<p><span>有效期：</span>'.$data->expiry.'</p>
</div>
';
}

$data_arr['code']=1;
$data_arr['msg']=$html;

}else{
$data_arr['code']=0;
$data_arr['msg']='你输入的卡密不存在！';
}

	

}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';	
}
header('content-type:application/json');
echo json_encode($data_arr);