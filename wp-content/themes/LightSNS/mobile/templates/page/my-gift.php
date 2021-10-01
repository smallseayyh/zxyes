<?php 
//我的礼物
require( '../../../../../../wp-load.php');
if(isset($_GET['author_id'])){
$user_id=$_GET['author_id'];	
}else{
$user_id=$current_user->ID;
}

$theme_url=get_template_directory_uri();
global $wpdb;
$table_name = $wpdb->prefix . 'jin_gift';
$my_gift_data = $wpdb->get_results("SELECT *,count(`name`) as count FROM $table_name WHERE `receive_user_id` ='$user_id' group by `name` order by count(*) desc limit 50;");
?>
<div data-page="my-gift" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">
<?php if(isset($_GET['author_id'])){echo __('Ta的礼物','jinsom');}else{echo __('我的礼物','jinsom');}?>
</div>
<div class="right">
<?php if(isset($_GET['author_id'])){?>
<a href="#" class="link icon-only"></a>
<?php }else{?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/gift-note.php" class="link icon-only"><?php _e('记录','jinsom');?></a>
<?php }?>
</div>
</div>
</div>

<div class="page-content">
<?php if(($user_id==$current_user->ID||jinsom_is_admin($current_user->ID))&&$my_gift_data){?>
<div class="jinsom-gift-number-tip"><?php _e('礼物积分','jinsom');?>：<span><?php echo (int)get_user_meta($user_id,'gift_number',true);?></span></div>
<?php }?>
<div class="jinsom-send-gift-form clear">
<?php 
if($my_gift_data){
foreach ($my_gift_data as $data) {
echo '
<li>
<div class="top">
<div class="icon"><img src="'.$data->img.'"></div>
<div class="name">'.$data->name.'</div>
</div>
<div class="bottom">X '.$data->count.'</div>
</li>';
}
}else{
echo jinsom_empty(__('没有收到任何礼物','jinsom'));	
}
?>
</div>
</div>

</div>
</div>        