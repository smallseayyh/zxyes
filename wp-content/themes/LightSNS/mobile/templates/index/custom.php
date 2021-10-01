<!-- 自定义Tab页面 -->
<div id="jinsom-view-custom-<?php echo $index_i;?>" class="view tab <?php echo $active;?>" data-page="view-custom">
<div class="navbar jinsom-custom-navbar">
<div class="navbar-inner">
<?php require(get_template_directory().'/mobile/templates/index/navbar.php');?>
</div>
</div>
<div class="pages navbar-through">
<div data-page="jinsom-custom-page" class="page">
<div class="page-content jinsom-custom-page-content <?php echo $hide_navbar_class;?> <?php echo $hide_toolbar_class;?>">
<?php
echo do_shortcode($data['jinsom_mobile_tab_custom_html']);?>
</div>
</div>
</div>
</div>