<!-- 我的 -->
<div id="jinsom-view-mine-<?php echo $index_i;?>" class="view tab <?php echo $active;?>" data-page="view-mine">

<div class="navbar jinsom-mine-navbar">
<div class="navbar-inner">
<?php require(get_template_directory().'/mobile/templates/index/navbar.php');?>
</div>
</div>


<div class="pages navbar-through">
<div data-page="jinsom-mine-page" class="page"><!-- no-navbar -->
<div class="page-content jinsom-mine-page <?php echo $hide_navbar_class;?> <?php echo $hide_toolbar_class;?>">
<?php if(is_user_logged_in()){?>
<div class="jinsom-load-post"><div class="jinsom-loading-post"><i></i><i></i><i></i><i></i><i></i></div></div>
<?php }?>
</div>
</div>
</div>
</div>

<?php if(is_user_logged_in()){?>
<script type="text/javascript">
$.ajax({   
url:jinsom.mobile_ajax_url+"/stencil/mine-page.php",
type:'POST',    
success:function(msg){
$('.jinsom-mine-page').html(msg);

if($('.jinsom-mine-box li.notice').length>0){
$.ajax({   
url:jinsom.jinsom_ajax_url+"/action/notice-all.php",
type:'POST',    
success:function(msg){
if($.trim(msg)){
$('.jinsom-mine-box li.notice .item-after,.jinsom-mine-box.cell li.notice i,.toolbar .mine i').html('<span class="badge bg-red tips">'+msg+'</span>');	
}
}
});	
}

}
});


</script>
<?php }?>