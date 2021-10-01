<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$post_id=(int)$_POST['post_id'];
$type=strip_tags($_POST['type']);
$author_id=jinsom_get_user_id_post($post_id);
$post_power=get_post_meta($post_id,'post_power',true);

if(is_bbs_post($post_id)){
$hide_content=get_post_meta($post_id,'post_price_cnt',true);//帖子隐藏内容
}else{
$hide_content=jinsom_autolink(get_post_meta($post_id,'pay_cnt',true));//隐藏内容
}

if($type=='pay'){
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
$data_arr['code']=0;
}else{
$data_arr['code']=1;
$data_arr['content']=do_shortcode(convert_smilies(wpautop($hide_content)));
}
}else if($type=='comment'){
if($user_id!=$author_id&&!jinsom_is_comment($user_id,$post_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;	
}else{
$data_arr['code']=1;
$data_arr['content']=do_shortcode(convert_smilies(wpautop($hide_content)));	
} 	
}



header('content-type:application/json');
echo json_encode($data_arr);
