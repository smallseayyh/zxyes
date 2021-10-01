<?php 
//版权为林金胜所有，未经授权，不得实施复制传播倒卖等任何侵权行为
if ( ! defined( 'ABSPATH' ) ) { die; }
if( ! class_exists( 'LightSNS_Field_module' ) ) {
class LightSNS_Field_module extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

//默认模块模版
function jinsom_default_module_template($type){
$logo=get_template_directory_uri().'/images/preference.png';
return '
<li class="default">
<div class="install opacity" type="'.$type.'" lay-data=\'{data:{type:"'.$type.'"}}\'><i class="fa fa-cloud-upload"></i> 上传</div>
<div class="content">
<div class="cover"><img src="'.$logo.'"></div>
<div class="info">
<div class="name">默认模块</div>
<div class="author"><span>作者：jinsom</span><span>版本：1.0</span></div>
<div class="bug"><span><i class="fa fa-check-square-o"></i> 官方模块</span><a href="https://q.jinsom.cn/bbs/bug" target="_blank"><i class="fa fa-edit"></i> 问题与建议反馈</a></span></div>
</div>
</div>
<div class="desc">这是程序默认模块</div>
</li>';
}

function jinsom_module_template($type){
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

if(get_option('LightSNS_Module_'.$type)){
$install='<div class="install trash opacity" onclick=\'jinsom_remove_module("'.$type.'",this)\'><i class="fa fa-trash"></i> 卸载</div>
<div class="close opacity" onclick=\'jinsom_close_module("'.$type.'",this)\'><i class="fa fa-close"></i> 关闭</div>
';
}else{
$install='<div class="install trash opacity" onclick=\'jinsom_remove_module("'.$type.'",this)\'><i class="fa fa-trash"></i> 卸载</div>
<div class="install active opacity" onclick=\'jinsom_active_module("'.$type.'",this)\'><i class="fa fa-plug"></i> 启用</div>';
}

return '
<li>
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
';

}else{//不存在模块
return jinsom_default_module_template($type);
}
}

//多模块模版
function jinsom_module_template_multiple($type){
echo '<li class="cell upload" type="'.$type.'"><i class="fa fa-cloud-upload" lay-data=\'{data:{type:"'.$type.'"}}\'><span>上传模块</span></i></li>';
$widget_dir=$_SERVER['DOCUMENT_ROOT'].'/wp-content/module/'.$type.'/';

if(is_dir($widget_dir)){//存在文件夹
$file_arr=scandir($widget_dir);
foreach ($file_arr as $data){
if($data==='.'||$data==='..'){
continue;
}
if(!strpos($data,'.')){
if(file_exists($widget_dir.$data.'/index.php')){
$module_path=file_get_contents($widget_dir.$data.'/index.php');
preg_match_all('/\/\*[\s\S]*\*\//U',$module_path,$matches);
if(strpos($matches[0][0],'Module Name')){//index文件包含模块信息

$module_info=explode("\n",$matches[0][0]);
array_shift($module_info);
$list_arr=explode(':',$module_info[0]);//模块的名称信息

$logo=get_template_directory_uri().'/images/preference.png';
if(file_exists($widget_dir.$data.'/logo.jpg')){
$logo=content_url('/module/'.$type.'/'.$data.'/logo.jpg');
}

$btn='<span class="no">已关闭模块</span>';
$option=get_option('LightSNS_Module_'.$type);
if($option){
$option_arr=explode(',',$option);//分割
if(in_array($data, $option_arr)){//已经启用
$btn='<span class="active">已启用模块</span>';
}
}
echo '<li class="cell opacity" data="'.$type.'/'.$data.'" onclick="jinsom_read_module(this)">'.$btn.'<img src="'.$logo.'"><p>'.$list_arr[1].'</p></li>';
}

}
}
}
}

}
?>
<style type="text/css">
.jinsom-panel-module-tab .layui-tab-item li {
    margin-top: 15px;
    width: 48%;
    position: relative;
    margin-right: 4%;
}
.jinsom-panel-module-tab .layui-tab-item li:nth-child(2n) {
    margin-right: 0;
}
.jinsom-panel-module-tab .layui-tab-item {
    min-height: 170px;
}
.jinsom-panel-module-tab li .content {
    display: flex;
}
.jinsom-panel-module-tab li .content .cover {
    margin-right: 15px;
}
.jinsom-panel-module-tab li .content .cover img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
}
.jinsom-panel-module-tab li .content .name {
    font-size: 20px;
    margin-bottom: 15px;
    line-height: 26px;
}
.jinsom-panel-module-tab li .content .author span,.jinsom-panel-module-tab li .content .bug span {
    margin-right: 15px;
    color: #999;
}
.jinsom-panel-module-tab li .desc {
    color: #333333;
    background: #f7f7f7;
    padding: 15px;
    border-radius: 3px;
    margin-top: 15px;
    line-height: 22px;
}
.jinsom-panel-module-tab li .desc a {
    color: #2196F3;
    margin: 0 5px;
}
.jinsom-panel-module-tab li .content .bug a {
    color: #2196F3;
}
.jinsom-panel-module-tab li .content .author {
    margin-bottom: 15px;
}

.jinsom-panel-module-tab .layui-tab-item li .install {
    position: absolute;
    right: 0;
    background-color: #24aa42;
    color: #fff;
    padding: 6px 8px;
    border-radius: 2px;
    cursor: pointer;
}
.jinsom-panel-module-tab .layui-tab-item li .install.trash {
    background-color: #e14d43;
}
.jinsom-panel-module-tab .layui-tab-item li .close,.jinsom-panel-module-tab .layui-tab-item li .active {
    position: absolute;
    right: 70px;
    background-color: #9E9E9E;
    color: #fff;
    padding: 6px 8px;
    border-radius: 2px;
    cursor: pointer;
}
.jinsom-panel-module-tab .layui-tab-item li .active {
    background-color: #0085ba;
}

.jinsom-panel-module-tab .layui-tab-item.multiple {
    width: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    height: 200px;
}
.jinsom-panel-module-tab .layui-tab-item li.cell {
    width: 120px;
    height: 120px;
    text-align: center;
    cursor: pointer;
    margin-right: 0;
    margin-bottom: 15px;
    float: left;
}
.jinsom-panel-module-tab .layui-tab-item li.cell img {
    width: 100px;
    height: 100px;
    border-radius: 4px;
}
.jinsom-panel-module-tab .layui-tab-item li.cell p {
    margin-top: 10px;
}
.jinsom-panel-module-tab .layui-tab-item li.cell>i {
    width: 100px;
    height: 100px;
    font-size: 30px;
    background-color: #eee;
    color: #888;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-flow: column;
    margin: auto;
}
.jinsom-panel-module-tab .layui-tab-item li.cell>i>span {
    font-size: 12px;
    display: block;
}
.jinsom-panel-module-tab .layui-tab-item li.cell>span.active, .jinsom-panel-module-tab .layui-tab-item li.cell>span.no {
    color: #fff;
    padding: 2px 6px;
    border-radius: 2px;
    cursor: pointer;
    font-size: 12px;
    right: 10px;
    position: absolute;
    width: 100px;
    box-sizing: border-box;
    opacity: 0.7;
}
.jinsom-panel-module-tab .layui-tab-item li.cell>span.no{
    background-color: #9E9E9E;
}
.jinsom-panel-module-tab .layui-tab-item .tips {
    color: #999;
    font-size: 12px;
    padding-left: 4px;
    border-left: 3px solid #9E9E9E;
}
.jinsom-panel-module-tab .layui-tab-title li>i {
    width: 5px;
    height: 5px;
    background-color: #4CAF50;
    display: inline-block;
    border-radius: 100%;
    margin-right: 4px;
    margin-bottom: 2px;
}
.jinsom-panel-module-tab .layui-tab-title li {
    min-width: 50px;
    padding: 0px 15px;
}
</style>

<fieldset class="layui-elem-field">
<legend>电脑端模块</legend>
<div class="layui-field-box">
<div class="layui-tab jinsom-panel-module-tab">
<ul class="layui-tab-title">
<li class="layui-this">首页</li>
<li>头部</li>
<li>底部</li>
<li>个人主页</li>
<li>话题页</li>
<li>登录内页</li>
<li><i></i>小工具</li>
</ul>
<div class="layui-tab-content">
<div class="layui-tab-item layui-show"><?php echo jinsom_module_template('pc/home');?></div>
<div class="layui-tab-item">
<?php echo jinsom_module_template('pc/header');?>
</div>
<div class="layui-tab-item"><?php echo jinsom_module_template('pc/footer');?></div>
<div class="layui-tab-item"><?php echo jinsom_module_template('pc/member');?></div>
<div class="layui-tab-item"><?php echo jinsom_module_template('pc/topic');?></div>
<div class="layui-tab-item"><?php echo jinsom_module_template('pc/login_page');?></div>
<div class="layui-tab-item multiple">
<div class="tips">已启用的模块，请到WordPress后台-外观-小工具-选择对应的小工具</div>
<?php jinsom_module_template_multiple('pc/widget')?>
</div>
</div>
</div>
</div>
</fieldset>


<fieldset class="layui-elem-field">
<legend>移动端模块</legend>
<div class="layui-field-box">
<div class="layui-tab jinsom-panel-module-tab">
<ul class="layui-tab-title">
<li class="layui-this">SNS首页</li>
<li>消息页面</li>
<li>发现页面</li>
<li>我的页面</li>
<li>左侧栏</li>
<li>右侧栏</li>
<li><i></i>页面</li>
</ul>
<div class="layui-tab-content">
<div class="layui-tab-item layui-show"><?php echo jinsom_module_template('mobile/sns');?></div>
<div class="layui-tab-item"><?php echo jinsom_module_template('mobile/notice');?></div>
<div class="layui-tab-item"><?php echo jinsom_module_template('mobile/find');?></div>
<div class="layui-tab-item"><?php echo jinsom_module_template('mobile/mine');?></div>
<div class="layui-tab-item"><?php echo jinsom_module_template('mobile/left_sidebar');?></div>
<div class="layui-tab-item"><?php echo jinsom_module_template('mobile/right_sidebar');?></div>
<div class="layui-tab-item"><?php echo jinsom_module_template_multiple('mobile/page');?></div>
</div>
</div>
</div>
</fieldset>

<fieldset class="layui-elem-field">
<legend>公共模块</legend>
<div class="layui-field-box">
<div class="layui-tab jinsom-panel-module-tab">
<ul class="layui-tab-title">
<li class="layui-this"><i></i>页面模版</li>
<li><i></i>小部件</li>
<li><i></i>函数文件</li>
</ul>
<div class="layui-tab-content">
<div class="layui-tab-item layui-show multiple">
<div class="tips">已启用的模块，请到WordPress后台-新建页面-选择对应的页面模版</div>
<?php jinsom_module_template_multiple('public/page')?>
</div>
<div class="layui-tab-item multiple">
<div class="tips">启用模块后，可实现局部功能定制</div>
<?php jinsom_module_template_multiple('public/gadget')?>
</div>
<div class="layui-tab-item">
<div class="tips">已启用的模块，主题将加载这些函数模块</div>
<?php echo jinsom_module_template_multiple('public/function');?>
</div>
</div>
</div>
</div>
</fieldset>

<script type="text/javascript">

layui.use('upload', function(){
var upload = layui.upload;

//单个模块上传
upload.render({
elem: '.jinsom-panel-module-tab .layui-tab-item li.default .install',
url: jinsom.theme_url+'/module/upload/module.php',
accept:'file',
before: function(){
this.item.html('<i class="fa fa-spin fa-spinner"></i> 上传中...');
},
done: function(msg,index,upload){
item=this.item;
layer.msg(msg.msg);
item.next().val('');//清空上传
if(msg.code==1){
item.siblings('.content').find('img').attr('src',msg.ModuleLogo);
item.siblings('.content').find('.name').text(msg.ModuleName);
item.siblings('.content').find('.author').children('span').eq(0).text('作者：'+msg.AuthorName);
item.siblings('.content').find('.author').children('span').eq(1).text('版本：'+msg.Version);
item.siblings('.content').find('.bug').children('span').html('<i class="fa fa-check-square-o"></i> '+msg.ModuleType);
item.siblings('.content').find('.bug').children('a').attr('href',msg.ModuleURI);
item.siblings('.desc').html(msg.Description);
item.after('<div class="install trash opacity" onclick=\'jinsom_remove_module("'+item.attr('type')+'",this)\'><i class="fa fa-trash"></i> 卸载</div>\
<div class="close opacity" onclick=\'jinsom_close_module("'+item.attr('type')+'",this)\'><i class="fa fa-close"></i> 关闭</div>\
');
item.remove();
// jinsom_admin_save_setting(0);//保存
function c(){window.location.reload();}setTimeout(c,2000);//刷新页面
}else{
item.html('<i class="fa fa-cloud-upload"></i> 上传');	
}
},
error: function(index, upload){
this.item.next().val('');//清空上传
this.item.html('<i class="fa fa-cloud-upload"></i> 上传');	
}
});

//多个模块上传
upload.render({
elem: '.jinsom-panel-module-tab .layui-tab-item li.cell.upload i',
url: jinsom.theme_url+'/module/upload/module.php',
accept:'file',
before: function(){
this.item.children('span').text('上传中...');
},
done: function(msg,index,upload){
item=this.item;
layer.msg(msg.msg);
item.next().val('');//清空上传
this.item.children('span').text('上传模块');
if(msg.code==1){
item.parent().after('<li class="cell opacity" data="'+msg.multiple_type+'" onclick="jinsom_read_module(this)"><span class="active">已启用模块</span><img src="'+msg.ModuleLogo+'"><p>'+msg.ModuleName+'</p></li>');
// jinsom_admin_save_setting(0);//保存
function c(){window.location.reload();}setTimeout(c,2000);//刷新页面
}
},
error: function(index, upload){
this.item.next().val('');//清空上传
this.item.children('span').text('上传模块');  
}
});


});

//卸载模块
function jinsom_remove_module(type,obj){
layer.load(1);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/module.php",
data:{type:type,action:'remove',list_name:$(obj).attr('data')},
success: function(msg){
layer.closeAll('loading');
layer.msg(msg.msg);
item=$(obj);
if(msg.code==1){
$(obj).parents('li').fadeTo("slow",0.06, function(){
$(this).slideUp(0.06, function() {
$(this).remove();
});
});
function c(){window.location.reload();}setTimeout(c,2000);
}
}
});
}

//启用模块
function jinsom_active_module(type,obj){
list_name=$(obj).attr('data');
layer.load(1);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/module.php",
data:{type:type,action:'active',list_name:list_name},
success: function(msg){
layer.closeAll('loading');
layer.msg(msg.msg);
item=$(obj);
item.after('<div class="close opacity" onclick=\'jinsom_close_module("'+type+'",this)\' data="'+list_name+'"><i class="fa fa-close"></i> 关闭</div>');

if(list_name){
$('.jinsom-panel-module-tab .layui-tab-item li.cell[data="'+type+'/'+list_name+'"]').children('span').removeClass('no').addClass('active').html('已启用模块');
}

item.remove();
// jinsom_admin_save_setting(0);//保存
function c(){window.location.reload();}setTimeout(c,2000);
}
});	
}

//关闭模块
function jinsom_close_module(type,obj){
list_name=$(obj).attr('data');
layer.load(1);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/module.php",
data:{type:type,action:'close',list_name:list_name},
success: function(msg){
layer.closeAll('loading');
layer.msg(msg.msg);
item=$(obj);
item.after('<div class="active opacity" onclick=\'jinsom_active_module("'+type+'",this)\' data="'+list_name+'"><i class="fa fa-plug"></i> 启用</div>');
if(list_name){
$('.jinsom-panel-module-tab .layui-tab-item li.cell[data="'+type+'/'+list_name+'"]').children('span').removeClass('active').addClass('no').html('已关闭模块');
}
item.remove();
function c(){window.location.reload();}setTimeout(c,2000);
}
});	
}



//查看具体模块
function jinsom_read_module(obj){
layer.load(1);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/module.php",
data:{type:$(obj).attr('data'),action:'read'},
success: function(msg){
layer.closeAll('loading');
if(msg.code==1){
layer.open({
type:1,
title:'模块详情',
fixed: false,
resize:false,
area: ['560px'],
content: msg.html
});
}else{
layer.msg(msg.msg);
}

}
});

}
</script>

<?php }

}
}