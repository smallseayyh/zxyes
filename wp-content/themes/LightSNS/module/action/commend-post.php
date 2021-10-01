<?php 
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$post_id=(int)$_POST['post_id'];
$author_id=jinsom_get_user_id_post($post_id);

if(!$user_id||!$author_id){
$data_arr['code']=0;
$data_arr['msg']='数据异常！';
header('content-type:application/json');
echo json_encode($data_arr);	
exit();	
}

//全局置顶
if($_POST['type']=='sticky-all'){
if(jinsom_is_admin($user_id)){
$sticky_arr=get_option('sticky_posts');
if(!$sticky_arr){$sticky_arr=array();}
if(in_array($post_id,$sticky_arr)){

// $sticky_arr=array_flip($sticky_arr);
// unset($sticky_arr[$post_id]);

if(count($sticky_arr)==1){
delete_option('sticky_posts');
}else{

$key=array_search($post_id,$sticky_arr);
unset($sticky_arr[$key]);

update_option('sticky_posts',$sticky_arr);//更新置顶数据
}



if($user_id!=$author_id){
jinsom_im_tips($author_id,__('你发布的内容已经被取消全局置顶','jinsom').'<br>操作用户：'.jinsom_nickname($user_id).'<br><a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}
$data_arr['code']=2;
$data_arr['html']='全局置顶';
$data_arr['msg']='已经取消全局置顶！';	
}else{
array_push($sticky_arr,$post_id);
update_option('sticky_posts',$sticky_arr);//更新置顶数据
if($user_id!=$author_id){
jinsom_im_tips($author_id,__('你发布的内容已经被全局置顶','jinsom').'<br>操作用户：'.jinsom_nickname($user_id).'<br><a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}
$data_arr['code']=1;
$data_arr['html']='取消全局';
$data_arr['msg']='全局置顶成功！';	
}

}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';	
}

}



//推荐动态
if($_POST['type']=='commend-post'){
if(jinsom_is_admin($user_id)){
$commend_post_id=get_post_meta($post_id,'jinsom_commend',true);
if($commend_post_id){
delete_post_meta($post_id,'jinsom_commend');
if($user_id!=$author_id){
jinsom_im_tips($author_id,__('你发布的内容已经被取消推荐','jinsom').'<br>操作用户：'.jinsom_nickname($user_id).'<br><a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}
$data_arr['code']=2;
$data_arr['html']='推荐';
$data_arr['msg']='已经取消推荐！';		
}else{
update_post_meta($post_id,'jinsom_commend','post');
if($user_id!=$author_id){
jinsom_im_tips($author_id,__('你发布的内容已经被推荐','jinsom').'<br>操作用户：'.jinsom_nickname($user_id).'<br><a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}
$data_arr['code']=1;
$data_arr['html']='取消推荐';
$data_arr['msg']='推荐成功！';	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';	
}
}



//板块置顶
if($_POST['type']=='sticky-bbs'){
$bbs_id=$_POST['bbs_id'];
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
if(empty($admin_a)){$admin_a=9909999;}
if(empty($admin_b)){$admin_b=9909999;}
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
if($is_bbs_admin){

$sticky_post_id=get_post_meta($post_id,'jinsom_sticky',true);
if($sticky_post_id){
delete_post_meta($post_id,'jinsom_sticky');
if($user_id!=$author_id){
jinsom_im_tips($author_id,__('你发布的内容已经被取消板块置顶','jinsom').'<br>操作用户：'.jinsom_nickname($user_id).'<br><a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}
$data_arr['code']=2;
$data_arr['html']='板块置顶';
$data_arr['msg']='已经取消板块置顶！';		
}else{
update_post_meta($post_id,'jinsom_sticky','bbs');
if($user_id!=$author_id){
jinsom_im_tips($author_id,__('你发布的内容已经被置顶到板块','jinsom').'<br>操作用户：'.jinsom_nickname($user_id).'<br><a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}
$data_arr['code']=1;
$data_arr['html']='取消版顶';
$data_arr['msg']='板块置顶成功！';	
}

}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';	
}
}




//加精帖子
if($_POST['type']=='commend-bbs'){
$bbs_id=(int)$_POST['bbs_id'];
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
if(empty($admin_a)){$admin_a=9909999;}
if(empty($admin_b)){$admin_b=9909999;}
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
if($is_bbs_admin){

$commend_post_id=get_post_meta($post_id,'jinsom_commend',true);
if($commend_post_id){
delete_post_meta($post_id,'jinsom_commend');
if($user_id!=$author_id){
jinsom_im_tips($author_id,__('你发布的内容已经被取消加精','jinsom').'<br>操作用户：'.jinsom_nickname($user_id).'<br><a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}
$data_arr['code']=2;
$data_arr['html']='加精';
$data_arr['msg']='已经取消加精！';		
}else{
update_post_meta($post_id,'jinsom_commend','bbs');
if($user_id!=$author_id){
jinsom_im_tips($author_id,__('你发布的内容已经被加精','jinsom').'<br>操作用户：'.jinsom_nickname($user_id).'<br><a href="'.get_the_permalink($post_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里查看--<<</a>');
}
$data_arr['code']=1;
$data_arr['html']='取消加精';
$data_arr['msg']='加精成功！';	
}

}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';	
}
}


//个人主页置顶
if($_POST['type']=='sticky-member'){
if($user_id==$author_id){

if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=3;
$data_arr['msg']='该功能仅限VIP用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);	
exit();	
}

$sticky_post_id=get_user_meta($user_id,'sticky',true);
if($sticky_post_id==$post_id){//取消置顶
delete_user_meta($user_id,'sticky');
$data_arr['code']=2;
$data_arr['html']='主页置顶';
$data_arr['msg']='已经取消个人主页置顶！';	
}else{
update_user_meta($user_id,'sticky',$post_id);
$data_arr['code']=1;
$data_arr['html']='取消主页';
$data_arr['msg']='个人主页置顶成功！';	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='你只能置顶你发布的内容！';	
}

}



header('content-type:application/json');
echo json_encode($data_arr);