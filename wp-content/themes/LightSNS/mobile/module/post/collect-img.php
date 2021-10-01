<?php 
//收藏-图片
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$page=(int)$_POST['page'];
if(!$page){$page=1;}
$number=15;
$offset=($page-1)*$number;


global $wpdb;
$table_name = $wpdb->prefix . 'jin_collect';
$collect_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' AND type='img' order by ID DESC limit $offset,$number;");
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
echo 0;
}