<?php 
//发布树洞
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;

if((jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("publish-secret",jinsom_get_option('jinsom_machine_verify_use_for')))&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])&&!jinsom_is_admin($user_id)){
if(!jinsom_machine_verify($_POST['ticket'],$_POST['randstr'])){
$data_arr['code']=0;
$data_arr['msg']='安全验证失败！请重新进行操作！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
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

//自动风控
if(!get_user_meta($user_id,'latest_ip',true)){
update_user_meta($user_id,'user_power',4);
update_user_meta($user_id,'danger_reason','发内容不存在用户IP');
delete_user_meta($user_id,'session_tokens');
$data_arr['code']=0;
$data_arr['msg']=jinsom_user_danger_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断账户是否异常
$jinsom_danger_on_off = jinsom_get_option('jinsom_danger_on_off');
if($jinsom_danger_on_off&&!current_user_can('level_10')){//开启并且不是管理团队
$jinsom_publish_danger_limit = (int)jinsom_get_option('jinsom_publish_danger_limit');
$last_publish_time=get_user_meta($user_id,'last_publish_time',true);
if($last_publish_time){//曾经发布过内容
if(time()-$last_publish_time<=$jinsom_publish_danger_limit){//如果现在发布的时间和上次的时间间隔小于安全值，则自动变为风险账户
update_user_meta($user_id,'user_power',4);
update_user_meta($user_id,'danger_reason','连续发内容超过限定时间');
delete_user_meta($user_id,'session_tokens');
$data_arr['code']=0;
$data_arr['msg']=jinsom_user_danger_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}
}


$secret_credit=(int)jinsom_get_option('jinsom_secret_credit');
if($secret_credit){
$credit_name=jinsom_get_option('jinsom_credit_name');
$user_credit=(int)get_user_meta($user_id,'credit',true);
if($user_credit<abs($secret_credit)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！每次发布需要花费'.$secret_credit.$credit_name;
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}



$ip=$_SERVER['REMOTE_ADDR'];
$user_publish_times=(int)get_user_meta($user_id,'publish_post_times',true);//个人当天已发布的动态次数

// if(is_vip($user_id)){
// $jinsom_publish_limit=(int)jinsom_get_option('jinsom_publish_limit_vip');
// }else{
// $jinsom_publish_limit=(int)jinsom_get_option('jinsom_publish_limit');	
// }
// if($user_publish_times>=$jinsom_publish_limit&&!jinsom_is_admin($user_id)){
// $data_arr['code']=0;
// $data_arr['msg']=__('你当天发表内容已经超过上限！(不含帖子)','jinsom');
// header('content-type:application/json');
// echo json_encode($data_arr);
// exit();	
// }



if(isset($_POST['content'])){
$content=strip_tags($_POST['content']);
$topic=strip_tags($_POST['topic']);
$color=strip_tags($_POST['color']);
$jinsom_secret_type_add=jinsom_get_option('jinsom_secret_type_add');
$jinsom_secret_color_add=jinsom_get_option('jinsom_secret_color_add');
$jinsom_secret_rand_name=jinsom_get_option('jinsom_secret_rand_name');

if($jinsom_secret_color_add){
$color_status=0;
foreach ($jinsom_secret_color_add as $data) {
if($color==$data['color']){
$color_status=1;
if($data['vip']){
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$color_status=2;
}
}
}
}

if(!$color_status){
$data_arr['code']=0;
$data_arr['msg']='颜色参数异常！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if($color_status==2){
$data_arr['code']=3;
$data_arr['msg']='该颜色需要开通VIP才可以使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


}else{
$color='#666';
}


if($jinsom_secret_type_add){
$topic_status=0;
foreach ($jinsom_secret_type_add as $data) {
if($topic==$data['name']){
$topic_status=1;
}
}

if(!$topic_status){
$data_arr['code']=0;
$data_arr['msg']='标签参数异常！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

}




$content_number=mb_strlen($content,'utf-8');

if(jinsom_trimall($content)==''){
$data_arr['code']=0;
$data_arr['msg']='请输入内容！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$content_min=jinsom_get_option('jinsom_secret_content_min');//字数下限
if($content_number<$content_min&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='内容不能少于'.$content_min.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

$content_max=jinsom_get_option('jinsom_secret_content_max');//字数上限
if($content_number>$content_max&&!jinsom_is_admin($user_id)){
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



$post_arr=array(
'post_content' => $content,
'post_type' =>'secret',
'comment_status'=>'open'
);
$post_arr['post_status']='publish';	
$post_id=wp_insert_post($post_arr);

if($post_id){

if($secret_credit){
jinsom_update_credit($user_id,$secret_credit,'cut','publish-post','发布匿名内容，扣除',1,'');
}

//更新话题
if(isset($_POST['topic'])){
wp_set_post_tags($post_id,$topic,false);	
}

update_post_meta($post_id,'nice_num',0);//点赞数
update_post_meta($post_id,'post_type','secret');//类型
update_post_meta($post_id,'secret_color',$color);//背景色
// update_user_meta($user_id,'publish_post_times',$user_publish_times+1);//更新发布次数
if(wp_is_mobile()){
update_post_meta($post_id,'post_from','mobile');//更新来自方式
}
update_post_meta($post_id,'post_ip',$ip);//更新文章ip
update_post_meta($post_id, 'last_comment_time', time());//设置回复时间
update_user_meta($user_id,'last_publish_time',time());//记录用户最后发表的时间戳

if($jinsom_secret_rand_name){
$name_arr=explode(",",$jinsom_secret_rand_name);
$rand=rand(0,count($name_arr));
$name=$name_arr[$rand];
}else{
$name='匿名';	
}

update_post_meta($post_id,'secret_name',$name);//匿名昵称
if(jinsom_get_option('jinsom_secret_rand_avatar_url')){//开启随机头像
$rand_avatar_number=jinsom_get_option('jinsom_secret_rand_avatar_number');
$avatar_url=jinsom_get_option('jinsom_secret_rand_avatar_url').rand(1,$rand_avatar_number).'.png';
}else{
if(jinsom_get_option('jinsom_default_avatar')){
$avatar_url=jinsom_get_option('jinsom_default_avatar');	
}else{
$avatar_url=get_template_directory_uri().'/images/default-cover.jpg';		
}
}
update_post_meta($post_id,'secret_avatar',$avatar_url);//秘密头像


$data_arr['code']=1;
$data_arr['post_id']=$post_id;
$data_arr['url']=get_the_permalink($post_id);	
$data_arr['msg']='发表成功！';


}else{
$data_arr['code']=0;
$data_arr['msg']='发表失败！';	
}


}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';
}

header('content-type:application/json');
echo json_encode($data_arr);