<?php
//模块上传
require( '../../../../../wp-load.php' );
$user_id= $current_user->ID;
$type=$_POST['type'];
if(!current_user_can('level_10')){
$data_arr['code']=0;
$data_arr['msg']='你没有权限上传！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if($type=='pc/widget'||$type=='public/page'||$type=='mobile/page'||$type=='public/function'||$type=='public/gadget'){
$multiple=1;
}else{
$multiple=0;
}

$aaaa=$type;
if($multiple){//如果是多模块则多加一个地址
$upload_file_name=explode('.',$_FILES['file']['name']);
$aaaa=$type.'/'.$upload_file_name[0];
}

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$aaaa.'/index.php')){
$data_arr['code']=0;
$data_arr['msg']='上传失败！已经存在相同名称的模块！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}




//路径
$path='../../../../module/'.$type.'/';
if(!is_dir($path)){mkdir($path,0755,true);}

if(!$multiple){
jinsom_deldir($path);//清空目录
}

$name=$_FILES['file']['name'];
$size=$_FILES['file']['size'];
$tmp=$_FILES['file']['tmp_name'];

if(empty($name)){
$data_arr['code']=0;
$data_arr['msg']='不存在安装包！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$ext=substr(strrchr($name,'.'),1);
if($ext!='zip'){
$data_arr['code']=0;
$data_arr['msg']='请上传zip格式的安装包！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$file_name='module.zip';//文件名

if(move_uploaded_file($tmp,$path.$file_name)){
$file=$_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/'.$file_name;
$outPath=$_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/';
$zip=new ZipArchive();
$openRes=$zip->open($file);
if($openRes===TRUE){
$zip->extractTo($outPath);
$zip->close();
unlink($file);//删除本地安装包
}

if($multiple){//如果是多模块则多加一个地址
$upload_file_name=explode('.',$_FILES['file']['name']);
$type=$type.'/'.$upload_file_name[0];
}


if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/index.php')){//如果存在index.php文件
$module_headers = array(
'Module Name' => '模块名称',
'Module URI'  => 'https://q.jinsom.cn',
'Module Type' => '官方模块',
'Author Name' => '佚名',
'Author URI'  => '',
'Version'     => '1.0',
'Description' => '暂无描述',
);
$module_path=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/index.php');
preg_match_all('/\/\*[\s\S]*\*\//U',$module_path,$matches);

if(!strpos($matches[0][0],'Module Name')){
if(!$multiple){
jinsom_deldir($path);//清空目录
}
$data_arr['code']=0;
$data_arr['msg']='index.php文件不合法！1001';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

$module_info=explode("\n",$matches[0][0]);
array_shift($module_info);
array_pop($module_info);
foreach($module_info as $data){
$list_arr=explode(':',$data);//分割
if(array_key_exists($list_arr[0],$module_headers)){
if(isset($list_arr[2])){
$module_headers[$list_arr[0]]=$list_arr[1].':'.$list_arr[2];
}else{
$module_headers[$list_arr[0]]=$list_arr[1];	
}
}
}

if($module_headers['Module Name']=='模块名称'){
if(!$multiple){
jinsom_deldir($path);//清空目录
}
$data_arr['code']=0;
$data_arr['msg']='index.php文件不合法！1002';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


$logo=get_template_directory_uri().'/images/preference.png';
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/logo.jpg')){
$logo=content_url('/module/'.$type.'/logo.jpg');
}

$data_arr['ModuleLogo']=$logo;
$data_arr['ModuleName']=$module_headers['Module Name'];




if($multiple){
$option=get_option('LightSNS_Module_'.$_POST['type']);

if($option){
$option_arr=explode(',',$option);
$i=0;
foreach ($option_arr as $data){
if($upload_file_name[0]==$data){
unset($option_arr[$i]);
}
$i++;
}
array_push($option_arr,$upload_file_name[0]);
update_option('LightSNS_Module_'.$_POST['type'],implode(",",$option_arr));
}else{
update_option('LightSNS_Module_'.$_POST['type'],$upload_file_name[0]);//记录启用		
}
$data_arr['multiple_type']=$type;
}else{
$data_arr['ModuleURI']=$module_headers['Module URI'];
$data_arr['ModuleType']=$module_headers['Module Type'];
$data_arr['AuthorName']=$module_headers['Author Name'];
$data_arr['AuthorURI']=$module_headers['Author URI'];
$data_arr['Version']=$module_headers['Version'];
$data_arr['Description']=$module_headers['Description'];
update_option('LightSNS_Module_'.$type,1);//记录启用	
}


$data_arr['code']=1;
$data_arr['msg']='安装包上传成功！';
}else{
if(!$multiple){
jinsom_deldir($path);//清空目录
}
$data_arr['code']=0;
$data_arr['msg']='无法解析模块，安装包不存在index.php文件或打包层级不正确！';
}
}else{
$data_arr['code']=0;
$data_arr['msg']='安装包上传失败！';	
}

header('content-type:application/json');
echo json_encode($data_arr);



//清空目录
function jinsom_deldir($path){
if(is_dir($path)){
$p=scandir($path);
foreach($p as $val){
if($val !="." && $val !=".."){
if(is_dir($path.$val)){
jinsom_deldir($path.$val.'/');
@rmdir($path.$val.'/');
}else{
unlink($path.$val);
}
}
}
}
}