<?php 
//修改密码
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
if(jinsom_is_admin($current_user->ID)){
$user_id=$_POST['user_id'];
}else{
$user_id=$current_user->ID;	
}

if(jinsom_is_black($user_id)&&!jinsom_is_admin($current_user->ID)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

if(isset($_POST['question'])&&isset($_POST['answer'])){

$question=htmlentities($_POST['question'],ENT_QUOTES,'UTF-8');
$answer=htmlentities($_POST['answer'],ENT_QUOTES,'UTF-8');

update_user_meta($user_id,'question',$question);
update_user_meta($user_id,'answer',$answer);

$data_arr['code']=1;
$data_arr['msg']='设置成功！';

}else{
$data_arr['code']=0;
$data_arr['msg']='非法请求！';		
}
header('content-type:application/json');
echo json_encode($data_arr);