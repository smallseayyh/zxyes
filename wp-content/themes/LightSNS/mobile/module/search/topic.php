<?php 
//搜索话题
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$keyword=$_POST['key'];

$args=array(
'number'=>30,
'taxonomy'=>'post_tag',//话题
'search'=>$keyword,
'hide_empty'=>false,
'orderby' =>'count'
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
// $data_arr['content']=jinsom_empty();	
}
$new='<li class="new search" onclick="jinsom_publish_topic_selete(this)" data="'.$keyword.'"><div class="name">#'.$keyword.'#</div><div class="desc">点击使用此'.jinsom_get_option('jinsom_topic_name').'</div></li>';
$data_arr['new']=$new;

header('content-type:application/json');
echo json_encode($data_arr);