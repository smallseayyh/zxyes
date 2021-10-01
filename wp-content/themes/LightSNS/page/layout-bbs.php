<?php
/*
Template Name:论坛大厅
*/
?>
<?php 
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');	
}else{
$require_url=get_template_directory();
get_header();
$post_id=get_the_ID();
$user_id = $current_user->ID;
$bbs_data=get_post_meta($post_id,'bbs_show_page_data',true);

if(!$bbs_data){
echo jinsom_empty('请在新建页面的时候配置当前页面的数据！');exit();
}

$jinsom_bbs_show_add=$bbs_data['jinsom_bbs_show_add'];
$jinsom_bbs_header=$bbs_data['jinsom_bbs_header'];
$bbs_header_height=$bbs_data['jinsom_bbs_header_height'];
$jinsom_bbs_hide_arr=jinsom_get_option('jinsom_bbs_show_hide');//论坛大厅隐藏的板块
$slider_width=$jinsom_bbs_header['jinsom_bbs_slider_width'];

$args = array(
'post_status' =>'publish',
'showposts' => $jinsom_bbs_header['list_number'],
'post_parent'=>999999999,
'no_found_rows'=>true,
'category__not_in'=>$jinsom_bbs_hide_arr,
'ignore_sticky_posts'=>1
);


//过滤数据
$jinsom_bbs_list_data_type_bbs_id=$jinsom_bbs_header['jinsom_bbs_list_data_type_bbs_id'];
if($jinsom_bbs_header['jinsom_bbs_list_data_type']=='b'&&$jinsom_bbs_list_data_type_bbs_id){
$args['cat']=$jinsom_bbs_list_data_type_bbs_id;
}else{
$args['cat']='';
}


if(!$jinsom_bbs_header['jinsom_bbs_slider_on_off']){
$list_width='100%';
}else{
$list_width='calc( 100% - '.$jinsom_bbs_header['jinsom_bbs_slider_width'].'%)';
}

if(!$jinsom_bbs_header['jinsom_bbs_list_on_off']){
$slider_width='100%';
}else{
$slider_width=$jinsom_bbs_header['jinsom_bbs_slider_width'].'%';	
}

?>
<!-- 主内容 -->
<div class="jinsom-main-content bbs-show clear">
<?php if($jinsom_bbs_header['jinsom_bbs_slider_on_off']||$jinsom_bbs_header['jinsom_bbs_list_on_off']){?>
<div class="jinsom-show-bbs-box">
<div class="jinsom-show-bbs-content clear" style="height:<?php echo $bbs_header_height;?>px;">

<?php if($jinsom_bbs_header['jinsom_bbs_slider_on_off']){?>
<div class="left" style="width: <?php echo $slider_width;?>;">
<div class="layui-carousel" id="jinsom-bbs-show-slider">
<div carousel-item>
<?php 
if($jinsom_bbs_header['jinsom_bbs_slider_add']){
foreach ($jinsom_bbs_header['jinsom_bbs_slider_add'] as $data){
if($data['title']!=''){$title='<p>'.$data['title'].'</p>';}else{$title='';}
echo '<a class="jinsom-bbs-slider-item" style="background-image: url('.$data['images'].');" href="'.$data['link'].'">'.$title.'</a>';
}
}else{
echo jinsom_empty(__('请添加幻灯片','jinsom'));
}
?>
</div>
</div>
</div>
<?php }?>



<?php 
$enabled=$jinsom_bbs_header['list']['enabled'];
if($jinsom_bbs_header['jinsom_bbs_list_on_off']&&$enabled){?>
<div class="right" style="width:<?php echo $list_width;?>;">
	
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title">
<?php 
foreach($enabled as $x=>$x_value){
switch($x){
case 'new': 
if(key($enabled)==$x){$class='class="layui-this"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('最新','jinsom').'</li>';  
break;
case 'hot': 
if(key($enabled)==$x){$class='class="layui-this"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('热门','jinsom').'</li>';  
break;
case 'nice': 
if(key($enabled)==$x){$class='class="layui-this"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('精品','jinsom').'</li>';  
break;
case 'pay': 
if(key($enabled)==$x){$class='class="layui-this"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('付费','jinsom').'</li>';  
break;
case 'answer': 
if(key($enabled)==$x){$class='class="layui-this"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('问答','jinsom').'</li>';  
break;
case 'activity': 
if(key($enabled)==$x){$class='class="layui-this"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('活动','jinsom').'</li>';  
break;
case 'vote': 
if(key($enabled)==$x){$class='class="layui-this"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('投票','jinsom').'</li>';  
break;
case 'custom_1': 
if(key($enabled)==$x){$class='class="layui-this"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.$jinsom_bbs_header['custom_a_name'].'</li>';  
break;
case 'custom_2': 
if(key($enabled)==$x){$class='class="layui-this"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.$jinsom_bbs_header['custom_b_name'].'</li>';  
break;
case 'custom_3': 
if(key($enabled)==$x){$class='class="layui-this"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.$jinsom_bbs_header['custom_c_name'].'</li>';  
break;
}}
?>

</ul>
<div class="layui-tab-content">
<?php
$date_query=array(
array(
'column' => 'post_date',
'before' => date('Y-m-d',time()+3600*24),
'after' =>date('Y-m-d',time()-3600*24*30)
)
);

foreach($enabled as $x=>$x_value){
switch($x){
case 'new':
$args['meta_key']='';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'nice':
$args['meta_key']='jinsom_commend';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'hot':
$args['meta_key']='';
$args['orderby']='comment_count';
$args['date_query']=$date_query;
require($require_url.'/page/layout-bbs-list.php');
break;
case 'pay':
$args['meta_key']='post_price';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'answer':
$args['meta_key']='answer_number';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'activity':
$args['meta_key']='activity_data';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'vote':
$args['meta_key']='vote_data';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'custom_1':
$args['meta_key']='';
$args['cat']=$jinsom_bbs_header['custom_bbs_a'];
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'custom_2':
$args['meta_key']='';
$args['cat']=$jinsom_bbs_header['custom_bbs_b'];
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'custom_3':
$args['meta_key']='';
$args['cat']=$jinsom_bbs_header['custom_bbs_c'];
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
}}
?>
</div>
</div>      

</div><!-- right -->
<?php }?>


</div>
</div>
<?php }?>



<div class="jinsom-layout-bbs-content">

<?php 
$custom_sidebar=$bbs_data['jinsom_bbs_sidebar'];
if($custom_sidebar!='no'){
require(get_template_directory().'/sidebar/sidebar-custom.php');//引入右侧栏
}
?>

<div class="jinsom-content-left <?php if($custom_sidebar=='no'){echo 'full';}?>"><!-- 左侧 -->

<?php 
global $wpdb;
$date=date('Y-m-d');
$jinsom_bbs_active_user_number=$bbs_data['jinsom_bbs_active_user_number'];
if($jinsom_bbs_active_user_number){
$jinsom_bbs_number_count=$bbs_data['jinsom_bbs_number_count'];

if($jinsom_bbs_number_count){
$today_publish = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status='publish' AND post_date like '$date%'");
$all_users = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users;");//总人数
$all_posts = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->posts where post_type='post' and post_status='publish';");//总内容
}
?>
<div class="jinsom-show-bbs-box user">
<div class="jinsom-show-bbs-box-header user clear">
<div class="name"><?php _e('活跃用户','jinsom');?></div>	
<?php if($jinsom_bbs_number_count){?>
<div class="number"><span><?php _e('用户','jinsom');?>：<i><?php echo $all_users;?></i></span><span><?php _e('内容','jinsom');?>：<i><?php echo $all_posts;?></i></span><span><?php _e('今日','jinsom');?>：<i><?php echo $today_publish;?></i></span></div>
<?php }?>
</div>
<div class="jinsom-show-user-box-content clear">
<?php 
$user_query = new WP_User_Query( array ( 
'orderby' => 'meta_value',
'order' => 'DESC',
'meta_key' => 'latest_login',
'count_total'=>false,
'number' => $jinsom_bbs_active_user_number
));
if (!empty($user_query->results)){
foreach ($user_query->results as $user){
$last_login=get_user_meta($user->ID,'latest_login',true);

$nickname=get_user_meta($user->ID,'nickname',true);
echo '
<li title="'.$nickname.'：'.jinsom_timeago($last_login).'('.jinsom_get_online_type($user->ID).')">
<a href="'.jinsom_userlink($user->ID).'" target="_blank">'.jinsom_vip_icon($user->ID).jinsom_avatar($user->ID,'60',avatar_type($user->ID)).jinsom_verify($user->ID).'</a>
</li>';
}
}
?>	
</div>
</div>
<?php }?>

<?php if (is_user_logged_in()&&$bbs_data['jinsom_bbs_show_myfollow']){?>
<div class="jinsom-show-bbs-box">
<div class="jinsom-show-bbs-box-header clear">
<div class="name"><?php _e('我关注的','jinsom');?></div>	
</div>
<div class="jinsom-show-bbs-box-content clear">
<?php 
$follow_bbs_arr=jinsom_get_user_follow_bbs_id($user_id);
if($follow_bbs_arr){
foreach ($follow_bbs_arr as $bbs_id) {
$category=get_category($bbs_id);
$cat_parents=$category->parent;
$desc=strip_tags(get_term_meta($bbs_id,'desc',true));
if(!$cat_parents){
?>
<li>

<?php if(jinsom_is_admin($user_id)){?>
<i class="jinsom-icon jinsom-shezhi" onclick="jinsom_bbs_setting_form(<?php echo $bbs_id;?>);"></i>
<?php }?>
	
<a href="<?php echo get_category_link($bbs_id);?>" target="_blank">
<div class="top clear">
<div class="left">
<?php echo jinsom_get_bbs_avatar($bbs_id,0);?>
</div>	
<div class="right">
<div class="title">
<?php echo get_category($bbs_id)->name;?>
<?php if((int)get_term_meta($bbs_id,'today_publish',true)){?>
<span class="layui-badge-dot tip" title="<?php _e('今天有内容更新','jinsom');?>"></span>
<?php }?>
</div>
<div class="desc" title="<?php echo $desc;?>"><?php echo $desc;?></div>
</div>
</div>	
</a>
<div class="bottom">
<span>内容：<i><?php echo jinsom_get_bbs_post($bbs_id);?></i></span>
<span>关注：<i><?php echo jinsom_get_bbs_like_number($bbs_id);?></i></span>
<a href="<?php echo get_category_link($bbs_id);?>" target="_blank"><?php _e('点击进入','jinsom');?></a>
</div>	
</li>

<?php }//不显示子论坛

}}else{
echo jinsom_empty();
}?>

</div>
</div>
<?php }?>


<?php 
if($jinsom_bbs_show_add){

foreach ($jinsom_bbs_show_add as $jinsom_bbs_show_adds) {
$type=$jinsom_bbs_show_adds['jinsom_bbs_show_add_type'];
$ad=$jinsom_bbs_show_adds['ad'];
$title=$jinsom_bbs_show_adds['title'];
$more_name=$jinsom_bbs_show_adds['more_name'];
$more_link=$jinsom_bbs_show_adds['more_link'];
$more_target=$jinsom_bbs_show_adds['more_target'];
$bbs_txt=$jinsom_bbs_show_adds['bbs'];
$bbs_arr=explode(",",$bbs_txt);
if($type=='a'){
?>

<div class="jinsom-show-bbs-box">
<div class="jinsom-show-bbs-box-header clear">
<div class="name"><?php echo $title;?></div>
<?php if($more_name){?>
<div class="more"><a href="<?php echo $more_link;?>" <?php if($more_target){echo 'target="_blank"';}?>><?php echo $more_name;?></a></div>	
<?php }?>
</div>
<div class="jinsom-show-bbs-box-content clear">
<?php 
foreach ($bbs_arr as $bbs_id) {
$category=get_category($bbs_id);
$cat_parents=$category->parent;
$desc=strip_tags(get_term_meta($bbs_id,'desc',true));
?>
<li>

<?php if(jinsom_is_admin($user_id)){?>
<?php if($cat_parents==0){?>
<i class="jinsom-icon jinsom-shezhi" onclick="jinsom_bbs_setting_form(<?php echo $bbs_id;?>);"></i>
<?php }else{?>
<i class="jinsom-icon jinsom-shezhi" onclick="jinsom_bbs_setting_form_child(<?php echo $bbs_id;?>);"></i>
<?php }?>
<?php }?>

<a href="<?php echo get_category_link($bbs_id);?>" target="_blank">
<div class="top clear">
<div class="left">
<?php echo jinsom_get_bbs_avatar($bbs_id,0);?>
</div>	
<div class="right">
<div class="title">
<?php echo get_category($bbs_id)->name;?>
<?php if((int)get_term_meta($bbs_id,'today_publish',true)){?>
<span class="layui-badge-dot tip" title="<?php _e('今天有内容更新','jinsom');?>"></span>
<?php }?>
</div>
<div class="desc" title="<?php echo $desc;?>"><?php echo $desc;?></div>
</div>
</div>	
</a>
<div class="bottom">
<span>内容：<i><?php echo jinsom_get_bbs_post($bbs_id);?></i></span>
<?php if($cat_parents==0){?>
<span>关注：<i><?php echo jinsom_get_bbs_like_number($bbs_id);?></i></span>
<?php }?>
<a href="<?php echo get_category_link($bbs_id);?>" target="_blank"><?php _e('点击进入','jinsom');?></a>
</div>	
</li>

<?php }?>



</div>
</div>

<?php 
}else{//自定义html代码
echo do_shortcode($ad);
}


}}?>
</div><!-- 左侧栏 -->
</div>



</div>

<script>
layui.use('carousel', function(){
var carousel = layui.carousel;
carousel.render({
elem: '#jinsom-bbs-show-slider',
width: '100%',
height: '<?php echo $bbs_header_height;?>px',
arrow: 'hover',
anim: 'fade',
autoplay:true
});
});
</script>
<?php get_footer(); ?>


<?php }//判断是否移动端?>