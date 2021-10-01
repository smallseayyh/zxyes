<?php
require( '../../../../../wp-load.php' );

//其他类型的消息
// if(isset($_POST['item_tips'])){
if (is_user_logged_in()) {
$get_post_tips_data=jinsom_get_post_tips_data(30);
$data_arr['item']['data']=array();
if($get_post_tips_data){
$data_arr['item']['code']=1;
foreach ($get_post_tips_data as $get_post_tips_datas) {
$post_id=$get_post_tips_datas->post_id;
$user_id=$get_post_tips_datas->user_id;
$action=$get_post_tips_datas->notice_content;
$time=$get_post_tips_datas->notice_time;
$type=$get_post_tips_datas->notice_type;
$status=$get_post_tips_datas->status;
if(!$status){$status='<m></m>';}else{$status='';}


$list['type']=$type;
$list['action']=$action;
$list['status']=$status;
$list['user_id']=$user_id;
$list['post_id']=$post_id;
$list['user_name']=jinsom_nickname($user_id);
if($type=='reg'||$type=='delete-post'||$type=='secret'){
$list['post_link']='#';	
}else if($type=='transfer'||$type=='gift'||$type=='blacklist_bail'){
if(!$post_id){
$list['post_link']=jinsom_userlink($user_id);
}else{
$list['post_link']=get_the_permalink($post_id);	
}	
}else if($type=='cash'||$type=='cash-refuse'){
$list['post_link']='javascript:jinsom_mywallet_form(0);';	
}else if($type=='order-send'){//发货
$list['post_link']='javascript:jinsom_goods_order_form();';	
}else if($type=='bbs-admin'||$type=='visit_bbs'){
$list['post_link']=get_category_link($post_id);	
}else{
$list['post_link']=get_the_permalink($post_id);	
}
$list['author_link']=jinsom_userlink($user_id);
$list['time']=jinsom_timeago($time);

if($type=='comment'||$type=='aite'){
if(get_option('permalink_structure')){//固定链接
$list['post_link']=$list['post_link'].'?comment_id='.$get_post_tips_datas->remark;
}else{
$list['post_link']=$list['post_link'].'&comment_id='.$get_post_tips_datas->remark;	
}
if($type=='comment'){
$list['action']='评论了你';
}
}


array_push($data_arr['item']['data'],$list);

// array_push($data_arr['item'],$list);

} 
}else{
$data_arr['item']['code']=0;
}


$get_like_tips_data=jinsom_get_like_tips_data(30);
$data_arr['like']['data']=array();
if($get_like_tips_data){
$data_arr['like']['code']=1;
foreach ($get_like_tips_data as $get_like_tips_datas) {
$post_id=$get_like_tips_datas->post_id;
$user_id=$get_like_tips_datas->user_id;
$action=$get_like_tips_datas->notice_content;
$time=$get_like_tips_datas->notice_time;
$status=$get_like_tips_datas->status;
if(!$status){$status='<m></m>';}else{$status='';}
$type=$get_like_tips_datas->notice_type;

$list['action']=$action;
$list['status']=$status;
$list['user_id']=$user_id;
$list['post_id']=$post_id;
$list['user_name']=jinsom_nickname($user_id);
$list['post_link']=get_the_permalink($post_id);
$list['author_link']=jinsom_userlink($user_id);
$list['time']=jinsom_timeago($time);

if($type=='comment-up'){
if(get_option('permalink_structure')){//固定链接
$list['post_link']=$list['post_link'].'?comment_id='.$get_like_tips_datas->remark;
}else{
$list['post_link']=$list['post_link'].'&comment_id='.$get_like_tips_datas->remark;	
}
if($type=='comment-up'){
$list['action']='赞了你的评论';
}
}


array_push($data_arr['like']['data'],$list);
} 
}else{
$data_arr['like']['code']=0;
}



$get_follow_tips_data=jinsom_get_follow_tips_data(30);
$data_arr['follow']['data']=array();
if($get_follow_tips_data){
$data_arr['follow']['code']=1;
foreach ($get_follow_tips_data as $get_follow_tips_datas) {
$post_id=$get_follow_tips_datas->post_id;
$user_id=$get_follow_tips_datas->user_id;
$time=$get_follow_tips_datas->notice_time;
$action=$get_follow_tips_datas->notice_content;
$status=$get_follow_tips_datas->status;
if(!$status){$status='<m></m>';}else{$status='';}

$list['action']=$action;
$list['status']=$status;
$list['user_id']=$user_id;
$list['post_id']=$post_id;
$list['user_name']=jinsom_nickname($user_id);
$list['post_link']=get_the_permalink($post_id);
$list['author_link']=jinsom_userlink($user_id);
$list['time']=jinsom_timeago($time);
array_push($data_arr['follow']['data'],$list);

} 
}else{
$data_arr['follow']['code']=0;
}


}
// }

//设置所有消息为已读
jinsom_set_notice();


header('content-type:application/json');
echo json_encode($data_arr);