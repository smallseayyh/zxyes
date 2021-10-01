<?php 
require( '../../../../../../wp-load.php');
$bbs_id=$_GET['bbs_id'];
$user_id=$current_user->ID;
$category=get_category($bbs_id);
$cat_parents=$category->parent;

$bbs_name=get_category($bbs_id)->name;
$like_number=jinsom_get_bbs_like_number($bbs_id);
$like_user_data=jinsom_get_bbs_user($bbs_id,8);


$categories_child = get_categories("child_of=".$bbs_id."&orderby=description&order=ASC&hide_empty=0");//返回子分类的数组

//论坛信息
if($cat_parents==0){
$publish_bbs_id=$bbs_id;
$bbs_mobile_bg=get_term_meta($bbs_id,'bbs_mobile_bg',true);
}else{
$publish_bbs_id=$cat_parents;
$bbs_mobile_bg=get_term_meta($cat_parents,'bbs_mobile_bg',true);
}


$desc=get_term_meta($bbs_id,'desc',true);
$bbs_notice=get_term_meta($bbs_id,'bbs_notice',true);
$bbs_power=get_term_meta($bbs_id,'bbs_power',true);
$bbs_blacklist=get_term_meta($bbs_id,'bbs_blacklist',true);
$showposts=get_term_meta($bbs_id,'bbs_showposts',true);
$post_target=get_term_meta($bbs_id,'bbs_post_target',true);
$group_im=get_term_meta($bbs_id,'bbs_group_im',true);

$post_child_commend=get_term_meta($publish_bbs_id,'bbs_post_child_commend',true);
$child_show_bbs=get_term_meta($publish_bbs_id,'child_show_bbs',true);//子论坛显示子版块

//发帖类型
$normal=(int)get_term_meta($publish_bbs_id,'bbs_normal',true);
$pay_see=(int)get_term_meta($publish_bbs_id,'bbs_pay_see',true);
$comment_see=(int)get_term_meta($publish_bbs_id,'bbs_comment_see',true);
$vip_see=(int)get_term_meta($publish_bbs_id,'bbs_vip_see',true);
$login_see=(int)get_term_meta($publish_bbs_id,'bbs_login_see',true);
$vote=(int)get_term_meta($publish_bbs_id,'bbs_vote',true);
$answer=(int)get_term_meta($publish_bbs_id,'bbs_answer',true);
$activity=(int)get_term_meta($publish_bbs_id,'bbs_activity',true);


//外观布局
$bbs_list_style=(int)get_term_meta($publish_bbs_id,'bbs_list_style',true);

if(empty($bbs_mobile_bg)){
$bbs_default_bg_mobile=jinsom_get_option('jinsom_bbs_default_bg_mobile');
if($bbs_default_bg_mobile){
$bbs_mobile_bg=$bbs_default_bg_mobile;
}else{
$bbs_mobile_bg='';  
}
}



$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);

if (is_user_logged_in()) {
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}

$bbs_visit_power=get_term_meta($publish_bbs_id,'bbs_visit_power',true);
if($bbs_visit_power==1){
if(!is_vip($user_id)&&!$is_bbs_admin){
$power=0;  
}else{
$power=1;  
}
}else if($bbs_visit_power==2){
$user_verify=get_user_meta($user_id, 'verify', true );
if(!$user_verify&&!$is_bbs_admin){
$power=0;  
}else{
$power=1;  
}
}else if($bbs_visit_power==3){
if(!$is_bbs_admin){
$power=0;  
}else{
$power=1;    
}
}else if($bbs_visit_power==4){
$user_honor=get_user_meta($user_id,'user_honor',true);
if(!$user_honor&&!$is_bbs_admin){
$power=0;  
}else{
$power=1;  
}
}else if($bbs_visit_power==5){
//先判断用户是否已经输入密码
if(!jinsom_is_bbs_visit_pass($publish_bbs_id,$user_id)&&!$is_bbs_admin){
$power=0;  
}else{
$power=1;  
}
}else if($bbs_visit_power==6){//满足经验的用户
$bbs_visit_exp=(int)get_term_meta($publish_bbs_id,'bbs_visit_exp',true);
$current_user_lv=jinsom_get_user_exp($user_id);//当前用户经验
if($current_user_lv<$bbs_visit_exp&&!$is_bbs_admin){
$power=0;  
}else{
$power=1;  
}
}else if($bbs_visit_power==7){//指定用户
$bbs_visit_user=get_term_meta($publish_bbs_id,'bbs_visit_user',true);
$bbs_visit_user_arr=explode(",",$bbs_visit_user);
if(!in_array($user_id,$bbs_visit_user_arr)&&!$is_bbs_admin){
$power=0;  
}else{
$power=1;  
}
}else if($bbs_visit_power==8){//登录用户
if(!is_user_logged_in()){
$power=0;
}else{
$power=1;
}
}else if($bbs_visit_power==9){//指定头衔
if(!$is_bbs_admin){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$bbs_visit_honor=get_term_meta($publish_bbs_id,'bbs_visit_honor',true);
$bbs_visit_honor_arr=explode(",",$bbs_visit_honor);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($bbs_visit_honor_arr,$user_honor_arr)){
$power=0;
}else{
$power=1;
}
}else{
$power=0;
}
}else{
$power=1;
}
}else if($bbs_visit_power==10){//指定认证类型
if(!$is_bbs_admin){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$bbs_visit_verify=get_term_meta($publish_bbs_id,'bbs_visit_verify',true);
$bbs_visit_verify_arr=explode(",",$bbs_visit_verify);
if(!in_array($user_verify_type,$bbs_visit_verify_arr)){
$power=0;
}else{
$power=1;
}
}else{
$power=0;
}
}else{
$power=1;
}
}else if($bbs_visit_power==11){//付费访问
if(!$is_bbs_admin){
$bbs_pay_user_list=get_term_meta($publish_bbs_id,'visit_power_pay_user_list',true);
$bbs_pay_user_list_arr=explode(",",$bbs_pay_user_list);
if($bbs_pay_user_list){
if(!in_array($user_id,$bbs_pay_user_list_arr)){
$power=0;
}else{
$power=1;
}
}else{
$power=0;
}
}else{
$power=1;
}
}else{//有权限的情况
$power=1;  
} 



$load_type='bbs';
?>
<div data-page="bbs" class="page no-tabbar">
<?php if($power){
echo '<div class="jinsom-bbs-publish-icon" onclick=\'jinsom_publish_power("bbs",'.$bbs_id.',"")\'><i class="jinsom-icon jinsom-fabiao-"></i></div>';
}

//移动端自定义css
echo '
<style type="text/css">
'.get_term_meta($publish_bbs_id,'bbs_mobile_css',true).'
</style>'
?>


<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $bbs_name;?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>


<div class="page-content infinite-scroll jinsom-bbs-content <?php if(!$power){echo 'no-power';}?>" data="<?php echo $bbs_id;?>" data-distance="500" style="background-image: url(<?php echo $bbs_mobile_bg;?>);">

<?php if($power){?>
<div class="jinsom-bbs-bg"></div>
<div class="jinsom-bbs-header">
<div class="top">
<div class="avatarimg"><?php echo jinsom_get_bbs_avatar($bbs_id,0)?></div>
<div class="number">
<?php if($cat_parents==0){?>
<span><?php _e('关注','jinsom');?>：<i><?php echo $like_number;?></i></span>
<?php }?>
<span><?php _e('内容','jinsom');?>：<i><?php echo jinsom_get_bbs_post($bbs_id);?></i></span>
</div>
</div>	
<div class="name">
<span><?php echo $bbs_name;?></span>
<?php if($cat_parents==0){?>
<?php echo jinsom_bbs_like_btn($user_id,$bbs_id);?>
<?php if($group_im){?>
<span class="chat" onclick="jinsom_join_group_chat(<?php echo $bbs_id;?>,this)"><i class="jinsom-icon jinsom-liaotian2"></i><?php _e('群聊','jinsom');?></span>
<?php }?>
<?php }?>
</div>
<div class="desc"><?php echo $desc;?></div>

<?php if($like_number){?>
<div class="member clear">
<a href="#" class="link">
<?php 
foreach ($like_user_data as $data){
echo '<li>'.jinsom_avatar($data->user_id,'40',avatar_type($data->user_id)).'</li>';
}
if($like_number>8){echo '<span>'.__('成员','jinsom').'<i class="jinsom-icon jinsom-bangzhujinru"></i></span>';}?>
</a>
</div>
<?php }?>
</div>

<?php if($bbs_notice){echo'<div class="jinsom-bbs-notice-bar">'.$bbs_notice.'</div>';}?>


<div class="jinsom-bbs-main-content">

<?php if(!empty($categories_child)){?>
<div class="jinsom-bbs-child-show child">
<?php 
foreach ($categories_child as $child) {
echo '
<li>
<a href="'.jinsom_mobile_bbs_url($child->cat_ID).'" class="link">
<div class="img">
'.jinsom_get_bbs_avatar($child->cat_ID,0).'
</div>
<div class="name">'.$child->cat_name.'</div>
</a>
</li>
';

}
?>
</div>
<?php }?>

<?php if($cat_parents>0){//子论坛显示父级论坛?>
<div class="jinsom-bbs-child-show parents">
<?php 
echo '
<li>
<a href="'.jinsom_mobile_bbs_url($publish_bbs_id).'" class="link">
<div class="img">
'.jinsom_get_bbs_avatar($publish_bbs_id,0).'
</div>
<div class="name">'.get_category($publish_bbs_id)->name.'</div>
</a>
</li>
';
?>
</div>
<?php }?>


<?php 
if($cat_parents==0||($cat_parents>0&&$post_child_commend)){
$args = array(
'meta_key' => 'jinsom_sticky',
'post_parent'=>999999999,
'no_found_rows'=>true,
'cat'=>$publish_bbs_id
);
query_posts( $args );
if (have_posts()){?>
<div class="jinsom-bbs-commend-show">
<?php 
while (have_posts()) : the_post();
$post_id=get_the_ID();
$post_views=get_post_meta($post_id,'post_views',true);  
$title_color=get_post_meta($post_id,'title_color',true);//标题颜色
if($title_color){$title_color='style="color:'.$title_color.'"';}else{$title_color='';}
echo '<li><a href="'.jinsom_mobile_post_url($post_id).'" class="link" '.$title_color.'><span class="up"></span>'.get_the_title().'</a></li>';

endwhile; wp_reset_query();
?>  
</div>
<?php }}?>

<div style="height: 2vw;display: none;" id="jinsom-waterfull-margin"></div>
<div class="jinsom-bbs-menu jinsom-bbs-menu-<?php echo $bbs_id;?>">
<?php 

//菜单
$enabled_menu=get_term_meta($publish_bbs_id,'enabled_menu',true);
$enabled_menu_arr=explode(",",$enabled_menu);
if($enabled_menu){
if($enabled_menu_arr&&$enabled_menu!='empty'){
$i=1;
foreach ($enabled_menu_arr as $data){
if($data=='comment'){
$name=__('回复','jinsom');
$topic='';
}else if($data=='new'){
$name=__('最新','jinsom');
$topic='';
}else if($data=='nice'){
$name=__('精华','jinsom');
$topic='';
}else if($data=='pay'){
$name=__('付费','jinsom');	
$topic='';
}else if($data=='answer'){
$name=__('问答','jinsom');
$topic='';
}else if($data=='ok'){
$name=__('已解决','jinsom');
$topic='';
}else if($data=='no'){
$name=__('未解决','jinsom');
$topic='';	
}else if($data=='vote'){
$name=__('投票','jinsom');
$topic='';
}else if($data=='activity'){
$name=__('活动','jinsom');	
$topic='';
}else if($data=='custom_1'){
$name=get_term_meta($publish_bbs_id,'custom_menu_name_1',true);	
$topic=get_term_meta($publish_bbs_id,'custom_menu_topic_1',true);
}else if($data=='custom_2'){
$name=get_term_meta($publish_bbs_id,'custom_menu_name_2',true);
$topic=get_term_meta($publish_bbs_id,'custom_menu_topic_2',true);
}else if($data=='custom_3'){
$name=get_term_meta($publish_bbs_id,'custom_menu_name_3',true);	
$topic=get_term_meta($publish_bbs_id,'custom_menu_topic_3',true);
}else if($data=='custom_4'){
$name=get_term_meta($publish_bbs_id,'custom_menu_name_4',true);
$topic=get_term_meta($publish_bbs_id,'custom_menu_topic_4',true);
}else if($data=='custom_5'){
$name=get_term_meta($publish_bbs_id,'custom_menu_name_5',true);
$topic=get_term_meta($publish_bbs_id,'custom_menu_topic_5',true);
}
if($i==1){
$on='class="on"';
}else{
$on='';
}

echo '<li onclick=\'jinsom_bbs_post('.$bbs_id.',"'.$data.'",this)\' '.$on.' topic="'.$topic.'" type="'.$data.'">'.$name.'</li>';
$i++;
}
}
}else{
echo '
<li onclick=\'jinsom_bbs_post('.$bbs_id.',"comment",this)\' class="on" type="comment">'.__('回复','jinsom').'</li>
<li onclick=\'jinsom_bbs_post('.$bbs_id.',"new",this)\' type="new">'.__('最新','jinsom').'</li>
<li onclick=\'jinsom_bbs_post('.$bbs_id.',"nice",this)\' type="nice">'.__('精华','jinsom').'</li>
';
}

?>


</div>


<div class="jinsom-bbs-post-list jinsom-post-list jinsom-bbs-post-list-<?php echo $bbs_list_style;?> clear" page="2" type="<?php echo $type;?>">
<?php 

$enabled_menu=get_term_meta($publish_bbs_id,'enabled_menu',true);
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
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='new'){//按最新发帖排序
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='nice'){//加精的帖子
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'jinsom_commend',
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='pay'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'post_price',
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='answer'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'answer_number',
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='ok'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'answer_adopt',
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='no'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'cat'=>$bbs_id,
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
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='activity'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'meta_key' => 'activity_data',
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='custom_1'||$type=='custom_2'||$type=='custom_3'||$type=='custom_4'||$type=='custom_5'){//自定义模块
if($type=='custom_1'){
$topic=get_term_meta($publish_bbs_id,'custom_menu_topic_1',true);
}else if($type=='custom_2'){
$topic=get_term_meta($publish_bbs_id,'custom_menu_topic_2',true);
}else if($type=='custom_3'){
$topic=get_term_meta($publish_bbs_id,'custom_menu_topic_3',true);
}else if($type=='custom_4'){
$topic=get_term_meta($publish_bbs_id,'custom_menu_topic_4',true);
}else if($type=='custom_5'){
$topic=get_term_meta($publish_bbs_id,'custom_menu_topic_5',true);
}

$topic_arr=explode(",",$topic);
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'cat'=>$bbs_id,
'tag__in' =>$topic_arr,
'post_parent'=>999999999
);
}

$args['no_found_rows']=true;
query_posts($args);
if (have_posts()){
while (have_posts()):the_post();
require(get_template_directory().'/mobile/templates/post/bbs-power.php');
endwhile;
}else{
echo jinsom_empty();
}
?>

</div><!-- jinsom-bbs-main-content -->

</div>

<?php }else{ require( get_template_directory() . '/mobile/templates/post/page-no-power.php' );}?>
</div>
</div>        
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>