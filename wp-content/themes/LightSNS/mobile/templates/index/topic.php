<!-- 话题中心-tab页面 -->
<?php 
$page_id=$data['page_id'];
$topic_data=get_post_meta($page_id,'topic_show_page_data',true);
?>
<div id="jinsom-view-topic-<?php echo $index_i;?>" class="view tab <?php echo $active;?>" data-page="view-topic">
<div class="navbar">
<div class="navbar-inner">
<?php require(get_template_directory().'/mobile/templates/index/navbar.php');?>
</div>
</div>

<div class="pages navbar-through">
<div data-page="jinsom-topic-page" class="page">
<div class="page-content infinite-scroll <?php echo $hide_navbar_class;?> <?php echo $hide_toolbar_class;?> jinsom-topic-show-content" data-distance="200">

<?php 
if($topic_data){
$jinsom_topic_add=$topic_data['jinsom_topic_add'];
require(get_template_directory().'/mobile/templates/index/topic-show.php');
}else{
echo '该话题中心页面没有设置数据！';
}
?>

</div>
</div>
</div>
</div>

<script type="text/javascript">
navbar_height=parseInt($('.navbar').height());
w_height=parseInt($(window).height());
$('.jinsom-topic-show-form').height(w_height-navbar_height);
$('.jinsom-topic-show-form .left>li').click(function(event){
$(this).addClass('on').siblings().removeClass('on').parent().next().children().eq($(this).index()).show().siblings().hide();
});
</script>