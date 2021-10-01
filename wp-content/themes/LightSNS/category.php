<?php
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');
}else{
get_header(); 
$user_id=$current_user->ID;
$bbs_id = get_query_var('cat');//论坛ID
$current_bbs_id=$bbs_id;//当前的论坛ID，不区分是否子论坛
$category=get_category($bbs_id);
$cat_parents=$category->parent;
$theme_url=get_template_directory_uri();

//获取本板块帖子总数
if($cat_parents>0){//子论坛 
$child_cat_id=$bbs_id;//子论坛id
$bbs_id=  $cat_parents;//父级论坛id
$loop_cat_id=$child_cat_id;//当前的论坛ID
}else{
$child_cat_id=0;
$loop_cat_id=$bbs_id;//当前的论坛ID
}


$categories_child = get_categories("child_of=".$bbs_id."&orderby=description&order=ASC&hide_empty=0");//返回子分类的数组

//权限
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
if(is_user_logged_in()){
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}

//访问权限判断
$bbs_visit_power=get_term_meta($bbs_id,'bbs_visit_power',true);
if($bbs_visit_power==1){
if(!is_vip($user_id)&&!$is_bbs_admin){
require(get_template_directory() . '/post/bbs-no-power.php');  
exit();
}  
}else if($bbs_visit_power==2){
$user_verify=get_user_meta($user_id, 'verify', true );
if(!$user_verify&&!$is_bbs_admin){
require(get_template_directory() . '/post/bbs-no-power.php');  
exit();  
}
}else if($bbs_visit_power==3){
if(!$is_bbs_admin){
require(get_template_directory() . '/post/bbs-no-power.php');
exit();  
}
}else if($bbs_visit_power==4){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor==''&&!$is_bbs_admin){
require(get_template_directory() . '/post/bbs-no-power.php'); 
exit();  
}
}else if($bbs_visit_power==5){
//先判断用户是否已经输入密码
if(!jinsom_is_bbs_visit_pass($bbs_id,$user_id)&&!$is_bbs_admin){
require(get_template_directory() . '/post/bbs-no-power.php');
exit();
}
}else if($bbs_visit_power==6){//满足经验的用户
$bbs_visit_exp=(int)get_term_meta($bbs_id,'bbs_visit_exp',true);
$current_user_lv=jinsom_get_user_exp($user_id);//当前用户经验
if($current_user_lv<$bbs_visit_exp&&!$is_bbs_admin){
require(get_template_directory() . '/post/bbs-no-power.php');
exit();
}
}else if($bbs_visit_power==7){//指定用户
$bbs_visit_user=get_term_meta($bbs_id,'bbs_visit_user',true);
$bbs_visit_user_arr=explode(",",$bbs_visit_user);
if(!in_array($user_id,$bbs_visit_user_arr)&&!$is_bbs_admin){
require(get_template_directory() . '/post/bbs-no-power.php');
exit();
}
}else if($bbs_visit_power==8){//登录用户
if(!is_user_logged_in()){
require(get_template_directory() . '/post/bbs-no-power.php');
exit();
}
}else if($bbs_visit_power==9){//指定头衔
if(!$is_bbs_admin){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$bbs_visit_honor=get_term_meta($bbs_id,'bbs_visit_honor',true);
$bbs_visit_honor_arr=explode(",",$bbs_visit_honor);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($bbs_visit_honor_arr,$user_honor_arr)){
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}else{
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}
}else if($bbs_visit_power==10){//指定认证
if(!$is_bbs_admin){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$bbs_visit_verify=get_term_meta($bbs_id,'bbs_visit_verify',true);
$bbs_visit_verify_arr=explode(",",$bbs_visit_verify);
if(!in_array($user_verify_type,$bbs_visit_verify_arr)){
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}else{
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}
}else if($bbs_visit_power==11){//付费访问
if(!$is_bbs_admin){
$bbs_pay_user_list=get_term_meta($bbs_id,'visit_power_pay_user_list',true);
$bbs_pay_user_list_arr=explode(",",$bbs_pay_user_list);
if($bbs_pay_user_list){
if(!in_array($user_id,$bbs_pay_user_list_arr)){
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}else{
require(get_template_directory() . '/post/bbs-no-power.php');	
exit();
}
}
}


//论坛信息
$bbs_bg=get_term_meta($bbs_id,'bbs_bg',true);
$bbs_notice=get_term_meta($bbs_id,'bbs_notice',true);
$bbs_blacklist=get_term_meta($bbs_id,'bbs_blacklist',true);
$showposts=get_term_meta($bbs_id,'bbs_showposts',true);
if(empty($showposts)){$showposts=20;}
$post_target=get_term_meta($bbs_id,'bbs_post_target',true);
$post_child_commend=get_term_meta($bbs_id,'bbs_post_child_commend',true);
$child_show_bbs=get_term_meta($bbs_id,'child_show_bbs',true);//子论坛显示子版块
$group_im=get_term_meta($bbs_id,'bbs_group_im',true);


//外观布局
$bbs_list_style=(int)get_term_meta($bbs_id,'bbs_list_style',true);
$bbs_css=get_term_meta($bbs_id,'bbs_css',true);

$ad_header=get_term_meta($bbs_id,'bbs_ad_header',true);
$ad_footer=get_term_meta($bbs_id,'bbs_ad_footer',true);

//空值
if($bbs_bg){
$bbs_bg='style="background-image:url('.$bbs_bg.');"';
}else{
$bbs_default_bg_pc=jinsom_get_option('jinsom_bbs_default_bg_pc');
if($bbs_default_bg_pc){
$bbs_bg='style="background-image:url('.$bbs_default_bg_pc.');"';
}else{
$bbs_bg='';
}
}


//发表按钮
$publish_text=get_term_meta($bbs_id,'publish_text',true);
if(!$publish_text){$publish_text=__('发表','jinsom');}



//论坛自定义css
echo '<style type="text/css">';


//瀑布流
if($bbs_list_style==3){
$waterfull_margin=jinsom_get_option('jinsom_waterfull_margin');
$full_width=$waterfull_margin*3;
$min_width=$waterfull_margin*2;
echo '
.jinsom-main-content.bbs{padding-top:'.$waterfull_margin.'px;}
.jinsom-bbs-box-header{margin-bottom:'.($waterfull_margin).'px;}
.jinsom-content-left{width:calc(100% - '.(300+$waterfull_margin).'px);}
.jinsom-bbs-list-4 li{width:calc((100% - '.$min_width.'px)/3) !important;margin-bottom: '.$waterfull_margin.'px !important;}
.jinsom-content-left.full .jinsom-bbs-list-4 li {width: calc((100% - '.$full_width.'px)/4) !important;}
';	
}
if($bbs_list_style==2||$bbs_list_style==4){
echo '.jinsom-content-left{width:calc(100% - 315px);}';
}

echo $bbs_css;
echo '</style>';
?>


<div class="jinsom-bbs-<?php echo $bbs_id;?>"><!-- 论坛自定义css模块 -->

<!-- 论坛头部开始 -->	
<div class="jinsom-bbs-header clear" data="<?php if($cat_parents>0){echo $child_cat_id;}else{echo $bbs_id;};?>" child_id=<?php echo $child_cat_id;?>>
<div class="jinsom-bbs-header-bg" <?php echo $bbs_bg;?>>
<?php if(jinsom_is_admin($user_id)||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)){?>
<i class="jinsom-icon jinsom-shezhi" onclick="jinsom_bbs_setting_form(<?php echo $bbs_id;?>);"></i>
<?php }?>
</div>
<div class="jinsom-bbs-header-info">
<div class="jinsom-bbs-header-info-avatar">
<?php 
echo '<a href="'.get_category_link($bbs_id).'">'.jinsom_get_bbs_avatar($bbs_id,0).'</a>';
if($child_cat_id){
echo '<span>'.jinsom_get_bbs_avatar($child_cat_id,0).'</span>';
}
?>
</div>
<div class="jinsom-bbs-header-info-btn clear">
<?php 
echo '<a href="'.get_category_link($bbs_id).'" class="name">';
echo get_category($bbs_id)->name; 
echo '</a> ';
if($child_cat_id){
echo '<child> — '.get_category($child_cat_id)->name.'</child>';
}
echo jinsom_bbs_like_btn($user_id,$bbs_id);//关注
//群组入口
if($group_im){?>
<span class="chat opacity" onclick="jinsom_join_group_chat(<?php echo $bbs_id;?>,this);"><i class="jinsom-icon jinsom-liaotian"></i> <?php _e('群聊','jinsom');?></span>
<?php }?>
<span class="jinsom-bbs-follow-info"><span><?php _e('关注','jinsom');?>：<m class="num"><?php echo jinsom_get_bbs_like_number($bbs_id);?></m></span><span><?php _e('内容','jinsom');?>：<m><?php echo jinsom_get_bbs_post($current_bbs_id);?></m></span></span>
<span class="jinsom-bbs-header-publish-btn opacity"  onclick="jinsom_publish_power('bbs',<?php echo $current_bbs_id;?>,'')"><i class="jinsom-icon jinsom-fabiao1"></i> <?php echo $publish_text;?></span>
</div>

<div class="jinsom-bbs-header-info-desc clear">
<?php echo get_term_meta($current_bbs_id,'desc',true);?>
</div>
</div>
</div><!-- jinsom-bbs-header -->
<!-- 论坛头部结束 -->


<!-- 论坛公告 -->
<?php if($bbs_notice){echo'<div class="jinsom-bbs-notice-bar">'.$bbs_notice.'</div>';}?>

<!-- 主内容 -->
<div class="jinsom-main-content bbs clear">
<?php 
$custom_sidebar=get_term_meta($bbs_id,'layout_sidebar',true);
if($custom_sidebar!='no'){
require(get_template_directory().'/sidebar/sidebar-custom.php');//引入右侧栏
}
?>
<div class="jinsom-content-left <?php if($custom_sidebar=='no'){echo 'full';}?>"><!-- 左侧 -->

<?php 
$commend_all = array(
'meta_key' => 'jinsom_sticky',
'cat'=>$bbs_id,
'showposts' => -1,
'post_parent' => 999999999
);
$the_query = new WP_Query($commend_all);
$jinsom_commend_number = $the_query->post_count;//获取所有置顶帖子的数量
?>

<?php if(($jinsom_commend_number&&(($cat_parents==0||($cat_parents!=0&&$post_child_commend))))||(!empty($categories_child)&&($cat_parents==0||($cat_parents!=0&&$child_show_bbs)))){//如果有置顶文章或者子版块 ?>
<div class="jinsom-bbs-box" style="margin-bottom: 10px;">

	
<!-- 子版块 -->
<?php if(!empty($categories_child)&&($cat_parents==0||($cat_parents!=0&&$child_show_bbs))){//不为空并且(是父级或者开启) ?>
<div class="jinsom-bbs-cat-list clear">
<?php 
foreach ($categories_child as $child) {
$child_setting='';
if(jinsom_is_admin($user_id)||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)){
$child_setting='<div class="setting" onclick="jinsom_bbs_setting_form_child('.$child->cat_ID.')"><i class="jinsom-icon jinsom-shezhi"></i></div>';	
}
echo '
<li class="cat-'.$child->cat_ID.'" id="cat-'.$child->cat_ID.'">
'.$child_setting.'
<div class="left">
<a href="'.get_category_link($child->cat_ID).'" target="_blank">
'.jinsom_get_bbs_avatar($child->cat_ID,0).'
</a>
</div>
<div class="right">
<div class="name"><a href="'.get_category_link($child->cat_ID).'" target="_blank">'.$child->cat_name.'<span>'.jinsom_get_bbs_post($child->cat_ID).'</span></a></div>
<div class="desc">'.get_term_meta($child->cat_ID,'desc',true).'</div>
</div>
</li>';
}
?>

</div>
<?php }?>


<?php  //置顶帖子    有置顶的&&不是那些样式的->父级||子级
if($jinsom_commend_number&&(($cat_parents==0||($cat_parents!=0&&$post_child_commend)))){
$args = array(
'meta_key' => 'jinsom_sticky',
'post_parent'=>999999999,
'no_found_rows'=>true,
'cat'=>$bbs_id,
'ignore_sticky_posts'=>1
);
query_posts( $args );
if (have_posts()){
?>

<div class="jinsom-bbs-post-list commend" <?php if($categories_child){echo 'style="margin-top:15px;"';}?> data-no-instant>
<?php 
while (have_posts()) : the_post();
$post_id=get_the_ID();
$post_views=get_post_meta($post_id,'post_views',true);  
require( get_template_directory() . '/post/bbs-list-1.php' );
endwhile; wp_reset_query();
?>  
</div>


<?php }?>
<?php }?>

</div>
<?php }?>


<!-- 顶部广告 -->
<?php echo do_shortcode($ad_header);?>


<!-- 帖子列表部分 -->

<?php 
//菜单
$enabled_menu=get_term_meta($bbs_id,'enabled_menu',true);
if($enabled_menu!='empty'&&$enabled_menu){
$enabled_menu_arr=explode(",",$enabled_menu);
$type=$enabled_menu_arr[0];
}else{
$type='new';
}

if($type=='comment'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'last_comment_time',
'orderby'   => 'meta_value_num', 
'cat'=>$loop_cat_id,
'post_parent'=>999999999
);
}else if($type=='new'){//按最新发帖排序
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'cat'=>$loop_cat_id,
'post_parent'=>999999999
);
}else if($type=='nice'){//加精的帖子
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'jinsom_commend',
'cat'=>$loop_cat_id,
'post_parent'=>999999999
);
}else if($type=='pay'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'post_price',
'cat'=>$loop_cat_id,
'post_parent'=>999999999
);
}else if($type=='answer'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'answer_number',
'cat'=>$loop_cat_id,
'post_parent'=>999999999
);
}else if($type=='ok'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'answer_adopt',
'cat'=>$loop_cat_id,
'post_parent'=>999999999
);
}else if($type=='no'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'cat'=>$loop_cat_id,
'post_parent'=>999999999,
'meta_query' => array(
array(
'key' => 'answer_adopt',
'compare' =>'NOT EXISTS'
),
array(
'key' => 'answer_number',
)
)
);
}else if($type=='vote'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'vote_data',
'cat'=>$loop_cat_id,
'post_parent'=>999999999
);
}else if($type=='activity'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'activity_data',
'cat'=>$loop_cat_id,
'post_parent'=>999999999
);
}else if($type=='custom_1'||$type=='custom_2'||$type=='custom_3'||$type=='custom_4'||$type=='custom_5'){//自定义模块
if($type=='custom_1'){
$topic=get_term_meta($bbs_id,'custom_menu_topic_1',true);
}else if($type=='custom_2'){
$topic=get_term_meta($bbs_id,'custom_menu_topic_2',true);
}else if($type=='custom_3'){
$topic=get_term_meta($bbs_id,'custom_menu_topic_3',true);
}else if($type=='custom_4'){
$topic=get_term_meta($bbs_id,'custom_menu_topic_4',true);
}else if($type=='custom_5'){
$topic=get_term_meta($bbs_id,'custom_menu_topic_5',true);
}

$topic_arr=explode(",",$topic);
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'cat'=>$loop_cat_id,
'tag__in' =>$topic_arr,
'post_parent'=>999999999
);
}

$args['no_found_rows']=true;


if($bbs_list_style!=2&&$bbs_list_style!=3&&$bbs_list_style!=4){//格子。瀑布流模式的?>
<div class="jinsom-bbs-box" style="min-height: 300px;">

<?php require( get_template_directory() . '/post/bbs-list-search.php' );?>

<div class="jinsom-bbs-list-box" data-no-instant>
<?php 
query_posts($args);
if (have_posts()){
while ( have_posts() ) : the_post();
if($bbs_list_style==0){
require( get_template_directory() . '/post/bbs-list-1.php' );
}else if($bbs_list_style==1){
require( get_template_directory() . '/post/bbs-list-2.php' );	
}
endwhile;


echo '<div class="jinsom-more-posts default" data="2" onclick=\'jinsom_ajax_bbs(this,"'.$type.'")\'>'.__('加载更多','jinsom').'</div>';


}else{
echo jinsom_empty();
}


?>
</div>

</div> <!-- 帖子结束 -->
<?php }else if($bbs_list_style==2){
require( get_template_directory() . '/post/bbs-list-3.php' );	
}else if($bbs_list_style==3){
require( get_template_directory() . '/post/bbs-list-4.php' );	
?>
<script>
//渲染瀑布流
var grid=$('#jinsom-bbs-list-4').masonry({
itemSelector:'.grid',
gutter:<?php echo jinsom_get_option('jinsom_waterfull_margin');?>,
});
grid.imagesLoaded().progress( function() {
grid.masonry('layout');
});    
</script>
<?php }else if($bbs_list_style==4){
require( get_template_directory() . '/post/bbs-list-5.php' );	
}?>

<!-- 底部广告 -->
<?php echo do_shortcode($ad_footer);?>



</div><!-- 左侧结束 -->
</div><!-- 主内容 -->
</div><!-- 论坛自定义css模块 -->
<?php get_footer();?>
<?php }//非移动端