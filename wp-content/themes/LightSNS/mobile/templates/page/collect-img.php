<?php 
//收藏的图片
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
jinsom_upadte_user_online_time();//更新在线状态
?>
<div data-page="collect-img" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('我收藏的图片','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>

</div>
</div>

<div class="page-content jinsom-collect-img-content infinite-scroll" data-distance="200">
<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'jin_collect';
$collect_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' AND type='img' order by ID DESC limit 15;");
if($collect_data){
$jinsom_upload_obj_style=jinsom_get_option('jinsom_upload_obj_style');
$upload_style='';
if($jinsom_upload_obj_style){
$upload_style=jinsom_get_option('jinsom_upload_style_colect_list');
}

foreach ($collect_data as $data) {
echo '<li><a href="'.$data->url.'" data-fancybox="collect"><img src="'.$data->url.$upload_style.'"></a></li>';
}

}else{
echo jinsom_empty(__('你还没有收藏任何图片','jinsom'));
}
?>

</div>
</div>        