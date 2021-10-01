<?php 
//移除模块
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$type=$_POST['type'];
$action=$_POST['action'];
$list_name=$_POST['list_name'];//目录名称
if($type=='pc/widget'||$type=='public/page'||$type=='mobile/page'||$type=='public/function'||$type=='public/gadget'){
$multiple=1;
}else{
$multiple=0;
}

if($action=='remove'){//=====================卸载模块
if($multiple){
$option=get_option('LightSNS_Module_'.$type);
if($option){
update_option('LightSNS_Module_'.$type,$option.','.$list_name);
}else{//没有模块
update_option('LightSNS_Module_'.$type,$list_name);
}
//删除文件夹
$path='../../../../../module/'.$type.'/'.$list_name.'/';
jinsom_deldir($path);//清空目录
rmdir('../../../../../module/'.$type.'/'.$list_name);

}else{//单模块
delete_option('LightSNS_Module_'.$type);
$path='../../../../../module/'.$type.'/';
jinsom_deldir($path);//清空目录
}

$data_arr['code']=1;
$data_arr['msg']='模块已卸载！';


}else if($action=='active'){//=====================启用模块
if($multiple){
$option=get_option('LightSNS_Module_'.$type);
if($option){
$option_arr=explode(',',$option);
$i=0;
foreach ($option_arr as $data){
if($list_name==$data){
unset($option_arr[$i]);
}
$i++;
}
array_push($option_arr,$list_name);
update_option('LightSNS_Module_'.$type,implode(",",$option_arr));
}else{//没有模块
update_option('LightSNS_Module_'.$type,$list_name);
}
}else{
update_option('LightSNS_Module_'.$type,1);
}
$data_arr['code']=1;
$data_arr['msg']='模块已启用！';
}else if($action=='close'){//======================关闭模块
if($multiple){
$option=get_option('LightSNS_Module_'.$type);
$option_arr=explode(',',$option);
if(count($option_arr)>1){
$i=0;
foreach ($option_arr as $data){
if($list_name==$data){
unset($option_arr[$i]);
}
$i++;
}
update_option('LightSNS_Module_'.$type,implode(",",$option_arr));
}else{//只有一个模块
delete_option('LightSNS_Module_'.$type);
}
}else{
delete_option('LightSNS_Module_'.$type);	
}
$data_arr['code']=1;
$data_arr['msg']='模块已关闭！';
}else if($action=='read'){//============================查看模块
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/index.php')){//存在模块
$module_headers = array(
'Module Name' => '模块名称',
'Module URI'  => 'https://q.jinsom.cn',
'Module Type' => '第三方模块',
'Author Name' => '佚名',
'Author URI'  => '',
'Version'     => '1.0',
'Description' => '暂无描述',
);
$module_path=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/index.php');
preg_match_all('/\/\*[\s\S]*\*\//U',$module_path,$matches);
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

$logo=get_template_directory_uri().'/images/preference.png';
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/logo.jpg')){
$logo=content_url('/module/'.$type.'/logo.jpg');
}

$type_arr=explode('/',$type);//分割
$list_name=end($type_arr);
array_pop($type_arr);//移除最后一个数组
$type=implode("/",$type_arr);

$option=get_option('LightSNS_Module_'.$type);
$install='<div class="install trash opacity" onclick=\'jinsom_remove_module("'.$type.'",this)\' data="'.$list_name.'"><i class="fa fa-trash"></i> 卸载</div>
<div class="install active opacity" onclick=\'jinsom_active_module("'.$type.'",this)\' data="'.$list_name.'"><i class="fa fa-plug"></i> 启用</div>';
if($option){
$option_arr=explode(',',$option);//分割
if(in_array($list_name, $option_arr)){
$install='<div class="install trash opacity" onclick=\'jinsom_remove_module("'.$type.'",this)\' data="'.$list_name.'"><i class="fa fa-trash"></i> 卸载</div>
<div class="close opacity" onclick=\'jinsom_close_module("'.$type.'",this)\' data="'.$list_name.'"><i class="fa fa-close"></i> 关闭</div>
';
}
}

$html= '
<div class="jinsom-panel-module-tab" style="padding:20px;">
<div class="layui-tab-item layui-show">
<li style="width:100%;margin-top:0;">
'.$install.'
<div class="content">
<div class="cover"><img src="'.$logo.'"></div>
<div class="info">
<div class="name">'.$module_headers['Module Name'].'</div>
<div class="author"><span>作者：'.$module_headers['Author Name'].'</span><span>版本：'.$module_headers['Version'].'</span></div>
<div class="bug"><span><i class="fa fa-check-square-o"></i> '.$module_headers['Module Type'].'</span><a href="'.$module_headers['Module URI'].'" target="_blank"><i class="fa fa-edit"></i> 问题与建议反馈</a></span></div>
</div>
</div>
<div class="desc">'.$module_headers['Description'].'</div>
</li>
<p style="color:#ccc;">该模块目录路径：'.$_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$_POST['type'].'</p>
</div></div>
';

$data_arr['code']=1;
$data_arr['html']=$html;
}else{//不存在模块
$data_arr['code']=0;
$data_arr['msg']='不存在该模块！';
}



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