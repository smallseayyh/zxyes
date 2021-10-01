<?php 
//论坛大厅-内页
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$page_id=$_GET['post_id'];
$bbs_data=get_post_meta($page_id,'bbs_show_page_data',true);
?>
<div data-page="bbs-show" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $bbs_data['jinsom_bbs_mobile_header_name'];?></div>
<div class="right">
<a href="#>" class="link icon-only"></a>
</div>
</div>
</div>


<div class="page-content keep-toolbar-on-scroll jinsom-show-bbs-content">

<?php require(get_template_directory().'/mobile/templates/index/bbs-show.php');?>

</div>
</div>        