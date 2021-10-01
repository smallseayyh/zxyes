<?php 
//后台设置
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
if(isset($_GET['download'])){//下载设置数据

header("Content-type:application/octet-stream");
header("Accept-Ranges:bytes");
header("Content-Disposition:attachment;filename=网站后台_设置数据_".date("Y-m-d_H-i-s").".json");
header("Expires: 0");
header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
header("Pragma:public");
echo json_encode(get_option('jinsom_options'));

}else{

if(isset($_POST['backup'])){
$backup=$_POST['backup'];

if($backup=='delete'){
$status=delete_option('jinsom_options');
if($status){
$data_arr['code']=1;
$data_arr['msg']='设置数据已恢复默认！';
}else{
$data_arr['code']=0;
$data_arr['msg']='清空失败！';	
}
}else{
$backup_arr=json_decode(stripslashes(trim($backup)),true);
if(is_array($backup_arr)){
$status=update_option('jinsom_options',$backup_arr);
if($status){
$data_arr['code']=1;
$data_arr['msg']='导入成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='导入失败！';	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='导入的数据格式有误！';
}
}

}

header('content-type:application/json');
echo json_encode($data_arr);

}

