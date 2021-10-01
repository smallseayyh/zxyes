<?php 
//完善信息
require( '../../wp-load.php');

if(is_user_logged_in()){
wp_redirect(home_url());
exit();
}

$reg_type=strip_tags($_COOKIE['reg_type']);
// $unionid=strip_tags($_COOKIE['unionid']);
$openid=strip_tags($_COOKIE['openid']);
$nickname=strip_tags($_COOKIE['nickname']);
$avatar=strip_tags($_COOKIE['avatar']);
if(!$reg_type||!$openid||!$avatar||!$nickname){
wp_die('请求失败！','提示');
}


if($reg_type=='qq'){
$reg='QQ';
}else if($reg_type=='weibo'){
$reg='微博';	
}else if($reg_type=='github'){
$reg='Github';	
}else if($reg_type=='alipay'){
$reg='支付宝';	
}else if($reg_type=='wechat_code'||$reg_type=='wechat_follow'||$reg_type=='wechat_mp'){
$reg='微信';	
}

?>
<!DOCTYPE html>
<html>
<head>
<title>完善注册信息-<?php echo jinsom_get_option('jinsom_site_name');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/jinsom/LightSNS-CDN@1.6.71.05/assets/js/jquery.min.js"></script>
</head>
<body>
<style type="text/css">
body,html,ul{
    margin:0;
    padding:0;
}
body {
    background-color: #eee;
}
li {
    list-style: none;
}
.opacity:hover{
    opacity: 0.8;
}
.jinsom-reg-select-content {
    width: 600px;
    margin: auto;
    padding: 20px;
    background-color: #fff;
    margin-top: 20px;
}
.jinsom-reg-select-content .header {
    display: flex;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f1f1;
    padding-bottom: 20px;
}
.jinsom-reg-select-content .header li.on, .jinsom-reg-select-content .header li:hover {
    color: #2196F3;
    font-weight: bold;
}
.jinsom-reg-select-content .header li {
    margin-right: 20px;
    cursor: pointer;
}
.jinsom-reg-select-content .content {
    padding: 20px 0;
}
.jinsom-reg-select-info {
    text-align: center;
}
.jinsom-reg-select-info img {
    width: 80px;
    height: 80px;
    border-radius: 4px;
    border: 1px solid #f1f1f1;
    padding: 4px;
}
.jinsom-reg-select-info .name {
    margin: 10px 10px 20px;
    color: #555;
}
.jinsom-reg-select-info .btn {
    width: 90px;
    margin: auto;
    text-align: center;
    background-color: #2196F3;
    color: #fff;
    padding: 8px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
}
.jinsom-reg-select-content .content ul {
    display: none;
}
.jinsom-reg-select-content .content ul:first-child {
    display: block;
}
.jinsom-reg-select-content .tips {
    font-size: 13px;
    margin: 20px 0;
    padding: 8px;
    background-color: #d9f3da;
    color: #559b58;
    border-radius: 4px;
    text-align: center;
}
.jinsom-reg-select-form li {
    margin-bottom: 15px;
}
.jinsom-reg-select-form input {
    padding: 10px;
    width: 240px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
.jinsom-reg-select-form .btn {
    width: 240px;
    padding: 8px;
    text-align: center;
    background-color: #2196F3;
    color: #fff;
    box-sizing: border-box;
    border-radius: 4px;
    cursor: pointer;
    margin: auto;
}
.jinsom-reg-select-form {
    text-align: center;
}
.jinsom-reg-select-content .header .right {
    flex: 1;
    text-align: right;
}
.jinsom-reg-select-content .header .right a {
    text-decoration: none;
    color: #999;
    font-size: 13px;
}

<?php if(wp_is_mobile()){?>
body {
    background-color: #fff;
}
.jinsom-reg-select-content {
    width: 100%;
    box-sizing: border-box;
    margin-top: 0;
}
<?php }?>
</style>
<div class="jinsom-reg-select-content">
<div class="header">
<li class="on">创建新帐号</li>
<li>绑定本站帐号</li>
<div class="right"><a href="<?php echo home_url();?>">已有账号？现在登录</a></div>
</div>
<div class="tips">正在使用<?php echo $reg;?>登录本站</div>
<div class="content">
<ul>
<div class="jinsom-reg-select-info">
<div class="avatarimg"><img src="<?php echo $avatar;?>"></div>
<div class="name"><?php echo $nickname;?></div>
<div class="btn opacity">立即创建</div>
</div>
</ul>	
<ul>
<div class="jinsom-reg-select-form">
<li><input type="text" id="jinsom-reg-username" placeholder="手机号/邮箱/用户名"></li>
<li><input type="password" id="jinsom-reg-password" placeholder="密码"></li>
<div class="btn opacity">绑定帐号</div>
</div>
</ul>
</div>

</div>

<script type="text/javascript">
$('.jinsom-reg-select-content .header li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
$('.jinsom-reg-select-content .content ul').eq($(this).index()).show().siblings().hide();
});

//新创建
$('.jinsom-reg-select-info .btn').click(function(){
$(this).unbind().text('创建中...');
$.ajax({
type: "POST",
url: "/Extend/oauth/login.php",
data: {type:'add'},
success: function(msg){
if(msg.code==0){
$('.jinsom-reg-select-content .tips').css({'background-color':'#fbc9c5','color':'#F44336'}).text(msg.msg);
$('.jinsom-reg-select-info .btn').css('background-color','#ccc').text('创建失败');
}else if(msg.code==1){
window.open('/','_self');
}

}
});
});


//绑定
$('.jinsom-reg-select-form .btn').click(function(){
$(this).text('绑定中...');
username=$('#jinsom-reg-username').val();
password=$('#jinsom-reg-password').val();
$.ajax({
type: "POST",
url: "/Extend/oauth/login.php",
data: {type:'bind',username:username,password:password},
success: function(msg){
if(msg.code==0){
$('.jinsom-reg-select-form .btn').text('绑定帐号');
$('.jinsom-reg-select-content .tips').css({'background-color':'#fbc9c5','color':'#F44336'}).text(msg.msg);
// $('.jinsom-reg-select-info .btn').css('background-color','#ccc').text('创建失败');
}else if(msg.code==1){
$('.jinsom-reg-select-form .btn').unbind();
window.open('/','_self');
}

}
});	
});
</script>

</body>
</html>