<?php 
require( '../../../../../wp-load.php' );
//加载更多评论===帖子

if(isset($_POST['page'])){
$user_id=$current_user->ID;
$page=$_POST['page'];
if(!$page){$page=1;}
$number=$_POST['number'];
$offset = ($page-1)*$number;
$post_id=$_POST['post_id'];
$author_id=jinsom_get_user_id_post($post_id);

$bbs_id=$_POST['bbs_id'];
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
if(empty($admin_a)){$admin_a=9909999;}
if(empty($admin_b)){$admin_b=9909999;}
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;


$comment_not_arr=array();
$comment_up_id=(int)get_post_meta($post_id,'up-comment',true);//置顶的评论ID
if($comment_up_id){
array_push($comment_not_arr,$comment_up_id);
}
$answer_adopt=(int)get_post_meta($post_id,'answer_adopt',true);//采纳楼层
if($answer_adopt){
array_push($comment_not_arr,$answer_adopt);
}

if($page==1){
if($comment_up_id){
$comments=get_comments('comment__in='.$comment_up_id);
require(get_template_directory().'/post/bbs-comment-1.php');//引人回帖模版
}


if($answer_adopt){
$comments=get_comments('comment__in='.$answer_adopt);
require(get_template_directory().'/post/bbs-comment-1.php');//引人回帖模版
}

}


$args = array(
'number' => $number,
'offset' => $offset ,
'post_id' => $post_id, 
'comment__not_in' =>$comment_not_arr,//不显示已经采纳的楼层
'status' =>'approve',
'parent'=>0,
'orderby' => 'comment_ID',
'order' => 'ASC',
);
$comments = get_comments($args);

if($comments){
require(get_template_directory().'/post/bbs-comment-1.php');//引人回帖模版
}else{//没有评论
echo 0;
}


}