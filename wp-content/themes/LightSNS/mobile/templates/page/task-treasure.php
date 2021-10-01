<?php 
//任务
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$theme_url=get_template_directory_uri();
$task_id=strip_tags($_GET['task_id']);
$jinsom_task_treasure_add=jinsom_get_option('jinsom_task_treasure_add'); 
?>
<div data-page="task-treasure" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('宝箱','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content">

<?php require( get_template_directory() . '/module/stencil/task-treasure-html.php' );?>

</div>
</div>        