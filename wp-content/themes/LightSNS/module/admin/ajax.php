<?php 
//验证来路
$from_url=$_SERVER['HTTP_REFERER'];
//$data_arr['from_url']=$from_url;
if(!strstr($from_url,home_url())&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])){
$data_arr['code']=0;
$data_arr['msg']='非法请求！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}