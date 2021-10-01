<?php
require( '../../../../../wp-load.php' );
if(jinsom_is_admin($current_user->ID)){
$author_id=$_POST['author_id'];
}else{
$author_id=$current_user->ID;
}

//=========未登录
if (!is_user_logged_in()){
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


//更换背景封面
if(isset($_POST['number'])){
$number=(int)$_POST['number'];
$jinsom_member_bg_add=jinsom_get_option('jinsom_member_bg_add');
if($jinsom_member_bg_add){
if($jinsom_member_bg_add[$number]['vip']){
if(is_vip($author_id)||jinsom_is_admin($current_user->ID)){
update_user_meta($author_id,'skin',$number);	
$data_arr['code']=1;
$data_arr['msg']='设置成功！';
}else{
$data_arr['code']=2;
$data_arr['msg']='你不是VIP用户，请先充值！';		
}
}else{
update_user_meta($author_id,'skin',$number);	
$data_arr['code']=1;
$data_arr['msg']='设置成功！';	
}


}else{
$data_arr['code']=0;
$data_arr['msg']='管理员还没有添加背景图数据！';	
}

}else{
$data_arr['code']=0;
$data_arr['msg']='参数无效！';	
}

header('content-type:application/json');
echo json_encode($data_arr);

