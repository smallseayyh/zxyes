<?php 
//发布帖子
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;
$credit_name=jinsom_get_option('jinsom_credit_name');
if((jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("publish",jinsom_get_option('jinsom_machine_verify_use_for')))&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])&&!jinsom_is_admin($user_id)){
if(!jinsom_machine_verify($_POST['ticket'],$_POST['randstr'])){
$data_arr['code']=0;
$data_arr['msg']='安全验证失败！请重新进行操作！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

$bbs_id=(int)$_POST['bbs_id'];
$bbs_child_id=$_POST['bbs_child_id'];
$credit=get_user_meta($user_id,'credit',true);
$pending=get_term_meta($bbs_id,'pending',true);//审核功能
$bbs_type=get_term_meta($bbs_id,'bbs_type',true);//类型
$publish_field=get_term_meta($bbs_id,'publish_field',true);//发布字段


//判断是否登录
if(!is_user_logged_in()){ 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
$is_bbs_admin=(jinsom_is_admin_y($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;




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
update_user_meta($user_id,'danger_reason','连续发帖超过限定时间');
delete_user_meta($user_id,'session_tokens');
$data_arr['code']=0;
$data_arr['msg']=jinsom_user_danger_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}
}




$publish_topic_on_off = jinsom_get_option('jinsom_publish_bbs_topic_on_off');
$publish_add_topic_max = jinsom_get_option('jinsom_publish_bbs_add_topic_max');
$topic=$_POST['topic'];

if($topic==''&&$publish_topic_on_off){
$data_arr['code']=5;
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

$publish_credit = (int)get_term_meta($bbs_id,'bbs_credit_post_number',true);//论坛发帖子可获得的金币
$publish_exp = (int)get_term_meta($bbs_id,'bbs_exp_post_number',true);//论坛发帖子可获得的经验
$publish_max_times = (int)jinsom_get_option('jinsom_publish_bbs_max');//每日发帖上限次数
$user_publish_times = (int)get_user_meta($user_id,'publish_bbs_times',true);//个人当天已发布的帖子次数

if(is_vip($user_id)){
$jinsom_publish_limit = (int)jinsom_get_option('jinsom_publish_bbs_limit_vip');
}else{
$jinsom_publish_limit = (int)jinsom_get_option('jinsom_publish_bbs_limit');
}
if($user_publish_times>=$jinsom_publish_limit&&!jinsom_is_admin($user_id)){
$data_arr['code']=3;
$data_arr['msg']=__('你当天发表的帖子已经超过上限<br>开通VIP可提升上限','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//发布帖子 需要金币
if($publish_credit<0){
if($credit<abs($publish_credit)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！需要'.abs($publish_credit).$credit_name;
header('content-type:application/json');
echo json_encode($data_arr);
exit();			
}
}


//售价范围
$price_mini=(int)jinsom_get_option('jinsom_bbs_pay_price_mini');
$price_max=(int)jinsom_get_option('jinsom_bbs_pay_price_max');

if(isset($_POST['content'])){


$title=htmlentities($_POST['title'],ENT_QUOTES,'UTF-8');

if(jinsom_trimall($title)==''){
$data_arr['code']=0;
$data_arr['msg']='标题不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$title_number=mb_strlen($title,'utf-8');
if($title_number>$title_max){
$data_arr['code']=0;
$data_arr['msg']='标题不能超过'.$title_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if(wp_is_mobile()){
$content=htmlentities($_POST['content'],ENT_QUOTES,'UTF-8');

if(jinsom_trimall($content)==''&&!$_POST['img']){
$data_arr['code']=0;
$data_arr['msg']='请输入内容！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(jinsom_trimall($content)==''){
$content='分享图片';
}

$content=$content.'</br>'.$_POST['img'];
}else{
$content=$_POST['content'];	
}

$content_number=mb_strlen(strip_tags($_POST['content']),'utf-8');


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
if($post_type=='answer'){
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
if($activity_price<0||$activity_price>999999){
$data_arr['code']=0;
$data_arr['msg']='活动费用不能大于0且不能超过999999'.$credit_name.'！';
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
if($_POST['comment-private']){
$comment_private=1;
}else{
$comment_private=0;//默认公开
}


//帖子分类
$category=(int)$_POST['category'];
if(empty($category)){$category=$bbs_child_id;}//如果在子版块发布 则把分类定位到当前板块的分类


if(wp_is_mobile()){
$content=str_replace(array("\n\r","\n","\r"),'<p></p>',$content);//换行符替换
}

$post_arr=array(
'post_title' => $title, 
'post_content' => $content,
'comment_status'=>$comment_status,
'post_category'=>array($bbs_id,$category),
'post_parent' => 999999999
);

if($pending&&!$is_bbs_admin){
$post_arr['post_status']='pending';
}else{
$post_arr['post_status']='publish';	
}

$post_id=wp_insert_post($post_arr);

if($post_id){

//更新每次发表上限和获得的奖励

if($pending&&!$is_bbs_admin){
$publish_tips='内容已提交审核！';

jinsom_im_tips(1,__('提醒：你网站有新的内容审核，请及时处理！','jinsom'));//提醒管理员

}else{
$publish_tips='发布成功！';
if($publish_credit>0){
if($user_publish_times<$publish_max_times){
jinsom_update_credit($user_id,$publish_credit,'add','publish-bbs-post','发布内容',1,''); 
jinsom_update_exp($user_id,$publish_exp,'add','发布内容');
$publish_tips.='<span class="jinsom-icon jinsom-jinbi"></span> +'.$publish_credit.'&nbsp;&nbsp;';
if($publish_exp>0){
$publish_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$publish_exp;	
}
}  
}else if($publish_credit<0){
jinsom_update_credit($user_id,$publish_credit,'cut','publish-bbs-post','发布内容，扣除',1,''); 
$publish_tips.='<span class="jinsom-icon jinsom-jinbi"></span> -'.abs($publish_credit).'&nbsp;&nbsp;';
if($publish_exp>0&&$user_publish_times<$publish_max_times){
jinsom_update_exp($user_id,$publish_exp,'add','发布内容');
$publish_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$publish_exp;	
}
}else{//金币等于0
if($publish_exp>0&&$user_publish_times<$publish_max_times){
jinsom_update_exp($user_id,$publish_exp,'add','发布内容');
$publish_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$publish_exp;	
}
}
}



update_user_meta($user_id,'publish_bbs_times',$user_publish_times+1);  //更新发布次数


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

if(wp_is_mobile()){
update_post_meta($post_id,'post_from','mobile');//更新来自方式
}


update_post_meta($post_id,'post_ip',$ip);//更新文章ip
update_post_meta($post_id,'bbs_floor',1);//发帖默认为一楼
update_post_meta($post_id, 'last_comment_time', time());//设置回帖时间
update_user_meta($user_id,'last_publish_time',time());//记录用户最后发表的时间戳

//更新位置
if(isset($_POST['city'])&&$_POST['city']!=''){
$user_city=get_user_meta($user_id,'city',true);
if($user_city){
update_post_meta($post_id,'city',$user_city);	
}
}

//更新标题颜色
if(isset($_POST['title_color'])&&$_POST['title_color']!=''){
update_post_meta($post_id,'title_color',$_POST['title_color']);	
}


//更新话题
if(isset($_POST['topic'])){
$topic=$_POST['topic'];
wp_set_post_tags($post_id,$topic,false);	
}


//付费可见、VIP可见、登录可见、评论可见
if($post_type=='pay_see'||$post_type=='vip_see'||$post_type=='login_see'||$post_type=='comment_see'){
if($post_type=='pay_see'){
update_post_meta($post_id,'post_price',$price);//更新售价
}
if(wp_is_mobile()){
//$hide_content=str_replace(['\n\r','\r','\n'],'</br>',$hide_content);
$hide_content = str_replace(array("\r\n"), "<br/>",$hide_content);//处理换行
}
update_post_meta($post_id,'post_price_cnt',$hide_content);//隐藏的内容
}

//投票
if($post_type=='vote'){
$vote_times=abs(intval($_POST['vote-times']));
if($vote_times==0){$vote_times=1;}
update_post_meta($post_id,'vote_data',htmlentities($_POST['vote-data'],ENT_QUOTES,'UTF-8'));//投票数据
update_post_meta($post_id,'vote_time',$vote_time);//投票时间
update_post_meta($post_id,'vote_times',$vote_times);//投票次数
}

//活动帖子
if($post_type=='activity'){
update_post_meta($post_id,'activity_data',htmlentities($_POST['activity-data'],ENT_QUOTES,'UTF-8'));//活动数据
update_post_meta($post_id,'activity_time',$activity_time);//活动时间
update_post_meta($post_id,'activity_price',$activity_price);//活动费用
}

//悬赏
if($post_type=='answer'){
update_post_meta($post_id,'answer_number',$answer_price);
jinsom_update_credit($user_id,$answer_price,'cut','publish-answer','发布悬赏内容',1,'');//扣除悬赏金币
}


// @用户提醒
jinsom_at_notice($user_id,$post_id,$_POST['content'],'发表内容@了你');//艾特提醒


//统一今日更新量
$today_publish=(int)get_term_meta($bbs_id,'today_publish',true);
update_term_meta($bbs_id,'today_publish',($today_publish+1));


$data_arr['code']=1;
$data_arr['post_id']=$post_id;
$data_arr['bbs_id']=$bbs_id;
$data_arr['url']=get_the_permalink($post_id);	
$data_arr['msg']=$publish_tips;


}else{
$data_arr['code']=0;
$data_arr['msg']='提交失败！';	
}


}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';
}

header('content-type:application/json');
echo json_encode($data_arr);