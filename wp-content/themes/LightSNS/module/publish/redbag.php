<?php 
//发红包
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

$user_id=$current_user->ID;
$credit=intval($_POST['credit']);//红包金额
$number=intval($_POST['number']);//红包个数
$type=htmlentities(strip_tags($_POST['type']));
$content=htmlentities(strip_tags($_POST['content']));
$user_credit=(int)get_user_meta($user_id,'credit',true);
$redbag_cover=(int)$_POST['redbag_cover'];//红包封面

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


//判断账户是否异常
$jinsom_danger_on_off = jinsom_get_option('jinsom_danger_on_off');
if($jinsom_danger_on_off&&!jinsom_is_admin($user_id)){//开启并且不是管理团队
$jinsom_publish_danger_limit = (int)jinsom_get_option('jinsom_publish_danger_limit');
$last_publish_time=get_user_meta($user_id,'last_publish_time',true);
if($last_publish_time){//曾经发布过内容
if(time()-$last_publish_time<=$jinsom_publish_danger_limit){//如果现在发布的时间和上次的时间间隔小于安全值，则自动变为风险账户
update_user_meta($user_id,'user_power',4);
delete_user_meta($user_id,'session_tokens');
$data_arr['code']=0;
$data_arr['msg']='系统检测到你的账户异常，已经将你的账户变为风险账号，请你联系管理员进行解封！1002';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}
}

if($content==''){
$data_arr['code']=0;
$data_arr['msg']='红包贺语不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


$ip=$_SERVER['REMOTE_ADDR'];
$credit_name=jinsom_get_option('jinsom_credit_name');

if($credit<=0){
$data_arr['code']=0;
$data_arr['msg']='红包金额不能小于0！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



//红包金额范围
$credit_mini = jinsom_get_option('jinsom_redbag_min');
$credit_max = jinsom_get_option('jinsom_redbag_max');
if($credit<$credit_mini||$credit>$credit_max){
$data_arr['code']=0;
$data_arr['msg']='红包金额范围'.$credit_mini.$credit_name.'-'.$credit_max.$credit_name.'！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if($credit<$number){
$data_arr['code']=0;
$data_arr['msg']='红包金额不能小于红包个数！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if($number<=0||$number>100){
$data_arr['code']=0;
$data_arr['msg']='红包个数不能小于0或大于100！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//红包封面
$redbag_img_add=jinsom_get_option('jinsom_redbag_img_add');
if($redbag_cover){
$redbag_cover--;
if($redbag_img_add[$redbag_cover]['vip']){
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='这是VIP专属红包封面，你需要开通vip会员！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}


if($type=='average'){
$credit=$credit*$number;
}


if($user_credit<$credit){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}



$desc_number=jinsom_get_option('jinsom_redbag_desc_number');
$content_number=mb_strlen($content,'utf-8');
if($content_number>$desc_number){
$data_arr['code']=0;
$data_arr['msg']='红包贺语不能超过'.$desc_number.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//敏感词过滤
if(jinsom_get_option('jinsom_baidu_filter_on_off')&&in_array("publish",jinsom_get_option('jinsom_baidu_filter_use_for'))&&!jinsom_is_admin($user_id)){
$filter=jinsom_baidu_filter($content);
if($filter['conclusionType']==2){
$data_arr['code']=0;
$a=$filter['data'][0]['hits'][0]['words'][0];
if($a==''){$a=$filter['data'][0]['msg'];}
$data_arr['msg']='红包贺语含有敏感词：'.$a;
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}


$post_arr=array(
'post_content' => $content,
'post_status' => 'publish',
'comment_status'=>'open'
);
$post_id=wp_insert_post($post_arr);
if($post_id){
jinsom_update_credit($user_id,$credit,'cut','publish-redbag','发红包，扣除',1,''); 

update_post_meta($post_id,'redbag_type',$type);
update_post_meta($post_id,'redbag_credit',$credit);
update_post_meta($post_id,'redbag_number',$number);
update_post_meta($post_id,'post_type','redbag');
update_post_meta($post_id,'redbag_surplus_credit',$credit);//剩余金额

// wp_set_post_tags($post_id,'红包',false);

//红包封面
if($_POST['redbag_cover']){
update_post_meta($post_id,'redbag_cover',$redbag_cover);
}


if(wp_is_mobile()){
update_post_meta($post_id,'post_from','mobile');
}
update_post_meta($post_id,'post_ip',$ip);//更新文章ip
update_post_meta($post_id,'last_comment_time', time());//设置回复时间
update_user_meta($user_id,'last_publish_time',time());//记录用户最后发表的时间戳

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$post_id','redbag','$time')");


$data_arr['code']=1;
$data_arr['post_id']=$post_id;
$data_arr['url']=get_the_permalink($post_id);	
$data_arr['msg']='发红包成功！';


}else{
$data_arr['code']=0;
$data_arr['msg']='发红包失败！';	
}


header('content-type:application/json');
echo json_encode($data_arr);