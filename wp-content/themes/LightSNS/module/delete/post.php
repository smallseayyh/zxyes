<?php 
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
//删除动态
$user_id=$current_user->ID;

//判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if(isset($_POST['type'])&&$_POST['type']=='post'){
$post_id=(int)$_POST['post_id'];
$author_id=jinsom_get_post_author_id($post_id);
if(jinsom_is_admin_x($user_id)||$user_id==$author_id){//作者、管理人员

$delete_time=(int)jinsom_get_option('jinsom_delete_time_max');
$single_time=strtotime(get_the_time('Y-m-d H:i:s',$post_id));
if(time()-$single_time>60*60*$delete_time&&!jinsom_is_admin_x($user_id)){
$data_arr['code']=0;
$data_arr['msg']='内容已经超过删除时间，无法进行删除！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if($user_id!=$author_id){//非本人删除自己的文章
$content=jinsom_get_post_content($post_id);
$title=get_the_title($post_id);
if(!$title){
$title=strip_tags(mb_substr($content,0,25,'utf-8'));
}
jinsom_im_tips($author_id,__('发布的内容已经被删除','jinsom').'<br>标题：《'.$title.'》<br>删除者：'.jinsom_nickname($user_id));
}
wp_delete_post($post_id);
// global $wpdb;
// $table_name = $wpdb->prefix . 'jin_like';
// $wpdb->query( " DELETE FROM $table_name WHERE post_id= $post_id ; " );//删除对应的喜欢
// $table_name_a = $wpdb->prefix . 'jin_notice';
// $wpdb->query( " DELETE FROM $table_name_a WHERE post_id= $post_id ; " );//删除对应的提醒
// $table_name_b = $wpdb->prefix . 'jin_now';
// $wpdb->query( " DELETE FROM $table_name_b WHERE post_id= $post_id ; " );//删除对应的实时动态

$data_arr['code']=1;
$data_arr['msg']='删除成功！';


//扣除对应的金币和经验
if(get_post_meta($post_id,'post_type',true)!='redbag'){
$publish_credit = (int)jinsom_get_option('jinsom_publish_post_credit');//每次发表动态可获得的金币
$publish_exp = (int)jinsom_get_option('jinsom_publish_post_exp');//每次发表动态可获得的经验
jinsom_update_exp($author_id,$publish_exp,'cut','内容被删除，扣除');
if($publish_credit<0){
jinsom_update_credit($author_id,$publish_credit,'add','post-delete','发布的内容被删除，返还',1,'');
}else{
jinsom_update_credit($author_id,$publish_credit,'cut','post-delete','发布的内容被删除，扣除',1,'');
}
}

}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';	
}

header('content-type:application/json');
echo json_encode($data_arr);
}


//删除帖子
if(isset($_POST['type'])&&$_POST['type']=='bbs'){
$post_id=$_POST['post_id'];
$bbs_id=$_POST['bbs_id'];

//获取大版主和管理人员
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
if(empty($admin_a)){$admin_a=9909999;}
$admin_a_arr=explode(",",$admin_a);

$author_id=jinsom_get_user_id_post($post_id);
if($user_id==$author_id||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)||jinsom_is_admin_x($user_id)){//版主、楼主、管理人员

$delete_time=(int)jinsom_get_option('jinsom_delete_time_max');
$single_time=strtotime(get_the_time('Y-m-d H:i:s',$post_id));
if(time()-$single_time>60*60*$delete_time&&!jinsom_is_admin_x($user_id)&&!jinsom_is_bbs_admin_a($user_id,$admin_a_arr)){
$data_arr['code']=0;
$data_arr['msg']='内容已经超过删除时间，无法进行删除！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if($user_id!=$author_id){//非本人删除自己的文章
jinsom_im_tips($author_id,__('发布的内容已经被删除','jinsom').'<br>标题：《'.get_the_title($post_id).'》<br>删除者：'.jinsom_nickname($user_id));
}
wp_delete_post($post_id);

global $wpdb;
$table_name = $wpdb->prefix . 'jin_like';
$wpdb->query( " DELETE FROM $table_name WHERE post_id= $post_id ; " );//删除对应的喜欢
$table_name_a = $wpdb->prefix . 'jin_notice';
$wpdb->query( " DELETE FROM $table_name_a WHERE post_id= $post_id ; " );//删除对应的提醒

//删除今日发帖量
$today_publish=(int)get_term_meta($bbs_id,'today_publish',true);
update_term_meta($bbs_id,'today_publish',intval($today_publish-1));


$data_arr['code']=1;
$data_arr['url']=get_category_link($bbs_id);
$data_arr['msg']='删除成功！';

//扣除对应的金币和经验
$publish_credit = (int)get_term_meta($bbs_id,'bbs_credit_post_number',true);//每次发帖可获得的金币
$publish_exp = (int)get_term_meta($bbs_id,'bbs_exp_post_number',true);//每次发帖可获得的经验
jinsom_update_exp($author_id,$publish_exp,'cut','帖子被删除，扣除');
if($publish_credit<0){
jinsom_update_credit($author_id,$publish_credit,'add','bbs-post-delete','发布的帖子被删除，返还',1,'');
}else{
jinsom_update_credit($author_id,$publish_credit,'cut','bbs-post-delete','发布的帖子被删除，扣除',1,''); 
}


}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';	
}

header('content-type:application/json');
echo json_encode($data_arr);
}