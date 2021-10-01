<!-- 消息 -->
<div id="jinsom-view-notice-<?php echo $index_i;?>" class="view tab <?php echo $active;?>" data-page="view-notice">

<div class="navbar  jinsom-notice-navbar">
<div class="navbar-inner">
<?php require(get_template_directory().'/mobile/templates/index/navbar.php');?>

<!-- 聊天tab -->
<div class="subnavbar">
<div class="jinsom-chat-tab">
<a href="#jinsom-chat-tab-recently" class="link tab-link jinsom-tab-button active"><?php _e('最近','jinsom');?></a>
<a href="#jinsom-chat-tab-follow" class="link tab-link jinsom-tab-button"><?php _e('关注','jinsom');?></a>
<?php if(jinsom_get_option('jinsom_im_group_on_off')){?>
<a href="#jinsom-chat-tab-group" class="link tab-link jinsom-tab-button"><?php _e('群组','jinsom');?></a>
<?php }?>
</div>
</div>

</div>
</div>



<div class="pages navbar-through notice">
<div data-page="jinsom-notice-page" class="page jinsom-notice-page">
<div class="jinsom-notice-page-content page-content pull-to-refresh-content <?php echo $hide_navbar_class;?> <?php echo $hide_toolbar_class;?>"><!-- 内容区 -->

<?php if(is_user_logged_in()){?>
<div class="pull-to-refresh-layer"><div class="pull-to-refresh-arrow"><i class="jinsom-icon jinsom-xialashuaxin"></i></div></div>
<div class="jinsom-chat tabs">
<div class="jinsom-load-post"><div class="jinsom-loading-post"><i></i><i></i><i></i><i></i><i></i></div></div>
</div>
<?php }?>
</div>
</div>
</div>
</div>


<?php if(is_user_logged_in()){?>
<script type="text/javascript">
jinsom_index_notice_js_load();//加载消息页面

//下拉刷新
var ptrContent = $('.jinsom-notice-page-content.pull-to-refresh-content');
ptrContent.on('refresh', function (e) {
if($('.jinsom-load-post').length>0){//防止多次下拉
return false;	
}
$('.jinsom-chat').prepend(jinsom.loading_post);
myApp.pullToRefreshDone();
// //下拉刷新完成
setTimeout(function (){
jinsom_index_notice_js_load();
$('.jinsom-chat-tab a').first().addClass('active').siblings().removeClass('active');
layer.open({content:'刷新成功',skin:'msg',time:2});
}, 800);

});

</script>
<?php }?>