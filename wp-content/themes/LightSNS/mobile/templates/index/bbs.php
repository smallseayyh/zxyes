<div id="jinsom-view-bbs-<?php echo $index_i;?>" class="view tab <?php echo $active;?>" data-page="view-bbs">

<div class="navbar jinsom-home-navbar">
<div class="navbar-inner">
<?php require(get_template_directory().'/mobile/templates/index/navbar.php');?>
</div>
</div>




<div class="pages navbar-through">
<div data-page="jinsom-home-page" class="page">


<div class="page-content jinsom-show-bbs-content <?php echo $hide_navbar_class;?> <?php echo $hide_toolbar_class;?>"><!-- 内容区 -->

<?php 
$page_id=$data['page_id'];
require(get_template_directory().'/mobile/templates/index/bbs-show.php');
?>

</div>
</div>
</div>
</div>

<?php if(!$data['is_login']||is_user_logged_in()){?>
<script type="text/javascript">
<?php if($jinsom_bbs_header['jinsom_mobile_bbs_slider_add']){//论坛大厅幻灯片?>
$('#jinsom-bbs-slider').owlCarousel({
items: 1,
margin:15,
<?php if($jinsom_bbs_header['jinsom_mobile_bbs_slider_autoplay']){?>
autoplay:true,
autoplayTimeout:5000,
<?php }?>
loop: true,
});
<?php }?>
</script>
<?php }?>