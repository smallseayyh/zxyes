<?php 
//视频专题页面
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
$page_id=$_GET['post_id'];
$video_data=get_post_meta($page_id,'video_show_page_data',true);



$video_data=get_post_meta($page_id,'video_show_page_data',true);
if($video_data){
$header_name=$video_data['jinsom_video_mobile_header_name'];
}else{
$header_name=__('视频','jinsom');
}
?>
<div data-page="video-special" class="page no-tabbar" id="jinsom-page-video-special">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $header_name;?></div>
<div class="right">
<a href="#>" class="link icon-only"></a>
</div>
<?php 
//视频专题菜单
$jinsom_mobile_video_special_add=$video_data['jinsom_mobile_video_special_add'];
if($jinsom_mobile_video_special_add){
echo '<div class="subnavbar"><div class="jinsom-video-special-menu clear">';
$i=1;
foreach ($jinsom_mobile_video_special_add as $data) {
if($i==1){$on='class="on"';}else{$on='';}
echo '<li '.$on.' onclick=\'jinsom_video_post_data(this)\' data="'.$data['topic'].'">'.$data['title'].'</li>';
$i++;
}
echo '</div></div>';
}
?>
</div>
</div>


<div class="page-content infinite-scroll keep-toolbar-on-scroll jinsom-video-page-content" data-distance="200">

<?php require(get_template_directory().'/mobile/templates/index/video-special.php');?>

</div>
</div>        