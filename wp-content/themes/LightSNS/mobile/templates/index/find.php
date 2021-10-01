<!-- 发现 -->
<div id="jinsom-view-find-<?php echo $index_i;?>" class="view tab <?php echo $active;?>" data-page="view-find">

<div class="navbar jinsom-find-navbar">
<div class="navbar-inner">
<?php require(get_template_directory().'/mobile/templates/index/navbar.php');?>
</div>
</div>


<div class="pages navbar-through">
<div data-page="jinsom-find-page" class="page">
<div class="page-content jinsom-find-content <?php echo $hide_navbar_class;?> <?php echo $hide_toolbar_class;?>">
<?php if(!$data['is_login']||is_user_logged_in()){?>
<div class="jinsom-load-post"><div class="jinsom-loading-post"><i></i><i></i><i></i><i></i><i></i></div></div>
<?php }?>
</div>
</div>
</div>
</div>

<?php if(!$data['is_login']||is_user_logged_in()){?>
<script type="text/javascript">
$.ajax({   
url:jinsom.mobile_ajax_url+"/stencil/find-page.php",
type:'POST',      
success:function(msg){
$('.jinsom-find-content').html(msg);
}
});
</script>
<?php }?>