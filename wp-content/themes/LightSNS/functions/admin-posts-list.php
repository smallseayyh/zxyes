<?php 

//文章列表
function jinsom_manage_posts_columns($value){
$newcolumns=array(
'cb' => '<input id="cb-select-all-1" type="checkbox">',
'title' => '标题',
'post_id' => 'ID',
'nickname' =>'作者',
'categories' =>'所属论坛',
'type' => '类型',
'date' => '日期',
);
return $newcolumns;
}
add_filter('manage_posts_columns','jinsom_manage_posts_columns');

//页面列表
function jinsom_manage_pages_columns($value){
$newcolumns=array(
'cb' => '<input id="cb-select-all-1" type="checkbox">',
'title' => '标题',
'post_id' => 'ID',
'nickname' =>'作者',
'date' => '日期',
);
return $newcolumns;
}
add_filter('manage_pages_columns','jinsom_manage_pages_columns');

//筛选内容
function jinsom_manage_posts_custom_column($column,$post_id){
if($column==='post_id'){
echo $post_id;
}else if($column==='nickname'){
echo '<a href="'.get_author_posts_url(jinsom_get_user_id_post($post_id)).'" target="_blank">'.jinsom_nickname(jinsom_get_user_id_post($post_id)).'</a>';	
}else if($column==='type'){
if(is_bbs_post($post_id)){
echo '论坛帖子';
}else{
$post_type=get_post_meta($post_id,'post_type',true);
if($post_type=='single'){
echo '文章';
}else if($post_type=='music'){
echo '音乐';		
}else if($post_type=='video'){
echo '视频';
}else{
echo '动态';
}
}
}
}
add_action('manage_posts_custom_column','jinsom_manage_posts_custom_column',5,2);
add_action('manage_pages_custom_column','jinsom_manage_posts_custom_column',5,2);