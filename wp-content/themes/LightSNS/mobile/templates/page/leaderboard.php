<?php 
//排行榜
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$number=0;
$page_option_data=get_post_meta($post_id,'page_leaderboard_data',true);
$jinsom_leaderboard_add=$page_option_data['jinsom_leaderboard_add'];
$type=$jinsom_leaderboard_add[$number]['type'];
$type_name=$jinsom_leaderboard_add[$number]['unit'];//单位

?>
<div data-page="leaderboard" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $page_option_data['header_name'];?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>

<?php if($jinsom_leaderboard_add){?>
<div class="subnavbar">
<div class="jinsom-home-menu leaderboard clear">
<?php 
$i=1;
foreach($jinsom_leaderboard_add as $data){
if($i==1){
$on='class="on"';
}else{
$on='';
}
echo '<li onclick=\'jinsom_leaderboard_data('.$post_id.',this)\' '.$on.'>'.$data['name'].'</li>'; 
$i++;
}
?>
</div>
<?php }?>

</div>


</div>
</div>

<div class="page-content">

<?php if($jinsom_leaderboard_add){?>
<div class="jinsom-leaderboard-content">
<?php require( get_template_directory() . '/mobile/module/stencil/leaderboard-list.php' );?>
</div>
<?php }?>

</div>

</div>
</div>        