<?php
//内容管理
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$type=$_POST['type'];
$post_id=$_POST['post_id'];
$bbs_id=$_POST['bbs_id'];
$author_id=jinsom_get_post_author_id($post_id);

if($type=='pending_refuse'||$type=='read_reason'){

//判断是否登录
if (!is_user_logged_in()){ 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


}else{

if($bbs_id){//如果是帖子

$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
if(empty($admin_a)){$admin_a=9909999;}
if(empty($admin_b)){$admin_b=9909999;}
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);

$is_bbs_admin=(jinsom_is_admin_x($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr)||get_user_meta($user_id,'user_power',true)==5)&&is_user_logged_in()?1:0;

if(!$is_bbs_admin){
$data_arr['code']=0;
$data_arr['msg']='你没有权限操作！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


}else{

//判断是否管理团队
if(!jinsom_is_admin_x($user_id)&&get_user_meta($user_id,'user_power',true)!=5){
$data_arr['code']=0;
$data_arr['msg']='你没有权限操作！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

}

}

//审核通过
if($type=='agree'){
$post_data=array();
$post_data['ID']=$post_id;
$post_data['post_status']='publish';
$post_data['post_date']=date('Y-m-d H:i:s');
$status=wp_update_post($post_data);
if($status){


if(is_bbs_post($post_id)){//如果是帖子
$bbs_id=jinsom_get_post_bbs_id($post_id);
$publish_credit=(int)get_term_meta($bbs_id,'bbs_credit_post_number',true);//论坛发帖子可获得的金币
$publish_exp=(int)get_term_meta($bbs_id,'bbs_exp_post_number',true);//论坛发帖子可获得的经验
}else{
$publish_credit = (int)jinsom_get_option('jinsom_publish_post_credit');//每次发表动态可获得的金币
$publish_exp = (int)jinsom_get_option('jinsom_publish_post_exp');//每次发表动态可获得的经验
}


if($publish_credit>0){
jinsom_update_credit($author_id,$publish_credit,'add','publish-bbs-post','发布内容',1,''); 
jinsom_update_exp($author_id,$publish_exp,'add','发布内容');
}else if($publish_credit<0){
jinsom_update_credit($author_id,$publish_credit,'cut','publish-bbs-post','发布内容，扣除',1,''); 
if($publish_exp>0){
jinsom_update_exp($author_id,$publish_exp,'add','发布内容');	
}
}else{//金币等于0
if($publish_exp>0){
jinsom_update_exp($author_id,$publish_exp,'add','发布内容');	
}
}






if($user_id!=$author_id){
jinsom_im_tips($author_id,__('你发布的内容已经审核通过','jinsom').'<br><a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}

$data_arr['code']=1;
$data_arr['msg']='审核已通过！';
}else{
$data_arr['code']=0;
$data_arr['msg']='审核失败！';
}
}

//驳回
if($type=='refuse'){
$reason=strip_tags($_POST['reason']);
if(!$reason){
$data_arr['code']=0;
$data_arr['msg']='请输入驳回原因！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

$post_data=array();
$post_data['ID']=$post_id;
$post_data['post_status']='draft';
$post_data['post_content_filtered']=$reason;
$status=wp_update_post($post_data);
if($status){
$where=strip_tags($_POST['where']);
if(!is_numeric($where)){
$data_arr['code']=0;
$data_arr['msg']='数据异常！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
if($where==1){//如果已经发布再进行驳回则扣除奖励
if(is_bbs_post($post_id)){//如果是帖子

//扣除/返还对应的金币和经验
$publish_credit=(int)get_term_meta($bbs_id,'bbs_credit_post_number',true);//每次发帖可获得的金币
$publish_exp=(int)get_term_meta($bbs_id,'bbs_exp_post_number',true);//每次发帖可获得的经验
jinsom_update_exp($author_id,$publish_exp,'cut','帖子被驳回，扣除');
if($publish_credit<0){
jinsom_update_credit($author_id,$publish_credit,'add','bbs-post-refuse','发布的帖子被驳回，返还',1,'');
}else{
jinsom_update_credit($author_id,$publish_credit,'cut','bbs-post-refuse','发布的帖子被驳回，扣除',1,''); 
}
}else{//动态、音乐、文章、视频

//扣除/返对应的金币和经验
$publish_credit=(int)jinsom_get_option('jinsom_publish_post_credit');//每次发表动态可获得的金币
$publish_exp=(int)jinsom_get_option('jinsom_publish_post_exp');//每次发表动态可获得的经验
jinsom_update_exp($author_id,$publish_exp,'cut','内容被驳回，扣除');
if($publish_credit<0){
jinsom_update_credit($author_id,$publish_credit,'add','post-refuse','发布的内容被驳回，返还',1,'');
}else{
jinsom_update_credit($author_id,$publish_credit,'cut','post-refuse','发布的内容被驳回，扣除',1,'');
}


}
}


if($user_id!=$author_id){
jinsom_im_tips($author_id,__('你发布的内容已经被驳回','jinsom').'<br>操作用户：'.jinsom_nickname($user_id).'<br>驳回原因：'.$reason.'<br><a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}
if($bbs_id){//如果是帖子
$data_arr['url']=get_category_link($bbs_id);
}
$data_arr['code']=1;
$data_arr['msg']='内容已驳回！';
}else{
$data_arr['code']=0;
$data_arr['msg']='驳回失败！';
}
}


//取消审核
if($type=='pending_refuse'){
$reason='自己手动操作取消审核';
$post_data=array();
$post_data['ID']=$post_id;
$post_data['post_status']='draft';
$post_data['post_content_filtered']=$reason;
$status=wp_update_post($post_data);
if($status){
$data_arr['code']=1;
$data_arr['msg']='已取消审核！';
}else{
$data_arr['code']=0;
$data_arr['msg']='取消失败！';
}
}


//查看驳回原因
if($type=='read_reason'){
echo '<div class="jinsom-content-management-reason-form">
'.get_post($post_id)->post_content_filtered.'
</div>';
}


if($type!='read_reason'){
header('content-type:application/json');
echo json_encode($data_arr);
}