<?php
/*
Template Name:登录页面（内页）
*/
if(is_user_logged_in()){
header("Location:".home_url());
exit();
}

if(get_option('LightSNS_Module_pc/login_page')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/pc/login_page/index.php');
}else{

wp_head();
$site_name=jinsom_get_option('jinsom_site_name');//网站名称
$jinsom_keyword = jinsom_get_option('jinsom_keyword');
$jinsom_description = jinsom_get_option('jinsom_description');
$jinsom_custom_css_file = jinsom_get_option('jinsom_custom_css_file');//自定义css文件
$jinsom_custom_js_file_header = jinsom_get_option('jinsom_custom_js_file_header');//自定义js文件
$jinsom_custom_css = jinsom_get_option('jinsom_custom_css');

$jinsom_login_page_bg = jinsom_get_option('jinsom_login_page_bg');


$user_query = new WP_User_Query( array ( 
'orderby' => 'registered', 
'count_total'=>false,
'order' => 'DESC',
'number' => 25
));
?>
<title><?php echo $site_name;?>-<?php _e('登录','jinsom');?></title>
<meta name="keywords" content="<?php echo $jinsom_keyword;?>" />
<meta name="description" content="<?php echo $jinsom_description;?>"/> 
<style type="text/css">
*:focus{
    outline: none;
}
.opacity:hover{
    opacity: 0.8;
}
.clear:after{
    display: block;
    content: '';
    clear: both;
}
li{
	list-style: none;
}
body {
    margin: 0;
    background-color: #89a6c6;
    background: linear-gradient(to right,#89a6c6, rgb(160, 191, 225));
    <?php if($jinsom_login_page_bg){?>
    background-image: url(<?php echo $jinsom_login_page_bg;?>);
    background-repeat: no-repeat,no-repeat;
    background-position: center center,center center;
    background-size: cover,cover;
    <?php }?>
    overflow: hidden;
    
}
.jinsom-login-page {
    display: flex;
    align-items: center;
    height: 100%;
}
.jinsom-login-page-form {
    margin-left: auto;
    margin-right: auto;
    background-color: rgba(255, 255, 255, 0.7);
    padding: 20px;
    display: flex;
    width: 640px;
    border-radius: 4px;
    box-sizing: border-box;
    height: 320px;
}
.jinsom-login-page-form .left {
    width: 300px;
    padding-right: 20px;
    flex: 1;
    border-right: 1px solid rgba(245, 245, 245, 0.5);
}
.jinsom-login-page-form .right {
    margin-left: 20px;
    flex: 1;
}
.jinsom-login-page-form .right li {
    float: left;
    margin-bottom: 10px;
    margin-right: 10px;
    width: calc((100% - 40px )/5);
    height: calc((100% - 40px )/5);
}
.jinsom-login-page-form .right li:nth-child(5n) {
    margin-right: 0;
}
.jinsom-login-page-form .right li img {
    width: 100%;
    height: 100%;
    cursor: pointer;
    border-radius: 4px;
    object-fit: cover;
}
.jinsom-login-page-form .left input {
    border: none;
    padding: 12px 10px;
    width: 100%;
    box-sizing: border-box;
    border-radius: 4px;
}
.jinsom-login-page-form .btn {
    background-color: #2eb354;
    color: #fff;
    text-align: center;
    padding: 10px 10px;
    border-radius: 2px;
    cursor: pointer;
}
.jinsom-login-page-form .left p {
    margin: 15px 0;
}
.jinsom-login-page-form .action {
    margin-bottom: 15px;
}
.jinsom-login-page-form .action span:last-child {
    float: right;
}
.jinsom-login-page-form .action span:hover {
    text-decoration: underline;
}
.jinsom-login-page-form .action span {
    color: #aaa;
    font-size: 13px;
    cursor: pointer;
}
.jinsom-login-page-form .social {
    margin-top: 15px;
    text-align: center;
}
.jinsom-login-page-form .social i {
    font-size: 32px;
}
.jinsom-login-page-form .social a {
    margin-right: 28px;
    transition: all .3s ease;
    display: inline-block;
}
.jinsom-login-page-form .social a:hover {
    transform: translateY(-4px);
}
.jinsom-login-page-form .social a:last-child {
    margin-right: 0;
}
.jinsom-login-page-form .social .phone {
    color: #107cd2;
}
.jinsom-login-page-form .social .qq {
    color: #4dafea;
}
.jinsom-login-page-form .social .weibo {
    color: #e6162d;
}
.jinsom-login-page-form .social .wechat {
    color: #3eb135;
}
.jinsom-login-page-form .left h1 {
    font-size: 25px;
}
<?php echo $jinsom_custom_css;?>
</style>
<link rel="stylesheet" type="text/css" href="<?php echo $jinsom_custom_css_file;?>">
<script type="text/javascript" src="<?php echo $jinsom_custom_js_file_header;?>"></script>
<div class="jinsom-login-page">
<div class="jinsom-login-page-form clear">
<div class="left">
<h1><?php echo $site_name;?></h1>
<p><input type="text" placeholder="<?php echo jinsom_get_option('jinsom_login_placeholder');?>" id="jinsom-page-username"></p>
<p><input type="password" placeholder="<?php _e('请输入密码','jinsom');?>" id="jinsom-page-password"></p>
<div class="action">
<span onclick="jinsom_login_form('注册帐号','reg-style',400)"><?php _e('立即注册','jinsom');?></span>
<span onclick="jinsom_get_password_one_form()"><?php _e('忘记密码','jinsom');?></span>
</div>
<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("login",jinsom_get_option('jinsom_machine_verify_use_for'))){?>
<div class="btn opacity" id="login-3"><?php _e('马上登录','jinsom');?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('login-3'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret===0){jinsom_page_login(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<div class="btn opacity" onclick="jinsom_page_login('','')"><?php _e('马上登录','jinsom');?></div>
<?php }?>   
<div class="social">
<?php 
if(jinsom_is_login_type('phone')){
echo '<a href=\'javascript:jinsom_login_form("手机号登录","login-phone",350)\' class="phone" rel="nofollow"><i class="jinsom-icon jinsom-shoujihao"></i></a>';
}
if(jinsom_is_login_type('qq')){
echo '<a href="'.jinsom_oauth_url('qq').'" class="qq" rel="nofollow"><i class="jinsom-icon jinsom-qq"></i></a>';
}
if(jinsom_is_login_type('weibo')){
echo '<a href="'.jinsom_oauth_url('weibo').'" class="weibo" rel="nofollow"><i class="jinsom-icon jinsom-weibo"></i></a>';	
}
if(jinsom_is_login_type('wechat_code')){
echo '<a href="'.jinsom_oauth_url('wechat_code').'" class="wechat" rel="nofollow"><i class="jinsom-icon jinsom-weixin"></i></a>';	
}
if(jinsom_is_login_type('github')){
echo '<a href="'.jinsom_oauth_url('github').'" class="github" rel="nofollow"><i class="jinsom-icon jinsom-icon jinsom-huaban88"></i></a>';    
}
?>
</div>
</div>
<div class="right">
<?php 
if (!empty($user_query->results)){
foreach ($user_query->results as $user){
echo '
<li title="'.$user->nickname.'">'.jinsom_vip_icon($user->ID).jinsom_avatar($user->ID,'60', avatar_type($user->ID) ).'</p></li>';
}}
?>	
</div>
</div>	
</div>
<script type="text/javascript">
layui.use(['layer'], function(){
var layer = layui.layer;
});

function jinsom_page_login(ticket,randstr){
username=$("#jinsom-page-username").val();
password=$("#jinsom-page-password").val();
layer.load(1);
$.ajax({
type: "POST",
dataType:'json',
url:  jinsom.jinsom_ajax_url+"/action/login.php",
data: {username:username,password:password,ticket:ticket,randstr:randstr},
success: function(msg){
layer.closeAll('loading');
layer.msg(msg.msg);
if(msg.code==1){
function d(){window.location.reload();}setTimeout(d,2000);
}
}
});
}


</script>

<?php }?>