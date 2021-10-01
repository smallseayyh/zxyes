<?php 
//获取热门话题
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


$args=array(
'number'=>30,
'taxonomy'=>'post_tag',//话题
'meta_key'=>'topic_views',
'orderby'=>'meta_value_num',
'hide_empty'=>false,
'order'=>'DESC'
);
$tag_arr=get_terms($args);
if(!empty($tag_arr)){
$data_arr['code']=1;
$data_arr['data']=array();
foreach ($tag_arr as $tag) {
$topic_arr=array();
$topic_id=$tag->term_id;
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
$topic_arr['name']=$tag->name;
$topic_arr['avatar']=jinsom_get_bbs_avatar($topic_id,1);
$topic_arr['hot']=jinsom_views_show($topic_views);
array_push($data_arr['data'],$topic_arr);
}
}else{
$data_arr['code']=0;
}

header('content-type:application/json');
echo json_encode($data_arr);