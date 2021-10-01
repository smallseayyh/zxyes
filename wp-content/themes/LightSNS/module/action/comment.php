<?php
//评论
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id = $current_user->ID;
if((jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("comment",jinsom_get_option('jinsom_machine_verify_use_for')))&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])&&!jinsom_is_admin($user_id)){
if(!jinsom_machine_verify($_POST['ticket'],$_POST['randstr'])){
$data_arr['code']=0;
$data_arr['msg']='安全验证失败！请重新进行操作！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}



$post_id=$_POST['post_id'];
$author_id=jinsom_get_user_id_post($post_id);
$author_nickname=get_user_meta($author_id,'nickname',true);


//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if(get_user_meta($user_id,'user_power',true)==4){
$data_arr['code']=0;
$data_arr['msg']='你的帐号已经被限制登录！1003';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//自动风控
if(!get_user_meta($user_id,'latest_ip',true)){
update_user_meta($user_id,'user_power',4);
update_user_meta($user_id,'danger_reason','评论内容不存在IP');
delete_user_meta($user_id,'session_tokens');
$data_arr['code']=0;
$data_arr['msg']=jinsom_user_danger_tips();
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


//判断内容是否为空
if(jinsom_trimall($_POST['content'])==''&&$_POST['img']==''){
$data_arr['code']=0;
$data_arr['msg']='请输入内容！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断账户是否异常
$jinsom_danger_on_off = jinsom_get_option('jinsom_danger_on_off');
if($jinsom_danger_on_off&&!current_user_can('level_10')){//开启并且不是管理团队
$jinsom_comment_danger_limit = (int)jinsom_get_option('jinsom_comment_danger_limit');
$last_comment_time=get_user_meta($user_id,'last_comment_time',true);
if($last_comment_time){//曾经发布过内容
if(time()-$last_comment_time<=$jinsom_comment_danger_limit){//如果现在评论的时间和上次的时间间隔小于安全值，则自动变为风险账户
update_user_meta($user_id,'user_power',4);
update_user_meta($user_id,'danger_reason','连续评论动态超过限定时间');
delete_user_meta($user_id,'session_tokens');
$data_arr['code']=0;
$data_arr['msg']=jinsom_user_danger_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}
}

//判断是否被对方拉黑
if(jinsom_is_blacklist($author_id,$user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='评论失败！对方已经将你加入黑名单！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//需要绑定手机号才能使用
$bind_phone_use_for=jinsom_get_option('jinsom_bind_phone_use_for');
if($bind_phone_use_for&&in_array("comment",$bind_phone_use_for)&&!current_user_can('level_10')){
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
if($bind_email_use_for&&in_array("comment",$bind_email_use_for)&&!current_user_can('level_10')){
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

if(!comments_open($post_id)&&!jinsom_is_admin($user_id)&&$user_id!=$author_id&&get_post_type($post_id)=='post'){
$data_arr['code']=0;
$data_arr['msg']='该内容不允许评论！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


//评论权限
$power = jinsom_get_option('jinsom_comment_power');	
$comment_exp = jinsom_get_option('jinsom_comment_power_exp');
$honor_arr = jinsom_get_option('jinsom_comment_power_honor_arr');
$verify_arr = jinsom_get_option('jinsom_comment_power_verify_arr');
if($power=='vip'){//VIP用户
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='会员用户才有权限评论！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='verify'){//认证用户
$user_verify=get_user_meta($user_id,'verify',true);
if(!$user_verify&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='认证用户才有权限评论！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($power=='honor'){//头衔用户
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor==''&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='拥有头衔的用户才有权限评论！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($power=='admin'){//管理员
if(!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='管理员才有权限评论！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($power=='exp'){//指定经验
$user_exp=jinsom_get_user_exp($user_id);//当前用户经验
if($user_exp<$comment_exp&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='需要经验值达到'.$comment_exp.'，才有权限评论！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($power=='honor_arr'){//指定头衔
if(!jinsom_is_admin($user_id)){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$publish_power_honor_arr=explode(",",$honor_arr);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($publish_power_honor_arr,$user_honor_arr)){	
$data_arr['code']=0;
$data_arr['msg']='只允许指定头衔的用户评论！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else{
$data_arr['code']=0;
$data_arr['msg']='只允许指定头衔的用户评论！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}else if($power=='verify_arr'){//指定认证类型
if(!jinsom_is_admin($user_id)){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$publish_power_verify_arr=explode(",",$verify_arr);
if(!in_array($user_verify_type,$publish_power_verify_arr)){
$data_arr['code']=0;
$data_arr['msg']='只允许指定认证类型的用户评论！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else{
$data_arr['code']=0;
$data_arr['msg']='只允许指定认证类型的用户评论！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}




$content_number=mb_strlen($_POST['content'],'utf-8');
$jinsom_comment_words_max=jinsom_get_option('jinsom_comment_words_max');
if(!$jinsom_comment_words_max){$jinsom_comment_words_max=300;}
if($content_number>$jinsom_comment_words_max){
$data_arr['code']=0;
$data_arr['msg']='最多只能回复'.$jinsom_comment_words_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

$comment_words_mini=jinsom_get_option('jinsom_comment_words_mini');
if($content_number<$comment_words_mini){
$data_arr['code']=0;
$data_arr['msg']='回复不能少于'.$comment_words_mini.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}







if(isset($_POST['content'])&&isset($_POST['post_id'])){

$comment_credit =(int)jinsom_get_option('jinsom_comment_post_credit');//回复可获得的金币
$comment_exp = (int)jinsom_get_option('jinsom_comment_post_exp');//回复可获得的经验
$comment_max_times = (int)jinsom_get_option('jinsom_comment_post_max');//每日回复上限次数
$user_comment_times = (int)get_user_meta($user_id,'comment_post_times',true);//个人当天回复的次数

if(is_vip($user_id)){
$jinsom_comment_limit = (int)jinsom_get_option('jinsom_comment_limit_vip');
}else{
$jinsom_comment_limit = (int)jinsom_get_option('jinsom_comment_limit');	
}
if($user_comment_times>=$jinsom_comment_limit&&!jinsom_is_admin($user_id)){
$data_arr['code']=3;
$data_arr['msg']=__('你当天评论次数(不含回帖)已达上限<br>开通VIP可提升上限','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}





$comment_content=htmlspecialchars($_POST['content']);//过滤标签
$comment_content = str_replace(array("\r", "\n", "\r\n"), "<br>", $comment_content);//处理换行

//敏感词过滤
if(jinsom_get_option('jinsom_baidu_filter_on_off')&&in_array("comment",jinsom_get_option('jinsom_baidu_filter_use_for'))&&!jinsom_is_admin($user_id)){
$filter=jinsom_baidu_filter($comment_content);
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
$comment_content= jinsom_at($comment_content);

//如果需要金币才能评论的情况
if($comment_credit<0){
$user_credit=(int)get_user_meta($user_id,'credit',true);
if($user_credit<abs($comment_credit)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;//评论失败
$data_arr['msg']='你的'.jinsom_get_option('jinsom_credit_name').'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}


update_user_meta($user_id,'comment_post_times',$user_comment_times+1);  //更新回复动态次数

$time = current_time('mysql');
$ip = $_SERVER['REMOTE_ADDR'];
$data = array(
'comment_post_ID' =>$post_id,
'comment_content' => $comment_content,
'user_id' => $user_id,
'comment_author_IP'=>$ip,
'comment_date' => $time
);
$comment_id=wp_insert_comment($data); 
if($comment_id){

//更新每次评论上限和获得的奖励
$comment_tips='评论成功！';
if($comment_credit>0){
if($user_comment_times<$comment_max_times){
jinsom_update_credit($user_id,$comment_credit,'add','comment','评论了内容',1,'');  
jinsom_update_exp($user_id,$comment_exp,'add','评论内容');
$comment_tips.='<span class="jinsom-icon jinsom-jinbi"></span> +'.$comment_credit.'&nbsp;&nbsp;';
if($comment_exp>0){
$comment_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$comment_exp;	
}
}  
}else if($comment_credit<0){
jinsom_update_credit($user_id,$comment_credit,'cut','comment','评论内容，扣除',1,'');  

$comment_tips.='<span class="jinsom-icon jinsom-jinbi"></span> -'.abs($comment_credit).'&nbsp;&nbsp;';
if($comment_exp>0&&$user_comment_times<$comment_max_times){
$comment_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$comment_exp;	
jinsom_update_exp($user_id,$comment_exp,'add','评论内容');
}
}else{//金币等于0
if($comment_exp>0&&$user_comment_times<$comment_max_times){
jinsom_update_exp($user_id,$comment_exp,'add','评论内容');
$comment_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$comment_exp;	
}
}



//提醒被艾特的用户
preg_match_all('/@([\x7f-\xff^a-zA-z0-9]+)/',$comment_content,$arr);
$arr_a=array_unique($arr[1]);
foreach ($arr_a as $arr_b) {//重复@只提醒一次
if(jinsom_nickname_exists($arr_b)){
$at_user_id=jinsom_get_user_id_for_nickname($arr_b);
if($at_user_id!=$user_id&&$author_id!=$at_user_id){//@的用户不等于当前用户并且@用户不等于作者才发送@提醒
if(!jinsom_is_blacklist($at_user_id,$user_id)){//没有被对方拉黑
jinsom_add_tips($at_user_id,$user_id,$post_id,'aite','评论时@了你',$comment_id);
}
}
}
}
if($author_id!=$user_id){//判断不是自己回复自己的动态才发送提醒
jinsom_add_tips($author_id,$user_id,$post_id,'comment',strip_tags($comment_content),$comment_id);
}

//来自终端
if(wp_is_mobile()){
update_comment_meta($comment_id,'from','mobile');//手机端
}

update_post_meta($post_id,'last_comment_time',time());//插入最后回复字段	
update_user_meta($user_id,'last_comment_time',time());//记录用户最后评论的时间戳


//移动端评论回复插入图片
if(isset($_POST['img'])){
$img=strip_tags($_POST['img']);
if($img){
update_comment_meta($comment_id,'img',$img); 
}
}


$data_arr['code']=1;
$data_arr['msg']=$comment_tips;
$data_arr['id']=$comment_id;
$data_arr['url']=get_the_permalink($post_id);
$data_arr['content']=convert_smilies(jinsom_autolink($comment_content)); //评论成功

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$user_id','$post_id','comment','$time','$author_id')");


}else{
$data_arr['code']=0;//评论失败
$data_arr['msg']='评论失败！';
}



}else{
$data_arr['code']=0;
$data_arr['msg']='数据异常！';
}

$data_arr['test']=$user_comment_times;
header('content-type:application/json');
echo json_encode($data_arr);