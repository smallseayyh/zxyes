<!-- 视频专题页面 -->
<div id="jinsom-view-video-<?php echo $index_i;?>" class="view tab <?php echo $active;?>" data-page="view-video">
<div class="navbar">
<div class="navbar-inner">
<?php require(get_template_directory().'/mobile/templates/index/navbar.php');?>

<?php 
//视频专题菜单
$page_id=$data['page_id'];
$video_data=get_post_meta($page_id,'video_show_page_data',true);
if($video_data){
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
}
?>
</div>
</div>
<div class="pages navbar-through">
<div data-page="jinsom-video-page" class="page">
<div class="page-content infinite-scroll <?php echo $hide_navbar_class;?> <?php echo $hide_toolbar_class;?> jinsom-video-page-content" data-distance="200">

<?php 
if($video_data){
require(get_template_directory().'/mobile/templates/index/video-special.php');
}else{
echo '请在新建页面的时候配置当前页面的数据！';
}
?>

</div>
</div>
</div>
</div>

<script type="text/javascript">
jinsom_index_video_special_js_load('#jinsom-view-video-<?php echo $index_i;?>');//视频专题需要加载的js	
</script>