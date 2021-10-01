<?php $type=$_GET['type'];//用于判断是否帖子 

$comment_sort=$_COOKIE['comment_sort'];
if($comment_sort=='ASC'){
$comment_sort_name=__('倒序','jinsom');
}else{
$comment_sort_name=__('正序','jinsom');
}

?>
<div class="jinsom-single-comment jinsom-single-comment-<?php echo $post_id;?>">
<div class="header clear">
<li class="<?php if(!isset($_COOKIE["comment_author"])||$_COOKIE["comment_author"]=='all'){echo 'on';}?>" data="all"><?php _e('全部','jinsom');?> <?php echo $comments_number;?></li>
<li class="author <?php if(isset($_COOKIE["comment_author"])&&$_COOKIE["comment_author"]=='author'){echo 'on';}?>" data="author"><?php _e('只看作者','jinsom');?></li>
<div class="sort" onclick="jinsom_comment_sort()"><span><?php echo $comment_sort_name;?></span> <i class="jinsom-icon jinsom-paixu2"></i></div>
</div>
<div class="jinsom-single-comment-list jinsom-single-comment-list-<?php echo $post_id;?>" post_id='<?php echo $post_id;?>' type="<?php echo $type;?>" bbs_id="<?php echo $bbs_id;?>">
<?php 
$comment_not_arr=array();
$current_comment_id='';

//回复内容里面的 把当前评论置顶
if(isset($_GET['comment_id'])&&is_numeric($_GET['comment_id'])){
$current_comment_id=(int)$_GET['comment_id'];
array_push($comment_not_arr,$current_comment_id);
$comments=get_comments('comment__in='.$current_comment_id);
require($require_url.'/mobile/templates/post/comment-templates.php');
}


$comment_up_id=(int)get_post_meta($post_id,'up-comment',true);//置顶的评论ID
if($comment_up_id&&$comment_up_id!=$current_comment_id){
array_push($comment_not_arr,$comment_up_id);
$comments=get_comments('comment__in='.$comment_up_id);
require($require_url.'/mobile/templates/post/comment-templates.php');
}

$answer_adopt=(int)get_post_meta($post_id,'answer_adopt',true);//采纳楼层
if($answer_adopt&&$answer_adopt!=$current_comment_id){
array_push($comment_not_arr,$answer_adopt);
$comments=get_comments('comment__in='.$answer_adopt);
require($require_url.'/mobile/templates/post/comment-templates.php');
}

$number=10;
$args=array(
'status' =>'approve',
'type'=>'comment',
'orderby'=>'comment_ID',
'number'=>$number,
'post_id'=>$post_id,
'comment__not_in'=>$comment_not_arr,
'no_found_rows'=>false,
'parent'=>0
);

if($type=='bbs'){
$args['order']='ASC';
}else{
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
$floor=$comments_number;
if($comments){
require($require_url.'/mobile/templates/post/comment-templates.php');
}else{
if(!$comment_up_id){//不存在评论置顶
echo jinsom_empty();
}
}?>

</div>
</div>