<?php 
//管理员修改用户资料
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;

//判断是否管理团队
if(!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
$author_id=$_POST['author_id'];
// $type=$_POST['type'];

update_user_meta($author_id,$_POST['type'],$_POST['value']);


if($_POST['type']=='user_honor'){//头衔
if($_POST['value']!=''){
$honor_arr=explode(",",$_POST['value']);
$use_honor=get_user_meta($author_id,'use_honor',true);//获取用户当前使用的头衔
if(!in_array($use_honor,$honor_arr)){//如果用户当前使用的头衔不在管理员更新之后的头衔里面
update_user_meta($author_id,'use_honor',$honor_arr[0]);//将用户目前使用的头衔预设为第一个新的头衔。
}
update_user_meta($author_id,'user_honor',$_POST['value']);	
}else{
delete_user_meta($author_id,'user_honor');
}

}else if($_POST['type']=='user_power'&&current_user_can('level_10')){
update_user_meta($author_id,'user_power',$_POST['value']);
if($_POST['value']==4){
delete_user_meta($author_id,'session_tokens');
update_user_meta($author_id,'nickname',__('已重置','jinsom').'-'.$author_id);
}
}else{
if($_POST['value']){
update_user_meta($author_id,$_POST['type'],$_POST['value']);	
}else{
delete_user_meta($author_id,$_POST['type']);
}
}


$data_arr['code']=1;
$data_arr['msg']='修改成功！';
$data_arr['self']=$user_id==$author_id?1:0;
header('content-type:application/json');
echo json_encode($data_arr);

