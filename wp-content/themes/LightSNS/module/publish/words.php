<?php 
//发布动态
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id = $current_user->ID;

if((jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("publish",jinsom_get_option('jinsom_machine_verify_use_for')))&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])&&!jinsom_is_admin($user_id)){
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
update_user_meta($user_id,'danger_reason','连续发动态超过限定时间');
delete_user_meta($user_id,'session_tokens');
$data_arr['code']=0;
$data_arr['msg']=jinsom_user_danger_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}
}

$publish_topic_on_off = jinsom_get_option('jinsom_publish_words_topic_on_off');
$publish_add_topic_max = jinsom_get_option('jinsom_publish_words_add_topic_max');
$publish_add_images_max = jinsom_get_option('jinsom_publish_words_add_images_max');
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




$ip=$_SERVER['REMOTE_ADDR'];
$title_max=jinsom_get_option('jinsom_publish_posts_title_max_words');
$content_max=jinsom_get_option('jinsom_publish_posts_cnt_max_words');
$credit_name=jinsom_get_option('jinsom_credit_name');

$publish_credit = (int)jinsom_get_option('jinsom_publish_post_credit');//每次发表动态可获得的金币
$publish_exp = (int)jinsom_get_option('jinsom_publish_post_exp');//每次发表动态可获得的经验
$publish_max_times = (int)jinsom_get_option('jinsom_publish_post_max');//每日上限次数
$user_publish_times = (int)get_user_meta($user_id, 'publish_post_times', true );//个人当天已发布的动态次数

if(is_vip($user_id)){
$jinsom_publish_limit = (int)jinsom_get_option('jinsom_publish_limit_vip');
}else{
$jinsom_publish_limit = (int)jinsom_get_option('jinsom_publish_limit');	
}
if($user_publish_times>=$jinsom_publish_limit&&!jinsom_is_admin($user_id)){
$data_arr['code']=3;
$data_arr['msg']=__('你当天发表内容(不含帖子)已经超过上限<br>开通VIP可提升上限','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//如果需要金币才能发布的情况
if($publish_credit<0){
$user_credit=(int)get_user_meta($user_id,'credit',true);
if($user_credit<abs($publish_credit)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

//售价范围
$price_mini = (int)jinsom_get_option('jinsom_publish_price_mini');
$price_max = (int)jinsom_get_option('jinsom_publish_price_max');

if(isset($_POST['content'])){


if(isset($_POST['title'])){
$title=htmlentities($_POST['title'],ENT_QUOTES,'UTF-8');
$title_number=mb_strlen($title,'utf-8');


if($title_number>$title_max){
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

}else{
$title='';	
}


$content=htmlentities($_POST['content'],ENT_QUOTES,'UTF-8');
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

$hide_content=htmlentities($_POST['hide-content'],ENT_QUOTES,'UTF-8');
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
'post_title' => $title, 
'post_content' => $content,
'comment_status'=>$comment_status
);

$pending=jinsom_get_option('jinsom_publish_words_pending');
if($pending&&!jinsom_is_admin_y($user_id)){
$post_arr['post_status']='pending';
}else{
$post_arr['post_status']='publish';	
}

$post_id=wp_insert_post($post_arr);

if($post_id){

//更新每次发表上限和获得的奖励
if($pending&&!jinsom_is_admin_y($user_id)){
$publish_tips='内容已提交审核！';

jinsom_im_tips(1,__('提醒：你网站有新的内容审核，请及时处理！','jinsom'));//提醒管理员

}else{
$publish_tips='发布成功！';
if($power!=3){//不是私密内容
if($publish_credit>0){
if($user_publish_times<$publish_max_times){
jinsom_update_credit($user_id,$publish_credit,'add','publish-post','发布内容',1,'');   
jinsom_update_exp($user_id,$publish_exp,'add','发布内容');
$publish_tips.='<span class="jinsom-icon jinsom-jinbi"></span> +'.$publish_credit.'&nbsp;&nbsp;';
if($publish_exp>0){
$publish_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$publish_exp;	
}
}  
}else if($publish_credit<0){
jinsom_update_credit($user_id,$publish_credit,'cut','publish-post','发布内容，扣除',1,''); 
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
}//判断是不是私密内容
}


update_user_meta($user_id,'publish_post_times',$user_publish_times+1);//更新发布次数


//更新话题
if(isset($_POST['topic'])){
$topic=$_POST['topic'];
wp_set_post_tags($post_id,$topic,false);	
}

update_post_meta($post_id,'post_power',$power);//更新power
update_post_meta($post_id,'post_type','words');//类型
if(wp_is_mobile()){
update_post_meta($post_id,'post_from','mobile');//更新来自方式
}

update_post_meta($post_id,'post_ip',$ip);//更新文章ip

update_post_meta($post_id, 'last_comment_time', time());//设置回复时间
update_user_meta($user_id,'last_publish_time',time());//记录用户最后发表的时间戳

//更新位置
if(isset($_POST['city'])&&$_POST['city']!=''){
$user_city=get_user_meta($user_id,'city',true);
if($user_city){
update_post_meta($post_id,'city',$user_city);	
}
}


//更新图片
if(isset($_POST['img'])&&isset($_POST['img_thum'])){
update_post_meta($post_id,'post_img',htmlentities($_POST['img'],ENT_QUOTES,'UTF-8'));//更新图片
update_post_meta($post_id,'post_thum',htmlentities($_POST['img_thum'],ENT_QUOTES,'UTF-8'));//更新缩略图
}


//有权限类
if($power==1||$power==2||$power==4||$power==5||$power==6||$power==7||$power==8){
if($power==1){
update_post_meta($post_id,'post_price',$price);//更新售价
}
if($power==2){
update_post_meta($post_id,'post_password',$password);//更新密码	
}
update_post_meta($post_id,'pay_cnt',$hide_content);//隐藏的内容
update_post_meta($post_id,'pay_img_on_off',(int)$_POST['power-see-img']);//没有权限是否也可以浏览图片
}

//判断是否添加了应用
if(isset($_POST['application-type'])&&$_POST['application-type']!=''&&isset($_POST['application-value'])){
$application_type=$_POST['application-type'];
$application_value=$_POST['application-value'];
if($application_type=='shop'||$application_type=='challenge'||$application_type=='url'||$application_type=='pet'){
if($application_type!='pet'){
update_post_meta($post_id,'application-type',$application_type);
if($application_type=='shop'){
$application_value=(int)$application_value;
}else{
$application_value=	strip_tags($application_value);
}
update_post_meta($post_id,'application-value',$application_value);
}else{
$pet_arr=explode(",",$application_value);
if(count($pet_arr)==2){
$pet_name=$pet_arr[0];
$pet_img=$pet_arr[1];
$pet_data=jinsom_get_option('jinsom_pet_add');
$status=0;
if($pet_data){
foreach ($pet_data as $pet) {
if($pet['name']==$pet_name&&$pet['img_pet']==$pet_img){
$status=1;
}
}
if($status){
update_post_meta($post_id,'application-type',$application_type);
update_post_meta($post_id,'application-value',$application_value);
}
}
}

}
}
}


// @用户提醒
if($power==0){
jinsom_at_notice($user_id,$post_id,$_POST['content'],'发表内容@了你');//艾特提醒
}


$data_arr['code']=1;
$data_arr['post_id']=$post_id;
$data_arr['url']=get_the_permalink($post_id);	
$data_arr['msg']=$publish_tips;


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