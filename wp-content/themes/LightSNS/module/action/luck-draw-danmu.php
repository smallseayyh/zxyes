<?php
//幸运抽奖弹幕列表
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
global $wpdb;
$table_name = $wpdb->prefix.'jin_luck_draw';
$luck_data= $wpdb->get_results("SELECT * FROM $table_name WHERE user_id!='$user_id' ORDER BY rand() LIMIT 300;");

if($luck_data){
$data_arr=array();
foreach ($luck_data as $data) {
$author_id=$data->user_id;
$danmu_arr=array();
$danmu_arr['info']='抽中了 '.$data->name;
$danmu_arr['img']=jinsom_avatar_url($author_id,avatar_type($author_id));
$danmu_arr['speed']=12;
$danmu_arr['href']=jinsom_userlink($author_id);
if(is_vip($author_id)){
$danmu_arr['color']='#e74c3c';
}
array_push($data_arr,$danmu_arr);
}

echo json_encode($data_arr);

}