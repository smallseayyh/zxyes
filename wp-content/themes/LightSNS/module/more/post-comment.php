<?php 
require( '../../../../../wp-load.php' );
//加载更多评论==普通动态
if(isset($_POST['page'])){
$user_id=$current_user->ID;
$page=(int)$_POST['page'];
$number=20;
$offset=($page-1)*$number;
$post_id=(int)$_POST['post_id'];
$author_id=jinsom_get_user_id_post($post_id);
$com_count_a=get_comments_number($post_id)-$offset;//评论总数

$args = array(
'status'=>'approve',
'type' => 'comment',
'karma'=>0,
'no_found_rows' =>false,
'number' => $number,
'offset' => $offset,
'post_id' => $post_id
);
$comment_data = get_comments($args);
if(!empty($comment_data)){
foreach ($comment_data as $comment_datas) {
// $com_count_a=$com_count_a-$offset;
require('../stencil/comments-templates.php');//评论模版
}
}else{
echo 0;
}

}