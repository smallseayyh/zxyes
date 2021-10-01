<?php 
//话题中心
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$topic_data=get_post_meta($post_id,'topic_show_page_data',true);
?>
<div data-page="topic-show" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $topic_data['header_name'];?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-topic-show-content">
<?php 
if($topic_data){
$jinsom_topic_add=$topic_data['jinsom_topic_add'];
require(get_template_directory().'/mobile/templates/index/topic-show.php');
}else{
echo '话题中心页面没有设置数据！';
}
?>
</div>

</div>
</div>        