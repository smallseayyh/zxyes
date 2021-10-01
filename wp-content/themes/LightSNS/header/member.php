<?php 
$require_url=get_template_directory();
$theme_url=get_template_directory_uri();

global $wp_query,$wpdb;
$curauth=$wp_query->get_queried_object();
$current_user=wp_get_current_user();
$author_id=$curauth->ID;
$user_id=$current_user->ID;
$myeself=$user_id==$author_id||jinsom_is_admin($user_id)? 1 : 0;
$user_info=get_userdata($author_id);
$description=$user_info->description;


$skin_pic=jinsom_member_bg($author_id,'big_img');
$verify_add=jinsom_get_option('jinsom_verify_add');

$user_honor=$user_info->user_honor;
?>
<div class="jinsom-member-main" data="<?php echo $author_id;?>">
<?php if($myeself){?>
<div class="jinsom-member-change-bg"></div>
<?php }?>
<div class="jinsom-member-bg" style="background-image: url(<?php echo $skin_pic;?>);">
<div class="jinsom-member-content">

<div class="jinsom-member-header">
<?php if($myeself){?>
<div class="jinsom-member-avatar myself">
<p><?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?></p>
<?php echo jinsom_verify($author_id);?>
<span><?php _e('点击修改头像','jinsom');?></span>
<form id="jinsom-upload-avatar-form" method="post" enctype="multipart/form-data" action="<?php echo $theme_url; ?>/module/upload/avatar.php">
<input id="jinsom-upload-avatar" type="file" name="file" title="<?php _e('点击修改头像','jinsom');?>">
<input  type="hidden" name="author_id" value="<?php echo $author_id;?>">
</form>
</div>
<?php }else{?>
<div class="jinsom-member-avatar other">
<p><?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?></p>
<?php echo jinsom_verify($author_id);?>
</div>
<?php }?>
<div class="jinsom-member-username">
<h1><?php echo jinsom_nickname($author_id); ?></h1>
<?php echo jinsom_sex($author_id);?>
<?php echo jinsom_lv($author_id);?>
<?php echo jinsom_vip($author_id);?>
<?php echo jinsom_honor($author_id);?>
</div>
<div class="jinsom-member-desc">
<?php 
if($user_info->verify==0){
echo '<span class="desc-tips">'.__('个人说明','jinsom').'：</span>';
if($user_id==$author_id){
$description=$description?$description:jinsom_get_option('jinsom_user_default_desc_a');
echo $description;
}else{
$description=$description?$description:jinsom_get_option('jinsom_user_default_desc_b');
echo $description;
}
}else{
echo '<span class="verify-tips">'.jinsom_verify_type($author_id).'：</span>'.$user_info->verify_info; 
}?>
</div>
<div class="jinsom-member-follow-info">
<?php 
if($user_id!=$author_id){
if (is_user_logged_in()) { 
echo jinsom_follow_button_home($author_id);?>
<span onclick="jinsom_open_user_chat(<?php echo $author_id;?>,this)" class="opacity"><i class="jinsom-icon jinsom-liaotian"></i> <?php _e('聊天','jinsom');?></span>
 <?php }else{?>
<span class="follow no opacity" onclick='jinsom_pop_login_style();'><i class="jinsom-icon jinsom-guanzhu"></i><?php _e('关注','jinsom');?></span>
<span onclick='jinsom_pop_login_style();' class="opacity"><i class="jinsom-icon jinsom-liaotian"></i> <?php _e('聊天','jinsom');?></span>
<?php }?>
<span>
<i class="jinsom-icon jinsom-mulu1"></i>
<div class="jinsom-member-follow-box">
<?php 
if(jinsom_is_blacklist($user_id,$author_id)){
echo '<li onclick=\'jinsom_add_blacklist("remove",'.$author_id.',this)\'>'.__('取消拉黑','jinsom').'</li>';	
}else{
echo '<li onclick=\'jinsom_add_blacklist("add",'.$author_id.',this)\'>'.__('拉黑','jinsom').'</li>';	
}
?>
<li onclick="layer.msg('<?php _e('暂未开启','jinsom');?>');"><?php _e('举报','jinsom');?></li>
</div>
</span>
<?php }?>
</div>
</div>



<?php 
//用户主页菜单
$jinsom_member_menu_add=jinsom_get_option('jinsom_member_menu_add');
if($jinsom_member_menu_add){
$type=$jinsom_member_menu_add[0]['jinsom_member_menu_type'];
echo '<div class="jinsom-member-menu clear">';
$i=1;
foreach($jinsom_member_menu_add as $data){
if(!$data['in_mobile']){
if($i==1){$on='class="on"';}else{$on='';}
$member_menu_type=$data['jinsom_member_menu_type'];
if($member_menu_type=='custom-bbs'){
echo '<li type="'.$member_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$member_menu_type.'","ajax",this)\' data="'.$data['bbs_id'].'" author_id="'.$author_id.'">'.$data['name'].'</li>';
}else if($member_menu_type=='custom-topic'){
echo '<li type="'.$member_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$member_menu_type.'","ajax",this)\' data="'.$data['topic_id'].'" author_id="'.$author_id.'">'.$data['name'].'</li>';
}else if($member_menu_type=='profile'){
if($myeself){
echo '<li type="'.$member_menu_type.'" '.$on.' onclick=\'jinsom_member_setting_page(this)\' author_id="'.$author_id.'">'.$data['name'].'</li>';
}else{
echo '<li style="display:none;"></li>';
}
}else if($member_menu_type=='follow-page'){
echo '<li type="'.$member_menu_type.'" '.$on.' onclick=\'jinsom_member_follow_page(this)\' author_id="'.$author_id.'">'.$data['name'].'</li>';
}else{
if($member_menu_type=='like'){
if(!$user_info->hide_like||$myeself){
echo '<li type="'.$member_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$member_menu_type.'","ajax",this)\' author_id="'.$author_id.'">'.$data['name'].'</li>';	
}
}else if($member_menu_type=='buy'){
if(!$user_info->hide_buy||$myeself){
echo '<li type="'.$member_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$member_menu_type.'","ajax",this)\' author_id="'.$author_id.'">'.$data['name'].'</li>';
}
}else{
echo '<li type="'.$member_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$member_menu_type.'","ajax",this)\' author_id="'.$author_id.'">'.$data['name'].'</li>'; 
}
}
$i++;
}
}

echo '</div>';
}else{
$type='all';
}
?>



<div class="jinsom-member-content-list clear">
<div class="jinsom-member-left">


<div class="box jinsom-member-left-follow clear">
<li onclick="jinsom_member_follow_page(this)" author_id=<?php echo $author_id;?>>
<strong><?php echo jinsom_following_count($author_id);?></strong>
<span><?php _e('关注','jinsom');?></span>
</li>
<li onclick="jinsom_member_follow_page(this)" author_id=<?php echo $author_id;?>>
<strong><?php echo jinsom_follower_count($author_id); ?></strong>
<span><?php _e('粉丝','jinsom');?></span>
</li>
<li>
<strong>
<?php 
$visitor=(int)get_user_meta($author_id,'visitor',true);
$add_visit=(int)get_user_meta($author_id,'add_visit',true);
$add_visit_a=$visitor-$add_visit;
if($add_visit_a>0&&$user_id==$author_id){
if($add_visit_a>=99){
echo '<i>+99</i>';	
}else{
echo '<i>+'.$add_visit_a.'</i>';
}
}
echo $visitor;
?></strong>
<span><?php _e('人气','jinsom');?></span>
</li>
<li>
<strong><?php echo (int)get_user_meta($author_id,'charm',true); ?></strong>
<span><?php _e('魅力','jinsom');?></span>
</li>
</div>

<?php if($user_honor){?>
<div class="box jinsom-member-left-honor">
<h3><?php _e('头衔','jinsom');?></h3>
<div class="content clear">
<?php 
$user_honor_arr=explode(",",$user_honor);
foreach ($user_honor_arr as $data) {
echo '<li class="jinsom-honor-'.strip_tags($data).'">'.$data.'</li>';
}
?>
</div>
</div>
<?php }?>


<?php if(jinsom_get_option('jinsom_gift_on_off')&&jinsom_get_option('jinsom_gift_add')){?>
<div class="box jinsom-member-left-gift">
<h3><?php _e('收到的礼物','jinsom');?></h3>
<div class="content clear">
<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'jin_gift';
$my_gift_data = $wpdb->get_results("SELECT *,count(`name`) as count FROM $table_name WHERE `receive_user_id` ='$author_id' group by `name` order by count(*) desc limit 18;");
if($my_gift_data){
foreach ($my_gift_data as $data) {
echo '
<li>
<div class="top">
<div class="icon"><img src="'.$data->img.'"></div>
<div class="name">'.$data->name.'</div>
</div>
<div class="bottom">X '.$data->count.'</div>
</li>';
}
}else{
echo jinsom_empty(__('还没有收到任何礼物','jinsom'));
}
?>
</div>
<?php if($user_id!=$author_id){?>
<div class="jinsom-member-send-gift-btn opacity" onclick="jinsom_send_gift_form(<?php echo $author_id;?>,0)"><?php _e('赠送礼物','jinsom');?></div>
<?php }?>
</div>
<?php }?>

<?php if($user_info->bg_music_url!=''){?>
<div class="box jinsom-member-left-bg-music clear">
<h3><?php _e('背景音乐','jinsom');?></h3>
<div id="jinsom-memeber-bg-music" class="aplayer"></div>
<script type="text/javascript">
var jinsom_memeber_bg_music = new APlayer({
element: document.getElementById('jinsom-memeber-bg-music'),
narrow: false,
<?php if($user_info->bg_music_on_off){?>
autoplay: true,
<?php }else{?>
autoplay: false,
<?php }?>
mutex: true,
showlrc: false,
preload: 'none',
music: {url: '<?php echo $user_info->bg_music_url;?>'}});
</script>
</div>
<?php }?>


<div class="box jinsom-member-left-profile">
<h3><?php _e('资料简介','jinsom');?></h3>
<li class="id">
I D：<span><?php echo $author_id;?></span>
<?php if(jinsom_is_black($author_id)){echo '<m class="black-user">黑名单</m>';}?>
<?php if($user_info->user_power==4){echo '<m class="danger-user">风险用户</m>';}?>
</li>
<?php if(jinsom_is_admin($user_id)){?>
<li class="username"><?php _e('账号','jinsom');?>：<span><?php echo $user_info->user_login; ?><span></li>
<?php }?>
<li class="nickname"><?php _e('昵称','jinsom');?>：<span><?php echo $user_info->nickname; ?><span></li>
<li class="gender"><?php _e('性别','jinsom');?>：
<span>
<?php 
$gender=$user_info->gender;
if($gender!='男生'&&$gender!='女生'){
$gender=__('保密','jinsom');	
}
echo $gender;?>
<span></li>
<?php if(jinsom_get_option('jinsom_location_on_off')!='no'){?>
<li class="address"><?php _e('位置','jinsom');?>：<span><?php echo $user_info->city;?><span></li>
<?php }?>
<?php if($description){echo '<li>'.__('说明','jinsom').'：<span>'.$description.'<span></li>';}?>

<div class="jinsom-member-left-profile-hide">
<?php 
//自定义资料字段
$jinsom_member_profile_setting_add=jinsom_get_option('jinsom_member_profile_setting_add');
if($jinsom_member_profile_setting_add){
foreach ($jinsom_member_profile_setting_add as $data) {
$power=$data['jinsom_member_profile_setting_power'];
$value=get_user_meta($author_id,$data['value'],true);
if($power=='vip'){
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)&&$user_id!=$author_id){
$value='<font style="color:#f00;">VIP用户才能查看</font>';	
}
}else if($power=='verify'){
if(!get_user_meta($user_id,'verify',true)&&!jinsom_is_admin($user_id)&&$user_id!=$author_id){
$value='<font style="color:#f00;">认证用户才能查看</font>';	
}
}

if($power!='privacy'&&$value){
echo '<li class="'.$data['value'].'">'.$data['name'].'：<span>'.$value.'<span></li>';
}
}
}
?>
<li class="reg"><?php _e('注册','jinsom');?>：<span title="<?php echo $user_info->user_registered;?>"><?php echo jinsom_timeago($user_info->user_registered);?><span></li>
<?php if(jinsom_is_admin($user_id)){?>
<li class="reg_type"><?php _e('注册类型','jinsom');?>：<span><?php echo jinsom_get_reg_type($author_id);?><span></li>
<li><?php _e('最后IP','jinsom');?>：<span><?php echo  $user_info->latest_ip;?><span></li>
<li><?php _e('最后在线','jinsom');?>：<span><?php echo  jinsom_timeago($user_info->latest_login);?> (<?php echo jinsom_get_online_type($user_id);?>)<span></li>
<?php }?>
</div>
<div class="jinsom-member-left-profile-more"><?php _e('查看更多','jinsom');?> <i class="fa fa-angle-right"></i></div>
</div>

<div class="box jinsom-member-left-visitor clear">
<h3><?php _e('最近访客','jinsom');?></h3>
<?php 
$table_name = $wpdb->prefix.'jin_visitor';
$data = $wpdb->get_results("SELECT * FROM $table_name WHERE author_id = $author_id ORDER BY visit_time DESC LIMIT 20;");
if($data){
foreach ($data as $datas){
$visit_user_id=$datas->user_id;
$user_verify=get_user_meta($visit_user_id,'verify',true);
echo '<li><a href="'.jinsom_userlink($visit_user_id).'" title="访问时间：'.jinsom_timeago($datas->visit_time).'">'.jinsom_vip_icon($visit_user_id).jinsom_avatar($visit_user_id,'40',avatar_type($visit_user_id)).'<p>'.jinsom_nickname($visit_user_id).'</p></a>'.jinsom_verify($visit_user_id).'</li>';
}
}else{
echo jinsom_empty();
}
?>
</div>

</div>




<div class="jinsom-member-right">
<div class="jinsom-post-list">
<?php 
//非引入文件或自定义html
if($type=='custom-html'||$type=='require'){
if($type=='custom-html'){
echo do_shortcode($jinsom_member_menu_add[0]['custom_html']);
}else{
require(do_shortcode($jinsom_member_menu_add[0]['require']));
}
}else if($type=='profile'){
echo jinsom_empty('资料设置禁止放在菜单第一位');
}else{


//显示主页置顶
$is_author=1;
$sticky_posts=get_user_meta($author_id,'sticky',true);
if($sticky_posts){
$args_sticky=array(
'post__in'=>array($sticky_posts),
'showposts' =>1,
'ignore_sticky_posts'=>1
);
query_posts($args_sticky);
while(have_posts()):the_post();
$post_power=get_post_meta(get_the_ID(),'post_power',true);
if($post_power!=3){//置顶不显示私密类型
require($require_url.'/post/post-list.php');	
}
endwhile;wp_reset_query();

$sticky_data=array($sticky_posts);//置顶数据
}




require($require_url.'/post/post.php');//内容类型
$args['no_found_rows']=true;
$args['showposts']=get_option('posts_per_page',10);
if($myeself){//判断是管理员或者自己
$args['post_status']=array('publish','private');
}else{
$args['post_status']='publish';	
}
$args['author']=$author_id;	
query_posts( $args );
if(have_posts()){
while (have_posts()):the_post();

require($require_url.'/post/post-list.php');
endwhile;
echo '<div class="jinsom-more-posts" page="2" onclick=\'jinsom_post("'.$type.'","more",this)\' author_id="'.$curauth->ID.'">'.__('加载更多','jinsom').'</div>';
}else{
echo jinsom_empty();
}

}

?>	
 </div>
</div>

</div>


</div>
</div>	
</div>

<?php 
if($myeself){
require($require_url.'/module/stencil/skin.php');
}

// 记录访客和人气
jinsom_add_visitor($user_id,$author_id);
if($user_id==$author_id){
update_user_meta($user_id,'add_visit',$visitor);
}
