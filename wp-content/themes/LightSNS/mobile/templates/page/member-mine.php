<?php 
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$jinsom_user_default_desc_a = jinsom_get_option('jinsom_user_default_desc_a');
$jinsom_user_default_desc_b = jinsom_get_option('jinsom_user_default_desc_b');
$author_id=$_GET['author_id'];
$user_id=$current_user->ID;

$user_info = get_userdata($author_id);
$description =$user_info->description;
$myeself = $user_id==$author_id || current_user_can('edit_users') ? 1 : 0;
$verify_add=jinsom_get_option('jinsom_verify_add');

$bg=jinsom_member_bg($author_id,'mobile_img');
if($bg){
$bg='style="background-image:url('.$bg.');"';
}else{
$bg='';
}
?>
<div data-page="member-mine" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('我的主页','jinsom');?></div>
<div class="right">
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting.php?author_id=<?php echo $user_id;?>" class="link icon-only"><i class="jinsom-icon jinsom-gengduo2" style="font-size: 8vw;"></i></a>
</div>
</div>
</div>

<div class="page-content infinite-scroll" data-distance="800" id="jinsom-member-mine-page" <?php echo $bg;?>>

<div class="jinsom-member-header">
<div class="avatarimg avatarimg-<?php echo $author_id;?>"><?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?><?php echo jinsom_verify($author_id);?></div>
<div class="name">
<?php echo jinsom_nickname($author_id);?>
<?php echo jinsom_sex($author_id);?>
<?php echo jinsom_lv($author_id);?>
<?php echo jinsom_vip($author_id);?>
<?php echo jinsom_honor($author_id);?>
</div>
<div class="desc">
<?php 
if($user_info->verify==0){
echo __('个人说明','jinsom').'：';
if($user_id==$author_id){
echo $description?$description:$jinsom_user_default_desc_a;
}else{
echo $description?$description:$jinsom_user_default_desc_b;
}
}else{
echo jinsom_verify_type($author_id).'：'.$user_info->verify_info; 
}?>
</div>
<div class="info">
<div class="item">
<a class="link" href="<?php echo $theme_url;?>/mobile/templates/page/follower.php">
<p><?php echo jinsom_follower_count($author_id); ?></p>
<p><?php _e('粉丝','jinsom');?></p>
</a>
</div>
<div class="item">
<a class="link" href="<?php echo $theme_url;?>/mobile/templates/page/follower.php?type=following">
<p><?php echo jinsom_following_count($author_id);?></p>
<p><?php _e('关注','jinsom');?></p>
</a>
</div>
<div class="item">
<p><?php echo (int)get_user_meta($author_id,'visitor',true);?></p>
<p><?php _e('人气','jinsom');?></p>
</div>
<div class="item">
<p><?php echo (int)get_user_meta($author_id,'charm',true);?></p>
<p><?php _e('魅力','jinsom');?></p>
</div>

</div>
</div>
<div class="jinsom-member-content">

<?php 
//用户主页菜单
$jinsom_member_menu_add=jinsom_get_option('jinsom_member_menu_add');
if($jinsom_member_menu_add){
$type=$jinsom_member_menu_add[0]['jinsom_member_menu_type'];
echo '<div class="jinsom-member-menu">';
$i=1;
foreach($jinsom_member_menu_add as $data){
if(!$data['in_pc']){
if($i==1){$on='class="on"';}else{$on='';}
$member_menu_type=$data['jinsom_member_menu_type'];
if($member_menu_type=='custom-bbs'){
echo '<li type="'.$member_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$member_menu_type.'","ajax",this)\' data="'.$data['bbs_id'].'" author_id="'.$author_id.'">'.$data['name'].'</li>';
}else if($member_menu_type=='custom-topic'){
echo '<li type="'.$member_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$member_menu_type.'","ajax",this)\' data="'.$data['topic_id'].'" author_id="'.$author_id.'">'.$data['name'].'</li>';
}else if($member_menu_type=='profile'||$member_menu_type=='follow-page'){//资料页面菜单不在移动端展示
echo '<li style="display:none;"></li>';
}else{
echo '<li type="'.$member_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$member_menu_type.'","ajax",this)\' author_id="'.$author_id.'">'.$data['name'].'</li>'; 
}
$i++;
}else{
echo '<li style="display:none;"></li>';
}
}

echo '</div>';
}else{
$type='all';
}

?>


<div class="jinsom-member-mine-post-list clear" page="2">
<?php 
//非引入文件或自定义html
if($type=='custom-html'||$type=='require'){
if($type=='custom-html'){
echo do_shortcode($jinsom_member_menu_add[0]['custom_html']);
}else{
require(do_shortcode($jinsom_member_menu_add[0]['require']));
}
}else{


//显示主页置顶
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
require(get_template_directory().'/mobile/templates/post/post-list.php' );	
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
require(get_template_directory().'/mobile/templates/post/post-list.php' );
endwhile;
}else{
echo jinsom_empty();
}

}

?>		
</div>

</div>



</div>
</div>   
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>