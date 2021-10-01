<?php 
require( '../../../../../../wp-load.php');
$jinsom_mobile_search_post_hot_add = jinsom_get_option('jinsom_mobile_search_post_hot_add');
?>
<div data-page="search-mobile" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner" style="padding-right: 0;">
<div class="left" style="display: none;"></div>
<div class="center">
<form id="jinsom-search-form" action="">
<i class="jinsom-icon jinsom-sousuo1"></i><input type="search" id="jinsom-search" placeholder="<?php _e('搜索你感兴趣的内容','jinsom');?>">
</form>
</div>
<div class="right" style="margin-left: 0;">
<a href="#" class="link icon-only back"><?php _e('取消','jinsom');?></a>
</div>

<div class="subnavbar">
<?php 
$jinsom_search_menu = jinsom_get_option('jinsom_search_menu_a');
$enabled=$jinsom_search_menu['enabled'];
if($enabled){
echo '<div class="jinsom-search-tab jinsom-home-menu">';
echo '<li type="all" class="on" onclick=\'jinsom_ajax_search("all",this)\'>'.__('全部','jinsom').'</li>'; 
foreach($enabled as $x=>$x_value) {
switch($x){
case 'user': 
echo '<li type="'.$x.'" onclick=\'jinsom_ajax_search("'.$x.'",this)\'>'.__('用户','jinsom').'</li>';    
break;
case 'bbs':    
echo '<li type="'.$x.'" onclick=\'jinsom_ajax_search("'.$x.'",this)\'>'.__('帖子','jinsom').'</li>'; 
break; 
case 'words':    
echo '<li type="'.$x.'" onclick=\'jinsom_ajax_search("'.$x.'",this)\'>'.__('动态','jinsom').'</li>'; 
break;
case 'music':    
echo '<li type="'.$x.'" onclick=\'jinsom_ajax_search("'.$x.'",this)\'>'.__('音乐','jinsom').'</li>'; 
break;
case 'single': 
echo '<li type="'.$x.'" onclick=\'jinsom_ajax_search("'.$x.'",this)\'>'.__('文章','jinsom').'</li>'; 
break;
case 'video': 
echo '<li type="'.$x.'" onclick=\'jinsom_ajax_search("'.$x.'",this)\'>'.__('视频','jinsom').'</li>'; 
break;
case 'forum':    
echo '<li type="'.$x.'" onclick=\'jinsom_ajax_search("'.$x.'",this)\'>'.jinsom_get_option('jinsom_bbs_name').'</li>'; 
break; 
case 'topic':    
echo '<li type="'.$x.'" onclick=\'jinsom_ajax_search("'.$x.'",this)\'>'.jinsom_get_option('jinsom_topic_name').'</li>'; 
break; 
}}

echo '</div>';
}
?>
</div>
</div>
</div>

<div class="page-content infinite-scroll jinsom-search-content" data-distance="800">

<?php if($_COOKIE['history-search']){?>
<div class="jinsom-search-hot history">
<div class="title"><i style="font-size: 4.2vw;" class="jinsom-icon jinsom-lishi"></i> 历史搜索
<div class="right"><i class="jinsom-icon jinsom-shanchu" onclick="jinsom_history_search_clear()"></i></div>
</div>
<div class="content clear">
<?php 
$history_search_arr=explode(",",$_COOKIE['history-search']);
foreach (array_reverse($history_search_arr) as $data) {
echo '<li onclick=\'jinsom_search("'.$data.'")\'>'.$data.'</li>';
}
?>
</div>
</div>
<?php }?>

<?php if(jinsom_get_option('jinsom_mobile_search_post_hot_on_off')){?>
<div class="jinsom-search-hot">
<div class="title"><?php echo jinsom_get_option('jinsom_mobile_search_post_hot_title');?></div>
<div class="content clear">
<?php 
if($jinsom_mobile_search_post_hot_add){
foreach ($jinsom_mobile_search_post_hot_add as $hot) {
echo '<li onclick=\'jinsom_search("'.$hot['title'].'")\'>'.$hot['title'].'</li>';
}
}
?>
</div>
</div>
<?php }?>

<?php if(jinsom_get_option('jinsom_mobile_search_hot_bbs_on_off')){
$jinsom_mobile_search_hot_bbs_list=jinsom_get_option('jinsom_mobile_search_hot_bbs_list');
$hot_bbs_arr=explode(",",jinsom_get_option('jinsom_mobile_search_hot_bbs_list'));
?>
<div class="jinsom-pop-search-bbs">
<div class="title"><?php echo jinsom_get_option('jinsom_mobile_search_hot_bbs_title');?></div>
<div class="list clear">
<?php 
if($jinsom_mobile_search_hot_bbs_list){
foreach ($hot_bbs_arr as $data) {
echo '<li><a href="'.jinsom_mobile_bbs_url($data).'" class="link">'.jinsom_get_bbs_avatar($data,0).'<p>'.get_category($data)->name.'</p></a></li>';
}
}
?>
</div>
</div>
<?php }?>

<?php if(jinsom_get_option('jinsom_mobile_search_hot_topic_on_off')){
$jinsom_mobile_search_hot_topic_list=jinsom_get_option('jinsom_mobile_search_hot_topic_list');
$hot_topic_arr=explode(",",jinsom_get_option('jinsom_mobile_search_hot_topic_list'));
?>
<div class="jinsom-pop-search-topic">
<div class="title"><?php echo jinsom_get_option('jinsom_mobile_search_hot_topic_title');?></div>
<div class="list clear">
<?php 
if($jinsom_mobile_search_hot_topic_list){
foreach ($hot_topic_arr as $data) {
$topic_data=get_term_by('id',$data,'post_tag');
echo '
<li>
<a href="'.jinsom_mobile_topic_id_url($data).'" class="link">
<div class="shadow"></div>
<img src="'.jinsom_topic_bg($data).'">
<p>#'.$topic_data->name.'#</p>
</a>
</li>';
}
}
?>
</div>
</div>
<?php }?>



<div class="jinsom-search-post-list" page="2" type="all"></div>


</div>
</div>