<?php
//我关注的论坛
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$topic_name=$_POST['topic_name'];
$bbs_name=jinsom_get_option('jinsom_bbs_name');//论坛名称
$type=$_POST['type'];
$jinsom_publish_bbs_user_select_type = jinsom_get_option('jinsom_publish_bbs_user_select_type');

echo '<div class="jinsom-follow-bbs-content clear">';

if($type=='commend-bbs'){//展示推荐的论坛
$jinsom_bbs_commend_list = jinsom_get_option('jinsom_bbs_commend_list');
if($jinsom_bbs_commend_list){
$jinsom_bbs_commend_list_arr=explode(",",$jinsom_bbs_commend_list);
foreach ($jinsom_bbs_commend_list_arr as $data) {
echo '
<li onclick=\'jinsom_publish_power("bbs",'.$data.',"'.$topic_name.'")\'>
<div class="img">
'.jinsom_get_bbs_avatar($data,0).'
</div>
<div class="name">'.get_category($data)->name.'</div>
</li>';
}

}else{
echo jinsom_empty('还没有设置推荐的'.$bbs_name);
}

}else if($type=='follow-bbs'){//展示关注的论坛

global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
$bbs_data = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id ORDER BY ID DESC limit 100;");
if($bbs_data){
echo '<p class="tips">请从以下你关注的'.$bbs_name.'中选取</p>';
echo '<div class="content">';
foreach ($bbs_data as $data) {
$bbs_id=$data->bbs_id;
$category=get_category($bbs_id);
$bbs_parents=$category->parent;
if($bbs_parents==0){
echo '
<li onclick=\'jinsom_publish_power("bbs",'.$bbs_id.',"'.$topic_name.'")\'>
<div class="img">
'.jinsom_get_bbs_avatar($bbs_id,0).'
</div>
<div class="name">'.get_category($bbs_id)->name.'</div>
</li>';
}

}
echo '</div>';
}else{
echo jinsom_empty('你还没有关注任何'.$bbs_name);
}

}//展示用户关注的论坛



echo '</div>';