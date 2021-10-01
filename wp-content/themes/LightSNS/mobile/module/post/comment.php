<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$page=$_POST['page'];
$post_id=$_POST['post_id'];
$type=$_POST['type'];
if(!isset($_POST['page'])){$page=1;}

$comment_not_arr=array();
$comment_up_id=(int)get_post_meta($post_id,'up-comment',true);//置顶的评论ID
if($comment_up_id){
array_push($comment_not_arr,$comment_up_id);
}
$answer_adopt=(int)get_post_meta($post_id,'answer_adopt',true);//采纳楼层
if($answer_adopt){
array_push($comment_not_arr,$answer_adopt);
}

if($page==1&&$_COOKIE["comment_author"]!='author'){
if($comment_up_id){
$comments=get_comments('comment__in='.$comment_up_id);
require($require_url.'/mobile/templates/post/comment-templates.php');
}


if($answer_adopt){
$comments=get_comments('comment__in='.$answer_adopt);
require($require_url.'/mobile/templates/post/comment-templates.php');
}

}


$number=10;
$offset=($page-1)*$number;
$author_id=jinsom_get_post_author_id($post_id);

$args=array(
'status' =>'approve',
'type'=>'comment',
'orderby'=>'comment_ID',
'offset'=>$offset,
'number'=>$number,
'post_id'=>$post_id,
'no_found_rows'=>false,
'comment__not_in'=>$comment_not_arr,
'parent'=>0
);

if($type=='bbs'){
$bbs_id=$_POST['bbs_id'];
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
if (is_user_logged_in()) {
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}

//帖子
$args['order']='ASC';

}else{
//动态类
$args['order']='DESC';
}

if(isset($_COOKIE["comment_author"])&&$_COOKIE["comment_author"]=='author'){
$args['user_id']=$author_id;
}

if(isset($_COOKIE["comment_sort"])){
if($type=='bbs'){
if($_COOKIE["comment_sort"]=='DESC'){
$args['order']='ASC';
}else{
$args['order']='DESC';	
}
}else{
$args['order']=$_COOKIE["comment_sort"];
}
}

$comments=get_comments($args);
if($comments){
$comments_number=get_comments_number($post_id);
$floor=$comments_number-$offset;
require($require_url.'/mobile/templates/post/comment-templates.php');
}else{
echo 0;
}
