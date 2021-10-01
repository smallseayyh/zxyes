<?php
//编辑动态 
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

$user_id=$current_user->ID;
$post_id=$_POST['post_id'];
$author_id=jinsom_get_user_id_post($post_id);
$credit_name=jinsom_get_option('jinsom_credit_name');

if(!jinsom_is_admin($user_id)&&$user_id!=$author_id){
$data_arr['code']=0;
$data_arr['msg']='你没有权限编辑！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}



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
if(time()-$single_time>60*60*$edit_time&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='已经超过编辑时间，无法进行编辑！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}




$publish_topic_on_off = jinsom_get_option('jinsom_publish_words_topic_on_off');
$publish_add_topic_max = jinsom_get_option('jinsom_publish_words_add_topic_max');
$publish_add_images_max = jinsom_get_option('jinsom_publish_words_add_images_max');
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

$img=$_POST['img'];
if($img!=''){
$img_arr=explode(",",$img);
if(count($img_arr)>$publish_add_images_max){
$data_arr['code']=0;
$data_arr['msg']='最多只允许上传'.$publish_add_images_max.'张图片！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}	
}


$ip = $_SERVER['REMOTE_ADDR'];
$title_max= jinsom_get_option('jinsom_publish_posts_title_max_words');
$content_max= jinsom_get_option('jinsom_publish_posts_cnt_max_words');

//售价范围
$price_mini = (int)jinsom_get_option('jinsom_publish_price_mini');
$price_max = (int)jinsom_get_option('jinsom_publish_price_max');

if(isset($_POST['content'])){


if(isset($_POST['title'])){
$title=htmlspecialchars($_POST['title']);
$title_number=mb_strlen($title,'utf-8');

if($title_num>$title_max){
$data_arr['code']=0;
$data_arr['msg']='标题不能超过'.$title_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//敏感词过滤
if(jinsom_get_option('jinsom_baidu_filter_on_off')&&in_array("title",jinsom_get_option('jinsom_baidu_filter_use_for'))&&!jinsom_is_admin($user_id)){
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

}


$content=htmlspecialchars($_POST['content']);
$content_number=mb_strlen($content,'utf-8');


if(jinsom_trimall($content)==''&&!$img){
$data_arr['code']=0;
$data_arr['msg']='请输入内容！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(jinsom_trimall($content)==''){
$content='分享图片';
}

if($content_number>$content_max&&!current_user_can('level_10')){
$data_arr['code']=0;
$data_arr['msg']='内容不能超过'.$content_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//换行符替换
$content=str_replace(['\n\r','\r'],'</br>',$content);

//敏感词过滤
if(jinsom_get_option('jinsom_baidu_filter_on_off')&&in_array("publish",jinsom_get_option('jinsom_baidu_filter_use_for'))&&!jinsom_is_admin($user_id)){
$filter=jinsom_baidu_filter($content);
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

//渲染艾特内容
$content= jinsom_at($content);

$power=(int)$_POST['power'];
if($power==1||$power==2||$power==4||$power==5||$power==6||$power==7||$power==8){
//付费
if($power==1){
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
//密码
if($power==2){
$password=htmlentities($_POST['password'],ENT_QUOTES,'UTF-8');
if(empty($password)){
$data_arr['code']=0;
$data_arr['msg']='密码不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();			
}
}

$hide_content=htmlspecialchars($_POST['hide-content']);
$hide_content_number=mb_strlen($hide_content,'utf-8');

if(jinsom_trimall($hide_content)==''){
$data_arr['code']=0;
$data_arr['msg']='请输入隐藏内容！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$jinsom_hide_words_max=jinsom_get_option('jinsom_hide_words_max');
if(!$jinsom_hide_words_max){$jinsom_hide_words_max=1000;}
if($hide_content_number>$jinsom_hide_words_max){
$data_arr['code']=0;
$data_arr['msg']='隐藏内容不能超过'.$jinsom_hide_words_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//隐藏内容的换行符替换
$hide_content=str_replace(['\n\r','\r'],'</br>',$hide_content);

}


//评论状态
$comment_status=$_POST['comment-status'];
if($comment_status=='closed'){$comment_status='closed';}else{$comment_status='open';}//默认允许评论


$post_arr=array(
'ID' => $post_id, 
'post_title' => $title, 
'post_content' => $content,
'comment_status'=>$comment_status
);

if(get_post_status($post_id)=='pending'){
$post_arr['post_date']=date('Y-m-d H:i:s');	
}

$pending=jinsom_get_option('jinsom_publish_words_pending');
if($pending&&!jinsom_is_admin_y($user_id)){
$post_arr['post_status']='pending';
}else{
$post_arr['post_status']='publish';	
}
$status=wp_update_post($post_arr);

if($status){


//更新话题
$topic=$_POST['topic'];
wp_set_post_tags($post_id,$topic,false);	

update_post_meta($post_id,'post_power',$power);//更新power
update_post_meta($post_id,'post_type','words');//类型
// update_post_meta($post_id,'post_from','pc');//更新来自方式
update_post_meta($post_id,'post_ip',$ip);//更新文章ip


//更新图片
if(isset($_POST['img'])&&isset($_POST['img_thum'])){
update_post_meta($post_id,'post_img',htmlentities($_POST['img'],ENT_QUOTES,'UTF-8'));//更新图片
update_post_meta($post_id,'post_thum',htmlentities($_POST['img_thum'],ENT_QUOTES,'UTF-8'));//更新缩略图
}else{
delete_post_meta($post_id,'post_img');	
delete_post_meta($post_id,'post_thum');
}


//有权限类
if($power==1||$power==2||$power==4||$power==5||$power==6||$power==7||$power==8){
if($power==1){
update_post_meta($post_id,'post_price',$price);//更新售价
}else{
delete_post_meta($post_id,'post_price');//删除售价	
}
if($power==2){
update_post_meta($post_id,'post_password',$password);//更新密码	
}else{
delete_post_meta($post_id,'post_password');//删除密码	
}
update_post_meta($post_id,'pay_cnt',$hide_content);//隐藏的内容
update_post_meta($post_id,'pay_img_on_off',$_POST['power-see-img']);//没有权限是否也可以浏览图片
}else{
delete_post_meta($post_id,'post_price');//删除售价
delete_post_meta($post_id,'post_password');//删除密码
delete_post_meta($post_id,'pay_cnt');
delete_post_meta($post_id,'pay_img_on_off');
}


// @用户提醒
if($power==0){
jinsom_at_notice($user_id,$post_id,$content,'发表内容@了你');//艾特提醒
}

$data_arr['code']=1;
$data_arr['url']=get_the_permalink($post_id);
$data_arr['msg']='更新成功！';


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



