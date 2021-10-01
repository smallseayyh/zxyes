<?php
//聊天记录详情
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

if(isset($_POST['id'])){
$id=$_POST['id'];
$type=$_POST['type'];


global $wpdb;
if($type==1){
$table_name = $wpdb->prefix.'jin_message';
}else{
$table_name = $wpdb->prefix.'jin_message_group';
}

$datas = $wpdb->get_results("SELECT * FROM $table_name WHERE ID='$id' limit 1;");	

if($datas){
$html='';
foreach ($datas as $data) {

if($type==1){
$content=preg_replace("/<(\/?a.*?)>/si","",$data->msg_content);
if(!$data->status){
$status='<font style="color:#5fb878;">对方未读</font>';
}else{
$status='<font style="color:#f00;">对方已读</font>';
}
}else{
$content=preg_replace("/<(\/?a.*?)>/si","",$data->content);
$status='<font style="color:#f00;">已发送</font>';
}

$html.='
<div class="jinsom-invite-code-search-results">
<p><span>状态：</span>'.$status.'</p>
<p><span>内容：</span>'.$content.'</p>
</div>
';
}

$data_arr['code']=1;
$data_arr['msg']=$html;

}else{
$data_arr['code']=0;
$data_arr['msg']='不存在该记录！';
}

	

}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';	
}
header('content-type:application/json');
echo json_encode($data_arr);