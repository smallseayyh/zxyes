<?php 
//任务
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$theme_url=get_template_directory_uri();
?>
<div data-page="task" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding" id="jinsom-task-navbar-center"><span class="on"><?php _e('今日任务','jinsom');?></span><span><?php _e('成长任务','jinsom');?></span></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content">

<?php require( get_template_directory() . '/module/stencil/task-html.php' );?>

</div>
</div>        
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>