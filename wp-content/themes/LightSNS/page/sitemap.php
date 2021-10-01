<?php
/*
Template Name:网站地图
*/
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');	
}else{
get_header();

?>
<style type="text/css">
.jinsom-sitemap .box ul li {
    list-style-type: disc;
    margin-bottom: 10px;
    background-color: #fff !important;
}
.jinsom-sitemap .box ul {
    padding: 20px;
}
</style>
<!-- 主内容 -->
<div class="jinsom-main-content single clear">
<div class="jinsom-content-left full"><!-- 左侧 -->
<div class="jinsom-page-content">
<div class="jinsom-sitemap">
<div class="box">
<h1><?php _e('最新发表的内容','jinsom');?></h1>
<ul>
<?php
$posts = get_posts('numberposts=-1&orderby=post_date&showposts=100&order=DESC');
foreach($posts as $post):
$title=get_the_title();
if($title==''){$title=__('动态内容','jinsom');}
?>
<li><a href="<?php the_permalink(); ?>" title="<?php echo $title; ?>" target="_blank"><?php echo $title; ?></a></li>
<?php endforeach; ?>
</ul>
</div>

<div class="box">
<h1><?php echo jinsom_get_option('jinsom_bbs_name');?></h1>
<ul>
<?php wp_list_categories('title_li='); ?>
</ul>
</div>	

<div class="box">
<h1><?php echo jinsom_get_option('jinsom_topic_name');?></h1>
<ul>
<?php wp_tag_cloud('number=2000'); ?>
</ul>
</div>	

<div class="box">
<h1><?php _e('最新注册用户','jinsom');?></h1>
<ul>
<?php 
$user_query = new WP_User_Query( array ( 
'orderby'=>'registered',
'order' => 'DESC',
'count_total'=>false,
'number' => 100
));
if (!empty($user_query->results)){
foreach ($user_query->results as $user){
$nickname=get_user_meta($user->ID,'nickname',true);
echo '<li><a href="'.jinsom_userlink($user->ID).'" title="'.$nickname.'" target="_blank">'.$nickname.'</a></li>';
}
}
 ?>
</ul>
</div>

</div>


</div>
</div><!-- 左侧结束 -->
</div>
<?php get_footer();
} ?>
