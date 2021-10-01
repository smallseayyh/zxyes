<?php 
//编辑帖子
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

$user_id = $current_user->ID;
$post_id=$_POST['post_id'];
$bbs_id=$_POST['bbs_id'];
$author_id=jinsom_get_user_id_post($post_id);
$credit_name=jinsom_get_option('jinsom_credit_name');

//判断是否登录
if (!is_user_logged_in()){ 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_a_arr=explode(",",$admin_a);
$is_bbs_admin=(jinsom_is_admin_y($user_id)||in_array($user_id,$admin_a_arr))?1:0;

if(!$is_bbs_admin&&$user_id!=$author_id){
$data_arr['code']=0;
$data_arr['msg']='你没有权限编辑！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

$bbs_child_id=$_POST['bbs_child_id'];
$credit=get_user_meta($user_id,'credit',true);
$pending=get_term_meta($bbs_id,'pending',true);//审核功能
$bbs_type=get_term_meta($bbs_id,'bbs_type',true);//类型
$publish_field=get_term_meta($bbs_id,'publish_field',true);//发布字段



//判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//需要绑定手机号才能使用
$bind_phone_use_for=jinsom_get_option('jinsom_bind_phone_use_for');
if($bind_phone_use_for&&in_array("publish",$bind_phone_use_for)&&!current_user_can('level_10')){
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
if($bind_email_use_for&&in_array("publish",$bind_email_use_for)&&!current_user_can('level_10')){
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

$edit_time=(int)jinsom_get_option('jinsom_edit_time_max');
$single_time=strtotime(get_the_time('Y-m-d H:i:s',$post_id));
if(time()-$single_time>60*60*$edit_time&&!$is_bbs_admin){
$data_arr['code']=0;
$data_arr['msg']='已经超过编辑时间，无法进行编辑！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$publish_topic_on_off = jinsom_get_option('jinsom_publish_bbs_topic_on_off');
$publish_add_topic_max = jinsom_get_option('jinsom_publish_bbs_add_topic_max');
$topic=$_POST['topic'];

if($topic==''&&$publish_topic_on_off){
$data_arr['code']=0;
$data_arr['msg']='你至少需要使用一个'.jinsom_get_option('jinsom_topic_name').'！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if($topic!=''){
$topic_arr=explode(",",$topic);
if(count($topic_arr)>$publish_add_topic_max){
$data_arr['code']=0;
$data_arr['msg']='最多只允许使用'.$publish_add_topic_max.'个'.jinsom_get_option('jinsom_topic_name').'！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}	
}





$ip = $_SERVER['REMOTE_ADDR'];
$title_max= jinsom_get_option('jinsom_bbs_title_number');
$content_max= jinsom_get_option('jinsom_bbs_content_number');



//售价范围
$price_mini=(int)jinsom_get_option('jinsom_bbs_pay_price_mini');
$price_max=(int)jinsom_get_option('jinsom_bbs_pay_price_max');

if(isset($_POST['content'])){


$title=htmlentities($_POST['title'],ENT_QUOTES,'UTF-8');
$title_number=mb_strlen($title,'utf-8');
if($title_num>$title_max){
$data_arr['code']=0;
$data_arr['msg']='标题不能超过'.$title_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}



$content=$_POST['content'];
$content_number=mb_strlen(strip_tags($content),'utf-8');


// if(jinsom_trimall(strip_tags($content))==''){
// $data_arr['code']=0;
// $data_arr['msg']='请至少输入一些文字！';
// header('content-type:application/json');
// echo json_encode($data_arr);
// exit();	
// }

if($content_number>$content_max&&!current_user_can('level_10')){
$data_arr['code']=0;
$data_arr['msg']='内容不能超过'.$content_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


//防止表情转义
$content = str_replace('&quot;','"',$content);

if(jinsom_get_option('jinsom_baidu_filter_on_off')&&!jinsom_is_admin($user_id)){

//敏感词过滤
if(in_array("title",jinsom_get_option('jinsom_baidu_filter_use_for'))){
$filter=jinsom_baidu_filter($title);
if($filter['conclusionType']==2){
$data_arr['code']=0;
$a=$filter['data'][0]['hits'][0]['words'][0];
if($a==''){$a=$filter['data'][0]['msg'];}
$data_arr['msg']='标题含有敏感词：'.$a;
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}


//敏感词过滤
if(in_array("publish",jinsom_get_option('jinsom_baidu_filter_use_for'))){
$filter=jinsom_baidu_filter(strip_tags($content));
if($filter['conclusionType']==2){
$data_arr['code']=0;
$a=$filter['data'][0]['hits'][0]['words'][0];
if($a==''){$a=$filter['data'][0]['msg'];}
$data_arr['msg']='内容含有敏感词：'.$a;
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}

}

//渲染艾特内容
$content= jinsom_at($content);
$post_type=strip_tags($_POST['post-type']);


//下载类
if($bbs_type=='download'){
$download_data=strip_tags($_POST['download_data']);
if($download_data){
$download_data_arr=explode(",",$download_data);
if($download_data_arr){
foreach ($download_data_arr as $data) {
$arr=explode("|",$data);
if(count($arr)!=3||jinsom_trimall($arr[0])==''){
$data_arr['code']=0;
$data_arr['msg']='请输入下载地址！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

}else{
$data_arr['code']=0;
$data_arr['msg']='请输入下载地址！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='请输入下载地址！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}

//自定义字段
if($publish_field){
$publish_field_arr=explode(",",$publish_field);
foreach ($publish_field_arr as $data) {
$key_arr=explode("|",$data);
if($key_arr){
if(strip_tags($_POST[$key_arr[2]])==''){
$data_arr['code']=0;
$data_arr['msg']='请填写表单信息！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}
}
}



//付费可见、VIP可见、登录可见、评论可见
if($post_type=='pay_see'||$post_type=='vip_see'||$post_type=='login_see'||$post_type=='comment_see'){

if($post_type=='pay_see'){//付费
$price=(int)$_POST['price'];
if(empty($price)){
$data_arr['code']=0;
$data_arr['msg']='售价不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();			
}

if($price<$price_mini||$price>$price_max){
$data_arr['code']=0;
$data_arr['msg']='售价范围为'.$price_mini.$credit_name.'-'.$price_max.$credit_name.'！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

}


$hide_content=strip_tags($_POST['hide-content']);
$hide_content_number=mb_strlen($hide_content,'utf-8');

// if(jinsom_trimall($hide_content)==''){
// $data_arr['code']=0;
// $data_arr['msg']='隐藏内容请至少输入一些文字！';
// header('content-type:application/json');
// echo json_encode($data_arr);
// exit();	
// }

$jinsom_bbs_hide_words_max=jinsom_get_option('jinsom_bbs_hide_words_max');
if(!$jinsom_bbs_hide_words_max){$jinsom_bbs_hide_words_max=10000;}
if($hide_content_number>$jinsom_bbs_hide_words_max){
$data_arr['code']=0;
$data_arr['msg']='隐藏内容不能超过'.$jinsom_bbs_hide_words_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//隐藏内容防止表情转义
$hide_content = str_replace('&quot;','"',$_POST['hide-content']);
if(wp_is_mobile()){
$hide_content=htmlentities($hide_content,ENT_QUOTES,'UTF-8');
}

}

//悬赏帖子
if($post_type=='answer'&&get_post_meta($post_id,'post_type',true)!='answer'){
$answer_price=(int)$_POST['answer-price'];//悬赏金额
$answer_price_mini=(int)jinsom_get_option('jinsom_answer_price_mini');
$answer_price_max=(int)jinsom_get_option('jinsom_answer_price_max');
if($answer_price<$answer_price_mini||$answer_price>$answer_price_max){
$data_arr['code']=0;
$data_arr['msg']='悬赏金额范围'.$answer_price_mini.'-'.$answer_price_max.$credit_name.'！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}	
if($credit<$answer_price){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

update_post_meta($post_id,'answer_number',$answer_price);
jinsom_update_credit($user_id,$answer_price,'cut','publish-answer','发布悬赏内容',1,'');//扣除悬赏金币

}




//投票
if($post_type=='vote'){
$vote_time=htmlentities($_POST['vote-time'],ENT_QUOTES,'UTF-8');
if(strtotime($vote_time)<time()){
$data_arr['code']=0;
$data_arr['msg']='投票结束时间应该为未来时间！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}	
}


//活动帖子
if($post_type=='activity'){
$activity_time=htmlentities($_POST['activity-time'],ENT_QUOTES,'UTF-8');
$activity_price=intval($_POST['activity-price']);
if($activity_price<0||$activity_price>50000){
$data_arr['code']=0;
$data_arr['msg']='活动费用不能大于0且不能超过50000'.$credit_name.'！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
if(strtotime($activity_time)<time()){
$data_arr['code']=0;
$data_arr['msg']='活动结束时间应该为未来时间！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}	
}


//评论状态
if(!wp_is_mobile()){
if(isset($_POST['comment-status'])){
$comment_status='closed';
}else{
$comment_status='open';//默认允许评论	
}
}else{
$comment_status=$_POST['comment-status'];	
if($comment_status=='closed'){$comment_status='closed';}else{$comment_status='open';}//默认允许评论
}

//评论隐私
if(isset($_POST['comment-private'])){
$comment_private=1;
}else{
$comment_private=0;//默认公开
}


//帖子分类
$category=(int)$_POST['category'];
if(empty($category)){$category=$bbs_child_id;}//如果在子版块发布 则把分类定位到当前板块的分类



$post_arr=array(
'ID' => $post_id, 
'post_title' => $title, 
'post_content' => $content,
'comment_status'=>$comment_status,
'post_category'=>array($bbs_id,$category),
'post_parent' => 999999999
);

if(get_post_status($post_id)=='pending'){
$post_arr['post_date']=date('Y-m-d H:i:s');	
}

if($pending&&!$is_bbs_admin){
$post_arr['post_status']='pending';
}else{
$post_arr['post_status']='publish';	
}

$status=wp_update_post($post_arr);

if($status){


//下载信息
if($bbs_type=='download'){
update_post_meta($post_id,'download_data',$download_data);
}

//自定义字段
if($publish_field){
$publish_field_arr=explode(",",$publish_field);
foreach ($publish_field_arr as $data) {
$key_arr=explode("|",$data);
if($key_arr){
update_post_meta($post_id,$key_arr[2],strip_tags($_POST[$key_arr[2]]));
}
}
}



update_post_meta($post_id,'post_type',$post_type);//帖子类型
update_post_meta($post_id,'comment_private',$comment_private);//回复隐私
update_post_meta($post_id,'post_from','pc');//更新来自方式
update_post_meta($post_id,'post_ip',$ip);//更新文章ip

//更新话题
wp_set_post_tags($post_id,$topic,false);

//更新标题颜色
if(isset($_POST['title_color'])&&$_POST['title_color']!=''){
update_post_meta($post_id,'title_color',$_POST['title_color']);	
}


//付费可见、VIP可见、登录可见、评论可见
if($post_type=='pay_see'||$post_type=='vip_see'||$post_type=='login_see'||$post_type=='comment_see'){
if($post_type=='pay_see'){
update_post_meta($post_id,'post_price',$price);//更新售价
}else{
delete_post_meta($post_id,'post_price');//删除售价
}

update_post_meta($post_id,'post_price_cnt',$hide_content);//隐藏的内容
}else{
delete_post_meta($post_id,'post_price');//删除售价
delete_post_meta($post_id,'post_price_cnt');//删除隐藏的内容
}

//投票
if($post_type=='vote'){
$vote_times=abs(intval($_POST['vote-times']));
if($vote_times==0){$vote_times=1;}
update_post_meta($post_id,'vote_time',$vote_time);//投票时间
update_post_meta($post_id,'vote_times',$vote_times);//投票次数

if(get_post_meta($post_id,'post_type',true)!='vote'){//如果编辑的帖子 之前不是投票类型的就更新 如果是的就不更新
update_post_meta($post_id,'vote_data',htmlentities($_POST['vote-data'],ENT_QUOTES,'UTF-8'));//投票数据
}

}else{
delete_post_meta($post_id,'vote_data');	
delete_post_meta($post_id,'vote_time');
delete_post_meta($post_id,'vote_times');	
//删除所有已经投票的数据
global $wpdb;
$table_name = $wpdb->prefix . 'jin_vote';	
$wpdb->query( " DELETE FROM $table_name WHERE post_id=$post_id;" );	
}




//悬赏
if($post_type!='answer'){
delete_post_meta($post_id,'answer_number');	
}

//活动帖子
if($post_type=='activity'){
update_post_meta($post_id,'activity_data',htmlentities($_POST['activity-data'],ENT_QUOTES,'UTF-8'));//活动数据
update_post_meta($post_id,'activity_time',$activity_time);//活动时间
update_post_meta($post_id,'activity_price',$activity_price);//活动费用
}else{
delete_post_meta($post_id,'activity_data');	
delete_post_meta($post_id,'activity_time');
delete_post_meta($post_id,'activity_price');
delete_post_meta($post_id,'activity');//报名的数据 删除
}




// @用户提醒
if($power==0){
jinsom_at_notice($user_id,$post_id,$_POST['content'],'发表内容@了你');//艾特提醒
}

$data_arr['code']=1;
$data_arr['url']=get_the_permalink($post_id);

if($pending&&!$is_bbs_admin){
$data_arr['msg']='内容已提交审核！';
}else{
$data_arr['msg']='更新成功！';
}


}else{
$data_arr['code']=0;
$data_arr['msg']='更新失败！';	
}


}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';
}

header('content-type:application/json');
echo json_encode($data_arr);