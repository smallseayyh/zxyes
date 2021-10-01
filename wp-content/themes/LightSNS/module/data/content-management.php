<?php 
//内容管理--分页
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$page=$_POST['page'];
$number=$_POST['number'];
$offset=($page-1)*$number;
$type=$_POST['type'];


//全部内容
if($type=='all'){

$args_all = array( 
'post_status' => array('publish','pending','draft'),
'showposts' => 8,
'offset' => $offset,
'no_found_rows'=>true,
'ignore_sticky_posts'=>1
);
if(!jinsom_is_admin($user_id)&&get_user_meta($user_id,'user_power',true)!=5){
$args_all['author']=$user_id;
}

query_posts($args_all);
if(have_posts()){
while (have_posts()):the_post();
if(get_the_title()){
$title=get_the_title();
}else{
$title=mb_substr(strip_tags(get_the_content()),0,40,'utf-8');
}
$status=get_post_status();
if($status=='publish'){
$status='<font style="color:#5fb878">已发表</font>';
}else if($status=='pending'){
$status='<font style="color:#2196F3">审核中</font>';	
}else{
$status='<font style="color:#f00">被驳回</font>';
}

echo '<li><span onclick="jinsom_post_link(this)" data="'.get_the_permalink().'">'.$title.'</span><span>'.$status.'</span><span>'.jinsom_timeago(get_the_time('Y-m-d H:i:s')).'</span></li>';
endwhile;
}else{
echo jinsom_empty();
}
}


//待审核的内容
if($type=='pending'){

$args_pending = array( 
'post_status' => array('pending'),
'showposts' => 8,
'offset' => $offset,
'no_found_rows'=>true,
'ignore_sticky_posts'=>1
);
if(!jinsom_is_admin($user_id)&&get_user_meta($user_id,'user_power',true)!=5){
$args_pending['author']=$user_id;
}

query_posts($args_pending);
if(have_posts()){
while (have_posts()):the_post();
$post_id=get_the_ID();
if(get_the_title()){
$title=get_the_title();
}else{
$title=mb_substr(strip_tags(get_the_content()),0,40,'utf-8');
}

if(jinsom_is_admin($user_id)||get_user_meta($user_id,'user_power',true)==5){
$do='
<span>
<m onclick="jinsom_content_management_agree('.$post_id.',this)" style="color:#5fb878">通过</m>
<m onclick="jinsom_content_management_refuse('.$post_id.',0,2,this)" style="color:#f00">驳回</m>
</span>';
}else{
$do='<span><m onclick="jinsom_content_management_pending_refuse('.$post_id.',this)" style="color:#f00">取消审核</m></span>';
}

echo '<li><span onclick="jinsom_post_link(this)" data="'.get_the_permalink().'">'.$title.'</span><span>'.jinsom_timeago(get_the_time('Y-m-d H:i:s')).'</span>'.$do.'</li>';
endwhile;
}else{
echo jinsom_empty();
}

}


//被驳回的内容
if($type=='draft'){

$args_draft = array( 
'post_status' => array('draft'),
'showposts' => 8,
'offset' => $offset,
'no_found_rows'=>true,
'ignore_sticky_posts'=>1
);
if(!jinsom_is_admin($user_id)&&get_user_meta($user_id,'user_power',true)!=5){
$args_draft['author']=$user_id;
}

query_posts($args_draft);
if(have_posts()){
while (have_posts()):the_post();
$post_id=get_the_ID();
if(get_the_title()){
$title=get_the_title();
}else{
$title=mb_substr(strip_tags(get_the_content()),0,40,'utf-8');
}

echo '<li><span onclick="jinsom_post_link(this)" data="'.get_the_permalink().'">'.$title.'</span><span>'.jinsom_timeago(get_the_time('Y-m-d H:i:s')).'</span><span><m onclick="jinsom_content_management_reason_form('.$post_id.')" style="color:#f00">点击查看</m></span></li>';
endwhile;
}else{
echo jinsom_empty();
}

}