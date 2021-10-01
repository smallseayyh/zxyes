<?php 
//音乐列表

$music_url=get_post_meta($post_id,'music_url',true);//音乐数据
$power_download=get_post_meta($post_id,'power_download',true);//是否允许下载
$content_number=mb_strlen(strip_tags($content),'utf-8');//内容

update_post_meta($post_id,'post_views',($post_views+1));//更新内容浏览量

if(($sticky_post||$commend_post)&&!$title){//如果不存在标题 才显示时光轴的知道和推荐图标
if($sticky_post&&$commend_post){
echo '<div class="jinsom-post-top-time"><span class="top"></span><span class="commend"></span></div>';
}else if($sticky_post) {
echo '<div class="jinsom-post-top-time"><span class="top"></span></div>';
}else{
echo '<div class="jinsom-post-top-time"><span class="commend"></span></div>';	
}
}

require($require_url.'/post/info.php' );//引入头部信息

if($title){//标题
$commend_html='';
if($sticky_post){$commend_html.='<span class="jinsom-mark jinsom-top"></span>';}
if($commend_post){$commend_html.='<span class="jinsom-mark jinsom-commend-icon"></span>';}
if(!is_single()&&!is_page()){
echo '<h2><a href="'.$permalink.'" target="_blank">'.$title.'</a>'.$commend_html.'</h2>';
}else{
echo '<h1>'.$title.$commend_html.'</h1>';
}  
}
?>


<div class="jinsom-post-content <?php if($content_number>$fold_number&&!is_single()&&!is_page()){ echo 'hidden';} ?>">
<?php 
if(is_single()){
jinsom_title_bottom_hook();//标题下方的钩子
}
?>
<div class="jinsom-post-music">
<?php 
if(!$power_download){
if($post_power==1){//付费 
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
echo '<div class="jinsom-music-no-power-'.$post_id.' jinsom-music-no-power" onclick="jinsom_show_pay_form('.$post_id.')"></div>';	
}
}else if($post_power==2){//密码
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人
echo '<div class="jinsom-music-no-power-'.$post_id.' jinsom-music-no-power" onclick=\'jinsom_music_password_form('.$post_id.');\'></div>';	
}
}else if($post_power==4){//VIP
if(!is_vip($user_id)&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、VIP用户
echo '<div class="jinsom-music-no-power-'.$post_id.' jinsom-music-no-power" onclick=\'jinsom_recharge_vip_form()\'></div>';	
}
}else if($post_power==5){//登录
if(!is_user_logged_in()){//没有登录的用户
echo '<div class="jinsom-music-no-power-'.$post_id.' jinsom-music-no-power" onclick=\'jinsom_pop_login_style();\'></div>';	
}
}
}
?>
<div id="jinsom_music_player_<?php echo $post_id;?>" class="aplayer"></div>
<?php 
if($power_download){
if($post_power==1){//付费 
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
echo '<a class="jinsom-post-music-download opacity" onclick="jinsom_show_pay_form('.$post_id.')">下载</a>';	
}else{
echo '<a class="jinsom-post-music-download opacity" href="'.$music_url.'" download target="_blank">下载</a>';		
}
}else if($post_power==2){//密码
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人
echo '<a class="jinsom-post-music-download opacity" onclick=\'jinsom_music_password_form('.$post_id.');\'>下载</a>';	
}else{
echo '<a class="jinsom-post-music-download opacity" href="'.$music_url.'" download target="_blank">下载</a>';		
}
}else if($post_power==4){//VIP
if(!is_vip($user_id)&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、VIP用户
echo '<a class="jinsom-post-music-download opacity" onclick=\'jinsom_recharge_vip_form()\'>下载</a>';	
}else{
echo '<a class="jinsom-post-music-download opacity" href="'.$music_url.'" download target="_blank">下载</a>';		
}
}else if($post_power==5){//登录
if(!is_user_logged_in()){//没有登录的用户
echo '<a class="jinsom-post-music-download opacity" onclick=\'jinsom_pop_login_style();\'>下载</a>';	
}else{
echo '<a class="jinsom-post-music-download opacity" href="'.$music_url.'" download target="_blank">下载</a>';		
}
}else{
echo '<a class="jinsom-post-music-download opacity" href="'.$music_url.'" download target="_blank">下载</a>';	
}
}
?>


</div>

<script type="text/javascript" id="jinsom-music-script">
var jinsom_music_player_<?php echo $post_id;?> = new APlayer({
element: document.getElementById('jinsom_music_player_<?php echo $post_id;?>'),
narrow: false,
autoplay: false,
mutex: true,
showlrc: false,
preload: 'none',
music: {url: '<?php echo $music_url;?>'}});
if($('.jinsom-music-no-power-<?php echo $post_id;?>').length>0){
$('#jinsom_music_player_<?php echo $post_id;?>').html('<div class="aplayer-pic"><div class="aplayer-button aplayer-play"><button type="button" class="aplayer-icon aplayer-icon-play"><svg xmlns:xlink="http://www.w3.org/1999/xlink" height="100%" version="1.1" viewBox="0 0 16 31" width="100%"><use xlink:href="#aplayer-play"></use><path class="aplayer-fill" d="M15.552 15.168q0.448 0.32 0.448 0.832 0 0.448-0.448 0.768l-13.696 8.512q-0.768 0.512-1.312 0.192t-0.544-1.28v-16.448q0-0.96 0.544-1.28t1.312 0.192z" id="aplayer-play"></path></svg></button></div></div><div class="aplayer-info"><div class="aplayer-controller"><div class="aplayer-bar-wrap"><div class="aplayer-bar"></div></div><div class="aplayer-time"><span class="aplayer-time-inner">- <span class="aplayer-ptime">00:00</span> / <span class="aplayer-dtime">00:00</span></span></div></div></div>');
}
$('#jinsom-music-script').remove();
</script>
<?php        
//内容
if(!is_single()&&!is_page()){
echo'<a class="post_list_link" href="'.$permalink.'" target="_blank">'.do_shortcode(convert_smilies(wpautop(jinsom_autolink($content)))).'</a>';
}else{
echo do_shortcode(convert_smilies(wpautop(jinsom_autolink($content))));
}
?>

</div>



<?php 
//阅读更多
if($content_number>$fold_number&&!is_single()&&!is_page()){
echo"<div class='jinsom-post-read-more'>查看全文</div>";
}

require($require_url.'/post/topic-list.php');//话题列表

if(is_single()){
jinsom_single_content_end_hook();//自定义内容结束钩子
}


require($require_url.'/post/bar.php' );//内容底部栏
jinsom_post_like_list($post_id);//喜欢列表
