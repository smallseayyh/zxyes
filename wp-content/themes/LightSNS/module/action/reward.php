<?php 
//打赏
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

$reward_min=jinsom_get_option('jinsom_reward_min');
$reward_max=jinsom_get_option('jinsom_reward_max');
$user_id=$current_user->ID;
$credit_name=jinsom_get_option('jinsom_credit_name');

//=========未登录
if (!is_user_logged_in()){
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();     
}

//=========判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}

//需要绑定手机号才能使用
$bind_phone_use_for=jinsom_get_option('jinsom_bind_phone_use_for');
if($bind_phone_use_for&&in_array("reward",$bind_phone_use_for)&&!current_user_can('level_10')){
if(!get_user_meta($user_id,'phone',true)){
$data_arr['code']=2;
$data_arr['user_id']=$user_id;
$data_arr['msg']='你需要绑定手机号码才能进行操作！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}
}

//需要绑定邮箱才能使用
$bind_email_use_for=jinsom_get_option('jinsom_bind_email_use_for');
if($bind_email_use_for&&in_array("reward",$bind_email_use_for)&&!current_user_can('level_10')){
$user_info=get_userdata($user_id);
if(!$user_info->user_email){
$data_arr['code']=4;
$data_arr['user_id']=$user_id;
$data_arr['msg']='你需要绑定邮箱才能进行操作！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}
}

//使用权限
$power=jinsom_get_option('jinsom_reward_power');
if($power=='vip'){//VIP用户
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限会员用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='verify'){//认证用户
$user_verify=get_user_meta($user_id,'verify',true);
if(!$user_verify&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限认证用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='honor'){//头衔用户
$user_honor=get_user_meta($user_id,'user_honor',true);
if(!$user_honor&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限拥有头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='admin'){//管理团队
if(!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限管理团队使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='exp'){//指定经验
$user_exp=jinsom_get_user_exp($user_id);//当前用户经验
$im_exp = jinsom_get_option('jinsom_reward_power_exps');
if($user_exp<$im_exp&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限经验值大于'.$im_exp.'的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='honor_arr'){//指定头衔
if(!jinsom_is_admin($user_id)){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$honor_arr=jinsom_get_option('jinsom_reward_power_honor_arr');
$publish_power_honor_arr=explode(",",$honor_arr);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($publish_power_honor_arr,$user_honor_arr)){	
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}else if($power=='verify_arr'){//指定认证类型
if(!jinsom_is_admin($user_id)){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$verify_arr=jinsom_get_option('jinsom_reward_power_verify_arr');
$publish_power_verify_arr=explode(",",$verify_arr);
if(!in_array($user_verify_type,$publish_power_verify_arr)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定认证类型的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定认证类型的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}




if(isset($_POST['number'])){
$number=(int)$_POST['number'];
$post_id=(int)$_POST['post_id'];
$type=strip_tags($_POST['type']);
if($type=='post'){
$author_id=jinsom_get_user_id_post($post_id);
}else if($type=='live'){//直播
$live_data=get_post_meta($post_id,'video_live_page_data',true);
$author_id=$live_data['jinsom_video_live_user_id'];
}else{
$author_id=jinsom_get_comments_author_id($post_id);	
$post_id=jinsom_get_comment_post_id($post_id);
}

$ip = $_SERVER['REMOTE_ADDR'];
$credit=get_user_meta($current_user->ID,'credit',true);

if($number<=0){
$data_arr['code']=0;
$data_arr['msg']='打赏的金额不合法！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 	
}

if(($number>$reward_max||$number<$reward_min)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='只能打赏'.$reward_min.'-'.$reward_max.$credit_name.'！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}

if(!$author_id){
$data_arr['code']=0;
$data_arr['msg']='你打赏的用户不存在！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();  
}

if($user_id==$author_id){
$data_arr['code']=0;
$data_arr['msg']='你不能给自己进行打赏！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();  
}

//判断是否将对方拉黑
if(jinsom_is_blacklist($author_id,$user_id)){
$data_arr['code']=0;
$data_arr['msg']='打赏失败！对方已经将你拉进黑名单！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if($credit>=$number){

if(jinsom_get_option('jinsom_reward_post_show_comment_on_off')){
$time = current_time('mysql');

if($type=='post'){
$content='<m class="reward"><span class="jinsom-redbag-icon"></span>打赏了'.$number.$credit_name.'</m>';	
}else{
$content='<m class="reward"><span class="jinsom-redbag-icon"></span>打赏了<jin class="jinsom-post-at" type="at" user_id="'.$author_id.'" data="'.jinsom_userlink($author_id).'" onclick="jinsom_post_link(this);">@'.get_user_meta($author_id,'nickname',true).'</jin>'.$number.$credit_name.'</m>';
}

$data = array(
'comment_post_ID' =>$post_id,
'comment_content' => $content,
'user_id' => $user_id,
'comment_date' => $time,
'comment_author_IP'=>$ip,
'comment_approved' => 1,
);
$comment_id=wp_insert_comment($data); 


update_comment_meta($comment_id,'comment_type','reward');

//更新评论来自
if(wp_is_mobile()){
update_comment_meta($comment_id,'from','mobile');
}else{
update_comment_meta($comment_id,'from','pc');  
}

update_post_meta($post_id, 'last_comment_time', time());//插入最后回复字段	

update_comment_meta($comment_id,'reward',1);//更新评论为打赏类型
update_comment_meta($comment_id,'delete',1);//禁止删除

//更新楼层数
if(get_post_meta($post_id,'bbs_floor',true)){//判断是否存在帖子总楼层数
$bbs_floor=get_post_meta($post_id,'bbs_floor',true);//获取目前的楼层数
update_comment_meta($comment_id,'comment_floor',$bbs_floor+1);//写入当前评论的楼层
update_post_meta($post_id,'bbs_floor',$bbs_floor+1);//总楼层累加
}else{
$comment_floor_args = array(
'post_id' => $post_id,
'parent'=> 0,
'status' =>'approve',
'count'=> true,
);
$comment_number = get_comments($comment_floor_args);//获取所有一级楼层数量  
update_comment_meta($comment_id,'comment_floor',$comment_number+1);//写入当前评论的楼层
update_post_meta($post_id,'bbs_floor',$comment_number+1);//同步楼层数
}

}//是否开启评论打赏

$user_reward=(int)get_user_meta($user_id,'reward',true);
update_user_meta($user_id,'reward',$user_reward+$number);//更新用户累计打赏值
jinsom_add_tips($author_id,$user_id,$post_id,'reward','打赏了你',$number);//添加提醒记录
jinsom_update_credit($user_id,$number,'cut','reward','你打赏了别人',1,'');

//手续费
$reward_poundage=jinsom_get_option('jinsom_reward_poundage');
if($reward_poundage){
$number=$number-(int)($number*($reward_poundage)/100);
}

jinsom_update_credit($author_id,$number,'add','reward','别人打赏了你['.$user_id.']',1,'');


//记录打赏次数
$reward_times=(int)get_user_meta($user_id,'reward_times',true);
update_user_meta($user_id,'reward_times',($reward_times+1));

$data_arr['code']=1;
$data_arr['msg']='打赏成功！'; 
$data_arr['post_url']=get_the_permalink($post_id); 


//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$user_id','$post_id','reward','$time','$author_id')");



}else{
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！';
}

}


header('content-type:application/json');
echo json_encode($data_arr);