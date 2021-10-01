<?php
//评论
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

$user_id=$current_user->ID;
$bbs_name=jinsom_get_option('jinsom_bbs_name');//论坛名称

if((jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("comment",jinsom_get_option('jinsom_machine_verify_use_for')))&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])&&!jinsom_is_admin($user_id)){
if(!jinsom_machine_verify($_POST['ticket'],$_POST['randstr'])){
$data_arr['code']=0;
$data_arr['msg']='安全验证失败！请重新进行操作！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

$content=$_POST['content'];
$bbs_id=$_POST['bbs_id'];
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

//自动风控
if(!get_user_meta($user_id,'latest_ip',true)){
update_user_meta($user_id,'user_power',4);
update_user_meta($user_id,'danger_reason','回复帖子不存在IP');
delete_user_meta($user_id,'session_tokens');
$data_arr['code']=0;
$data_arr['msg']=jinsom_user_danger_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否黑名单[网站]
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}


//判断内容是否为空
if($_POST['type']==1){//一级回复
if(jinsom_trimall($content)==''&&$_POST['img']==''){
$data_arr['code']=0;
$data_arr['msg']='请输入内容！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}	
}else{//二级回复
if(jinsom_trimall(strip_tags($content))==''){
$data_arr['code']=0;
$data_arr['msg']='请输入内容！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}
}


//判断账户是否异常
$jinsom_danger_on_off = jinsom_get_option('jinsom_danger_on_off');
if($jinsom_danger_on_off&&!current_user_can('level_10')){//开启并且不是管理团队
$jinsom_comment_danger_limit = (int)jinsom_get_option('jinsom_comment_danger_limit');
$last_comment_time=get_user_meta($user_id,'last_comment_time',true);
if($last_comment_time){//曾经发布过内容
if(time()-$last_comment_time<=$jinsom_comment_danger_limit){//如果现在评论的时间和上次的时间间隔小于安全值，则自动变为风险账户
update_user_meta($user_id,'user_power',4);
update_user_meta($user_id,'danger_reason','连续回复帖子超过限定时间');
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
$data_arr['msg']='评论失败！对方已经将你拉进黑名单！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


//判断是否黑名单[论坛]
$bbs_blacklist=get_term_meta($bbs_id,'bbs_blacklist',true);
$bbs_blacklist_arr=explode(",",$bbs_blacklist); 
if(in_array($user_id,$bbs_blacklist_arr)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='你是该'.$bbs_name.'的黑名单用户，禁止回复！';
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


$content_number=mb_strlen($content,'utf-8');
$jinsom_bbs_comment_words_max=jinsom_get_option('jinsom_bbs_comment_words_max');
if(!$jinsom_bbs_comment_words_max){$jinsom_bbs_comment_words_max=5000;}
if($content_number>$jinsom_bbs_comment_words_max){
$data_arr['code']=0;
$data_arr['msg']='最多只能回复'.$jinsom_bbs_comment_words_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


//禁止回复
if(!comments_open($post_id)&&!jinsom_is_admin($user_id)&&$user_id!=$author_id){//作者和管理团队可以回复
$data_arr['code']=0;
$data_arr['msg']='该帖子已经关闭回复！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//判断是否挖坟
$comment_time=(int)get_term_meta($bbs_id,'bbs_last_reply_time',true);
$post_time=get_the_time('Y-m-d H:i:s',$post_id);
$time_a=(time()-strtotime($post_time))/86400;
if($time_a>$comment_time&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该帖子已超过'.$comment_time.'天的，不再允许进行回复！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}


//回帖权限
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
if(is_user_logged_in()){
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}
$bbs_comment_power=(int)get_term_meta($bbs_id,'bbs_comment_power',true);

if($bbs_comment_power==1){//vip才能回帖
if(!is_vip($user_id)&&!$is_bbs_admin){
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许VIP用户回帖！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}
}else if($bbs_comment_power==2){//认证用户才能回帖
$user_verify=get_user_meta($user_id,'verify',true);
if(!$user_verify&&!$is_bbs_admin){
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许认证用户回帖！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($bbs_comment_power==3){//管理团队才可以回帖
if(!$is_bbs_admin){
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许管理团队回帖！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($bbs_comment_power==4){//关注本论坛才可以回帖
if(!jinsom_is_bbs_like($user_id,$bbs_id)&&!$is_bbs_admin){
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'需要关注之后才允许回帖！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($bbs_comment_power==5){//有头衔的用户
if(!get_user_meta($user_id,'user_honor',true)&&!$is_bbs_admin){
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许拥有头衔的用户回帖！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($bbs_comment_power==6){//指定经验用户才能发帖
$bbs_comment_power_exp=(int)get_term_meta($bbs_id,'bbs_comment_power_exp',true);
$current_user_lv=jinsom_get_user_exp($user_id);//当前用户经验
if($current_user_lv<$bbs_comment_power_exp&&!$is_bbs_admin){//当前用户等级是否大于或等于指定的等级
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许经验值大于或等于'.$bbs_comment_power_exp.'的用户回帖！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else if($bbs_comment_power==7){//指定头衔
if(!$is_bbs_admin){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor){
$bbs_comment_power_honor=get_term_meta($bbs_id,'bbs_comment_power_honor',true);
$comment_power_honor_arr=explode(",",$bbs_comment_power_honor);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($comment_power_honor_arr,$user_honor_arr)){	
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许拥有指定头衔的用户回帖！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许拥有指定头衔的用户回帖！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}else if($bbs_comment_power==8){//指定认证类型
if(!$is_bbs_admin){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$bbs_comment_power_verify=get_term_meta($bbs_id,'bbs_comment_power_verify',true);
$comment_power_verify_arr=explode(",",$bbs_comment_power_verify);
if(!in_array($user_verify_type,$comment_power_verify_arr)){
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许指定的认证用户回帖！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许指定的认证用户回帖！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}



$comment_credit = (int)get_term_meta($bbs_id,'bbs_credit_reply_number',true);//回帖可获得的金币
$comment_exp = (int)get_term_meta($bbs_id,'bbs_exp_reply_number',true);//回帖可获得的经验
$comment_max_times = (int)jinsom_get_option('jinsom_comment_bbs_max');//每日回帖上限次数
$user_comment_times = (int)get_user_meta($user_id,'comment_bbs_times',true);//个人当天回帖的次数


if(is_vip($user_id)){
$jinsom_comment_limit = (int)jinsom_get_option('jinsom_comment_bbs_limit_vip');
}else{
$jinsom_comment_limit = (int)jinsom_get_option('jinsom_comment_bbs_limit');	
}
if($user_comment_times>=$jinsom_comment_limit&&!jinsom_is_admin($user_id)){
$data_arr['code']=3;
$data_arr['msg']=__('你当天回帖次数已达上限<br>开通VIP可提升上限','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


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


$ip = $_SERVER['REMOTE_ADDR'];
$time = current_time('mysql');

if($_POST['type']==1){
$content = str_replace("&nbsp;"," ",$content);//替换&nbsp;  
$content = str_replace('&quot;','"',$content);//防止转义
}else if($_POST['type']==2){
$content =strip_tags($content);//二级回帖过滤标签
}

if(wp_is_mobile()){
$content=htmlspecialchars($content);
$content = str_replace(array("\r", "\n", "\r\n"), "<br/>",$content);//处理换行
}



//敏感词过滤
if(jinsom_get_option('jinsom_baidu_filter_on_off')&&in_array("comment",jinsom_get_option('jinsom_baidu_filter_use_for'))&&!jinsom_is_admin($user_id)){
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

//艾特
$content=jinsom_at($content);



//回复帖子 一级
if(isset($_POST['type'])&&$_POST['type']==1){

$data = array(
'comment_post_ID' =>$post_id,
'comment_content' => $content,
'user_id' => $user_id,
'comment_date' => $time,
'comment_author_IP'=>$ip
);
$comment_id=wp_insert_comment($data);  

if($comment_id){

//更新每次评论上限和获得的奖励
$comment_tips='评论成功！';
if($comment_credit>0){
if($user_comment_times<$comment_max_times){
jinsom_update_credit($user_id,$comment_credit,'add','comment-bbs','回复帖子',1,''); 
jinsom_update_exp($user_id,$comment_exp,'add','回复帖子');
$comment_tips.='<span class="jinsom-icon jinsom-jinbi"></span> +'.$comment_credit.'&nbsp;&nbsp;';
if($comment_exp>0){
$comment_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$comment_exp;	
}
}  
}else if($comment_credit<0){
jinsom_update_credit($user_id,$comment_credit,'cut','comment-bbs','回复帖子，扣除',1,''); 

$comment_tips.='<span class="jinsom-icon jinsom-jinbi"></span> -'.abs($comment_credit).'&nbsp;&nbsp;';
if($comment_exp>0&&$user_comment_times<$comment_max_times){
jinsom_update_exp($user_id,$comment_exp,'add','回复帖子'); 
$comment_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$comment_exp;	
}
}else{//金币等于0
if($comment_exp>0&&$user_comment_times<$comment_max_times){
jinsom_update_exp($user_id,$comment_exp,'add','回复帖子');
$comment_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$comment_exp;	
}
}
update_user_meta($user_id,'comment_bbs_times',$user_comment_times+1);  //更新回复帖子次数

//更新终端
if(wp_is_mobile()){
update_comment_meta($comment_id,'from','mobile');//手机端
}


update_post_meta($post_id, 'last_comment_time', time());//更新帖子最后回复时间
update_user_meta($user_id,'last_comment_time',time());//记录用户最后评论的时间戳


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


//提醒被艾特的用户
preg_match_all('/@([\x7f-\xff^a-zA-z0-9]+)/',$_POST['content'],$arr);
$arr_a=array_unique($arr[1]);//去重
foreach ($arr_a as $arr_b) {//重复@只提醒一次
$arr_b=strip_tags($arr_b);
if(jinsom_nickname_exists($arr_b)){
$at_user_id=jinsom_get_user_id_for_nickname($arr_b);
if($at_user_id!=$user_id&&$author_id!=$at_user_id){//@的用户不等于当前用户并且@用户不等于作者才发送@提醒
if(!jinsom_is_blacklist($at_user_id,$user_id)){//没有被对方拉黑
jinsom_add_tips($at_user_id,$user_id,$post_id,'aite','回帖@了你',$comment_id);
}
}
}
}

//移动端评论回复插入图片
if(isset($_POST['img'])){
$img=strip_tags($_POST['img']);
if($img){
update_comment_meta($comment_id,'img',$img); 
}
}


if($author_id!=$user_id){//并且判断回帖的用户跟楼主不是同一个用户
jinsom_add_tips($author_id,$user_id,$post_id,'comment',strip_tags($content),$comment_id);
}

//更新回帖数--回帖排行榜
$user_comment_number=(int)get_user_meta($user_id,'user_comment_number',true);
update_user_meta($user_id,'user_comment_number',$user_comment_number+1);


$data_arr['code']=1;
$data_arr['msg']=$comment_tips;
$data_arr['id']=$comment_id;
$data_arr['content']=do_shortcode(convert_smilies(jinsom_autolink($content))); //评论成功

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$user_id','$post_id','comment-bbs','$time','$author_id')");

}else{//评论失败
$data_arr['code']=0;
$data_arr['msg']='评论失败！';
}


}//=========一级回帖结束========================





//======二级帖子评论==========================
if(isset($_POST['type'])&&$_POST['type']==2){
$comment_id=$_POST['comment_id'];

$data = array(
'comment_post_ID' =>$post_id,
'comment_content' => $content,
'user_id' => $user_id,
'comment_author_IP'=>$ip,
'comment_parent' =>$comment_id,
'comment_date' => $time
);
$floor_comment_id=wp_insert_comment($data); 


if($floor_comment_id){

//更新每次评论上限和获得的奖励
$comment_tips='评论成功！';
if($comment_credit>0){
if($user_comment_times<$comment_max_times){
jinsom_update_credit($user_id,$comment_credit,'add','comment-bbs','回复楼层帖子',1,'');  
jinsom_update_exp($user_id,$comment_exp,'add','回复楼层帖子');
$comment_tips.='<span class="jinsom-icon jinsom-jinbi"></span> +'.$comment_credit.'&nbsp;&nbsp;';
if($comment_exp>0){
$comment_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$comment_exp;	
}
}  
}else if($comment_credit<0){
jinsom_update_credit($user_id,$comment_credit,'cut','comment-bbs','回复楼层帖子',1,'');  
$comment_tips.='<span class="jinsom-icon jinsom-jinbi"></span> -'.abs($comment_credit).'&nbsp;&nbsp;';

if($comment_exp>0&&$user_comment_times<$comment_max_times){
$comment_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$comment_exp;	
jinsom_update_exp($user_id,$comment_exp,'add','回复楼层帖子');
}
}else{//金币等于0
if($comment_exp>0&&$user_comment_times<$comment_max_times){
jinsom_update_exp($user_id,$comment_exp,'add','回复楼层帖子');
$comment_tips.='<span class="jinsom-icon jinsom-jingyan"></span> +'.$comment_exp;	
}
}
update_user_meta($user_id,'comment_bbs_times',$user_comment_times+1);  //更新回复帖子次数  

//更新终端
if(wp_is_mobile()){
update_comment_meta($floor_comment_id,'from','mobile');//手机端
}else{
update_comment_meta($floor_comment_id,'from','pc');  
}


update_post_meta($post_id, 'last_comment_time', time());//更新帖子最后回复时间
update_user_meta($user_id,'last_comment_time',time());//记录用户最后评论的时间戳

//获取层主ID
$comment_id_data = get_comment($comment_id); 
$comment_author_id=$comment_id_data->user_id;//层主ID
$comment_author_nickname=get_user_meta($comment_author_id,'nickname',true);//层主昵称

//提醒被艾特的用户
preg_match_all('/@([\x7f-\xff^a-zA-z0-9]+)/',$_POST['content'],$arr);
$arr_a=array_unique($arr[1]);//去重
foreach ($arr_a as $arr_b) {//重复@只提醒一次
$arr_b=strip_tags($arr_b);
if(jinsom_nickname_exists($arr_b)){
$at_user_id=jinsom_get_user_id_for_nickname($arr_b);
if($at_user_id!=$user_id&&$author_id!=$at_user_id){//@的用户不等于当前用户并且@用户不等于作者
if(!jinsom_is_blacklist($at_user_id,$user_id)){//没有被对方拉黑
jinsom_add_tips($at_user_id,$user_id,$post_id,'aite','楼层回帖@了你',$comment_id);
}
}
}
}

//判断作者是否被@了
if($author_id!=$user_id){//并且判断回帖的用户跟楼主不是同一个用户
jinsom_add_tips($author_id,$user_id,$post_id,'comment',strip_tags($content),$comment_id);
}

//判断层主ID是否被@了
if(!in_array($comment_author_nickname,$arr_a)){//判断层主ID是否被@了，若没有被@，则向层主推送一条有人回帖的提醒。
if($comment_author_id!=$user_id){//并且判断回帖的用户跟层主不是同一个用户
jinsom_add_tips($comment_author_id,$user_id,$post_id,'comment',strip_tags($content),$comment_id);
}
}

//更新回帖数--回帖排行榜
$user_comment_number=(int)get_user_meta($user_id,'user_comment_number',true);
update_user_meta($user_id,'user_comment_number',$user_comment_number+1);

$data_arr['code']=1;
$data_arr['msg']=$comment_tips;
$data_arr['id']=$floor_comment_id;
$data_arr['content']=convert_smilies(jinsom_autolink($content)); //评论成功

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$user_id','$post_id','comment-bbs-floor','$time','$comment_author_id')");

}else{//评论失败
$data_arr['code']=0;
$data_arr['msg']='评论失败！';
}

}

header('content-type:application/json');
echo json_encode($data_arr);