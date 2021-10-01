<?php 
//获取隐藏内容
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$type=strip_tags($_POST['type']);
$post_id=(int)$_POST['post_id'];
$author_id=jinsom_get_post_author_id($post_id);
if($type=='pay'){
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
$data_arr['code']=0;
}else{
$data_arr['code']=1;

//隐藏内容
if(is_bbs_post($post_id)){
$hidden=get_post_meta($post_id,'post_price_cnt',true);
}else{
$hidden=get_post_meta($post_id,'pay_cnt',true);
$post_type=get_post_meta($post_id,'post_type',true);
if($post_type=='words'){
$post_img=get_post_meta($post_id,'post_img',true);
if($post_img){
$data_arr['img']=jinsom_words_img($post_id,1,99);	
}
}else if($post_type=='video'){
$data_arr['video_url']=get_post_meta($post_id,'video_url',true);
$data_arr['video_cover']=jinsom_video_cover($post_id);
}
}
$data_arr['hidden']=$hidden;
}


}


header('content-type:application/json');
echo json_encode($data_arr);