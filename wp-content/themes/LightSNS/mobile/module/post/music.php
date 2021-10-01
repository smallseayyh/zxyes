<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$post_id=$_POST['post_id'];
$music_url=get_post_meta($post_id,'music_url',true);
$post_data = get_post($post_id, ARRAY_A);
$author_id=$post_data['post_author'];
$content=convert_smilies(wpautop(jinsom_autolink($post_data['post_content'])));
$data_arr['title']=$post_data['post_title'];
$data_arr['content']=$content;
$data_arr['music_url']=$music_url;
$data_arr['comment_number']=get_comments_number($post_id);
$data_arr['author_id']=$author_id;
$data_arr['is_like']=jinsom_is_like_post($post_id,$user_id);


header('content-type:application/json');
echo json_encode($data_arr);
