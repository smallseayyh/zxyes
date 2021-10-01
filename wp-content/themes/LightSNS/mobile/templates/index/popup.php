<?php 
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
$jinsom_machine_verify_appid=jinsom_get_option('jinsom_machine_verify_appid');

$theme_url=get_template_directory_uri();
?>

<!-- 登录页面 -->
<?php if(!is_user_logged_in()){?>
<div class="login-screen">
<div class="view">
<div class="page">

<div class="navbar jinsom-login-navbar">
<div class="navbar-inner">
<div class="left">
<?php 
$login_on_off=jinsom_get_option('jinsom_login_on_off');
if(!$login_on_off){
echo '<a href="#" class="link icon-only close-login-screen"><i class="jinsom-icon jinsom-xiangxia2"></i></a>';	
}else{
echo '<a href="#" class="link icon-only"></a>';	
}
?>
</div>
<div class="center"></div>
<div class="right">
<a href="javascript:jinsom_reg_type_form()"  class="link icon-only"><?php _e('注册','jinsom');?></a>
</div>
</div>
</div>

<div class="page-content login-screen-content jinsom-login-form">


<div class="jinsom-login-input-form">
<div class="jinsom-mobile-input">
<p class="name"><input type="text" id="jinsom-pop-username" placeholder="<?php echo jinsom_get_option('jinsom_login_placeholder');?>"></p>
<p class="pass"><input type="password" id="jinsom-pop-password" placeholder="<?php _e('请输入密码','jinsom');?>"></p>
</div>

<div class="jinsom-mobile-login-form-btn">
<?php if($jinsom_machine_verify_on_off&&in_array("login",$jinsom_machine_verify_use_for)){?>
<div class="jinsom-login-btn" id="login-1"><?php _e('立即登录','jinsom');?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('login-1'),'<?php echo $jinsom_machine_verify_appid;?>',function(res){
if(res.ret === 0){jinsom_login(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<div class="jinsom-login-btn" onclick="jinsom_login('','')"><?php _e('立即登录','jinsom');?></div>
<?php }?>
</div>



<?php 
$jinsom_login_add=jinsom_get_option('jinsom_login_add');
echo '<div class="jinsom-social-login clear">';
if($jinsom_login_add){
foreach($jinsom_login_add as $data){
if(!$data['in_pc']){
$type=$data['jinsom_login_add_type'];
$icon=$data['icon'];
$color=$data['color'];
if($type=='phone'){
$login_url='javascript:jinsom_load_login_page(\'login-phone\')';
$default_icon='<i class="jinsom-icon jinsom-shoujihao"></i>';	
}else if($type=='qq'){
$login_url=jinsom_oauth_url('qq');
$default_icon='<i class="jinsom-icon jinsom-qq"></i>';	
}else if($type=='weibo'){
$login_url=jinsom_oauth_url('weibo');
$default_icon='<i class="jinsom-icon jinsom-weibo"></i>';	
}else if($type=='wechat_mp'){//公众号登录
$login_url=jinsom_oauth_url('wechat_mp');
$default_icon='<i class="jinsom-icon jinsom-weixin"></i>';	
}else if($type=='github'){
$login_url=jinsom_oauth_url('github');
$default_icon='<i class="jinsom-icon jinsom-huaban88"></i>';	
}else if($type=='alipay'){
$login_url=jinsom_oauth_url('alipay');
$default_icon='<i class="jinsom-icon jinsom-payIcon-aliPay"></i>';	
}else if($type=='password'){
$login_url='javascript:jinsom_load_login_page(\'forget-password\')';
$default_icon='<i class="jinsom-icon jinsom-wenhao"></i>';	
}else if($type=='custom'){
$login_url=do_shortcode($data['custom']);
$default_icon='<i class="jinsom-icon jinsom-qixin-qunzu"></i>';	
}
if(!$icon){$icon=$default_icon;}

if($type!='username'&&$type!='wechat_code'&&$type!='wechat_follow'){

if($type=='qq'||$type=='weibo'||$type=='wechat_mp'||$type=='github'||$type=='alipay'){
$onclick='onclick="jinsom_login_back_url()"';
}else{
$onclick='';
}

echo '<li class="'.$type.' opacity"><a href="'.$login_url.'" '.$onclick.'><span style="color:'.$color.'">'.$icon.'</span><p>'.$data['name'].'</p></a></li>';
}  

}
}
}else{
echo jinsom_empty('请在后台-登录注册-基本设置-添加登录选项');
}

echo '</div>';
?>








</div>

</div>
</div>
</div>
</div>







<?php 
$jinsom_reg_add=jinsom_get_option('jinsom_reg_add');
if($jinsom_reg_add){

echo '<div class="jinsom-reg-type-form" style="display:none;">';

foreach($jinsom_reg_add as $data){
if(!$data['in_pc']){
$type=$data['jinsom_reg_add_type'];
$icon=$data['icon'];
$color=$data['color'];
if($type=='simple'){//简单注册
$login_url='javascript:jinsom_load_login_page(\'reg-simple\')';
$default_icon='<i class="jinsom-icon jinsom-qixin-qunzu"></i>';
}else if($type=='phone'){
$login_url='javascript:jinsom_load_login_page(\'reg-phone\')';
$default_icon='<i class="jinsom-icon jinsom-shoujihao"></i>';	
}else if($type=='email'){
$login_url='javascript:jinsom_load_login_page(\'reg-email\')';
$default_icon='<i class="jinsom-icon jinsom-youxiang"></i>';	
}else if($type=='invite'){
$login_url='javascript:jinsom_load_login_page(\'reg-invite\')';
$default_icon='<i class="jinsom-icon jinsom-yaoqing"></i>';	
}else if($type=='qq'){
$login_url=jinsom_oauth_url('qq');
$default_icon='<i class="jinsom-icon jinsom-qq"></i>';	
}else if($type=='weibo'){
$login_url=jinsom_oauth_url('weibo');
$default_icon='<i class="jinsom-icon jinsom-weibo"></i>';	
}else if($type=='wechat_mp'){//公众号注册
$login_url=jinsom_oauth_url('wechat_mp');
$default_icon='<i class="jinsom-icon jinsom-weixin"></i>';	
}else if($type=='github'){
$login_url=jinsom_oauth_url('github');
$default_icon='<i class="jinsom-icon jinsom-huaban88"></i>';	
}else if($type=='alipay'){
$login_url=jinsom_oauth_url('alipay');
$default_icon='<i class="jinsom-icon jinsom-payIcon-aliPay"></i>';	
}else if($type=='password'){
$login_url='javascript:jinsom_load_login_page(\'forget-password\')';
$default_icon='<i class="jinsom-icon jinsom-wenhao"></i>';	
}else if($type=='custom'){
$login_url=do_shortcode($data['custom']);
$default_icon='<i class="jinsom-icon jinsom-qixin-qunzu"></i>';	
}
if(!$icon){$icon=$default_icon;}
if($type!='wechat_code'&&$type!='wechat_follow'){

if($type=='qq'||$type=='weibo'||$type=='wechat_mp'||$type=='github'||$type=='alipay'){
$onclick='onclick="jinsom_login_back_url()"';
}else{
$onclick='';
}

echo '<li class="'.$type.' opacity"><a href="'.$login_url.'" '.$onclick.'><span style="color:'.$color.'">'.$icon.'</span><p>'.$data['name'].'</p></a></li>';  
}
}
}

echo '<div class="clear"></div><div class="cancel" onclick="layer.closeAll()">取消操作</div></div>';
}


?>
<?php }?>


<!-- 音乐播放器 -->
<div class="popup jinsom-music-player">
<div class="close-popup jinsom-music-player-close"><i class="jinsom-icon jinsom-xiangxia2"></i></div>
<div class="jinsom-player-content">
<div class="jinsom-player-record">
<div class="record-bg">
<div class="record-pic"><no-img><i class="jinsom-icon jinsom-yinle"></i></no-img></div>
</div>
</div>
<div class="jinsom-player-lyrics"><?php _e('暂无内容','jinsom');?></div>	
</div>
<div class="jinsom-player-progress">
<div class="progress-bar">
<div class="progress"></div>
<div class="progress-btn"></div>
</div>
</div>
<div class="jinsom-player-footer-btn">
<div class="like">
<?php if(is_user_logged_in()){?>
<i class="jinsom-icon jinsom-xihuan2" onclick="jinsom_like_music(this)"></i>
<?php }else{?>
<i class="jinsom-icon jinsom-xihuan2" onclick="myApp.closeModal('.jinsom-music-player');myApp.loginScreen();"></i>
<?php }?>	

</div>
<div class="play"><i class="jinsom-icon jinsom-bofang-"></i></div>
<div class="comment"><i class="jinsom-icon jinsom-pinglun2"></i><m>0</m></div>
</div>	
<div class="jinsom-player-bg"></div>
<audio id="jinsom-music-player"></audio>
</div>



<!-- 发布类型 -->
<?php 
if(is_user_logged_in()){
$jinsom_publish_add=jinsom_get_option('jinsom_publish_add');

?>
<div class="popup jinsom-publish-type-form">
<div class="page-content">
<div class="ad">
<?php echo do_shortcode($publish_header_html);?>
</div>

<div class="bottom">

<?php 
if($jinsom_publish_add){
foreach($jinsom_publish_add as $data) {
$publish_type=$data['jinsom_publish_add_type'];
if($publish_type=='words'){
$default_icon='<i class="jinsom-icon jinsom-shangjiadongtai"></i>';
}else if($publish_type=='music'){
$default_icon='<i class="jinsom-icon jinsom-yinle1"></i>';	
}else if($publish_type=='single'){
$default_icon='<i class="jinsom-icon jinsom-wenzhang44"></i>';	
}else if($publish_type=='video'){
$default_icon='<i class="jinsom-icon jinsom-shipin"></i>';	
}else if($publish_type=='redbag'){
$default_icon='<i class="jinsom-icon jinsom-hongbao2"></i>';	
}else{
$default_icon='<i class="jinsom-icon jinsom-neirong"></i>';	
}
if($data['icon']){$icon=$data['icon'];}else{$icon=$default_icon;}

if($publish_type=='bbs'){
$bbs_id=$data['bbs_id'];
}else{
$bbs_id=0;
}

if(!$data['in_pc']){
if($publish_type!='custom'){
echo '<li class="'.$publish_type.' opacity" onclick=\'jinsom_publish_power("'.$publish_type.'",'.$bbs_id.',"")\'><span style="background-color:'.$data['color'].';">'.$icon.'</span><p>'.$data['name'].'</p></li>';
}else{
echo '<li class="'.$publish_type.' opacity"><a href="'.do_shortcode($data['custom']).'" class="link"><span style="background-color:'.$data['color'].';">'.$icon.'</span><p>'.$data['name'].'</p></a></li>';
}
}//只在电脑端展示
}
}else{
echo '请在后台-主题配置-内容模块-基本设置-发布选项添加-设置发布选项';
}
?>
</div>

<div class="close">
<a href="#" class="link icon-only close-popup"><i class="jinsom-icon jinsom-xiangxia2"></i></a>
</div>

</div>
</div>
<?php }?>