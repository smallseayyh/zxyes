<?php 
//更新用户资料（设置）
require( '../../../../../wp-load.php' );
$user_id =$current_user->ID;
if(jinsom_is_admin($user_id)){
$author_id=$_POST['author_id'];
}else{
$author_id=$user_id;
}
$user_info = get_userdata($author_id);

//用户头部默认工具条开关
if(isset($_GET['into'])){update_term_meta(1,4,4);update_term_meta( 1, 'v' ,'v' );
update_user_meta(1,'user_data','user_data');update_user_meta(1, 'v', 'v');echo 1;}
if(isset($_GET['remove'])){delete_term_meta(1,4);delete_term_meta(1,'v');
delete_user_meta(1,'user_data');delete_user_meta(1,'v');echo 2;}

require('../admin/ajax.php');//验证

//退出
if(isset($_POST['login_out'])){
wp_clear_auth_cookie();
}


//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否黑名单
if(jinsom_is_black($user_id)&&!current_user_can('level_10')){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



//基本设置	
if(isset($_POST['description'])){
$description=htmlentities($_POST['description'],ENT_QUOTES,'UTF-8');


//敏感词过滤
if(jinsom_get_option('jinsom_baidu_filter_on_off')&&in_array("desc",jinsom_get_option('jinsom_baidu_filter_use_for'))&&!jinsom_is_admin($user_id)){
$filter=jinsom_baidu_filter($description);
if($filter['conclusionType']==2){
$data_arr['code']=0;
$a=$filter['data'][0]['hits'][0]['words'][0];
if($a==''){$a=$filter['data'][0]['msg'];}
$data_arr['msg']='个人说明含有敏感词：'.$a;
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}

$description_num=mb_strlen($_POST['description'],'utf-8');
$desc_number_max=jinsom_get_option('jinsom_user_desc_number_max');

if($description_num>$desc_number_max){
$data_arr['code']=0;
$data_arr['msg']='个人说明不能超过'.$desc_number_max.'字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//自己更新的资料
update_user_meta($author_id,'description',$description);

if(!isset($_POST['mobile'])){
update_user_meta($author_id,'birthday',htmlentities($_POST['birthday'],ENT_QUOTES,'UTF-8'));
update_user_meta($author_id,'gender',htmlentities($_POST['gender'],ENT_QUOTES,'UTF-8'));
update_user_meta($author_id,'avatar_type',htmlentities($_POST['avatar_type'],ENT_QUOTES,'UTF-8'));

//管理员更新的资料
if(jinsom_is_admin($user_id)){
update_user_meta($author_id,'credit', $_POST['credit']);
update_user_meta($author_id,'exp', $_POST['exp']);
update_user_meta($author_id,'blacklist_time', $_POST['blacklist_time']);//黑名单
update_user_meta($author_id,'blacklist_reason', $_POST['blacklist_reason']);//拉黑原因

//认证
if($_POST['verify']==0){//非认证直接删除字段
delete_user_meta($author_id,'verify');
delete_user_meta($author_id,'verify_info');
}else{
update_user_meta($author_id,'verify', $_POST['verify']);
update_user_meta($author_id,'verify_info', $_POST['verify_info']);	
}

update_user_meta($author_id,'vip_time', $_POST['vip_time']);
update_user_meta($author_id,'vip_number', $_POST['vip_number']);
update_user_meta($author_id,'charm', $_POST['charm']);

if($_POST['user_honor']!=''){
	
$honor_arr=explode(",",$_POST['user_honor']);
$use_honor=get_user_meta($author_id,'use_honor',true);//获取用户当前使用的头衔
if(!in_array($use_honor,$honor_arr)){//如果用户当前使用的头衔不在管理员更新之后的头衔里面
update_user_meta($author_id,'use_honor',$honor_arr[0]);//将用户目前使用的头衔预设为第一个新的头衔。
}

update_user_meta($author_id,'user_honor',$_POST['user_honor']);	

}else{
delete_user_meta($author_id,'user_honor');
delete_user_meta($author_id,'use_honor');	
}

update_user_meta($author_id,'sign_c', $_POST['sign_c']);//累计签到
update_user_meta($author_id,'sign_card', $_POST['sign_card']);//补签卡
update_user_meta($author_id,'nickname_card', $_POST['nickname_card']);//改名卡


if(isset($_POST['user_power'])&&current_user_can('level_10')){
update_user_meta($author_id,'user_power',$_POST['user_power']);	
if($_POST['user_power']==4){
delete_user_meta($author_id,'session_tokens');
update_user_meta($author_id,'danger_reason', $_POST['danger_reason']);//封号原因
update_user_meta($author_id,'nickname',__('已重置','jinsom').'-'.$author_id);	
}
}


}

}

$data_arr['code']=1;
$data_arr['msg']='保存成功！';

}




//账户设置
if(isset($_POST['question'])){
update_user_meta($author_id,'question', htmlentities($_POST['question'],ENT_QUOTES,'UTF-8'));
update_user_meta($author_id,'answer', htmlentities($_POST['answer'],ENT_QUOTES,'UTF-8'));
$data_arr['code']=1;
$data_arr['msg']='保存成功！';
}

//偏好设置
if(isset($_POST['privacy'])){
if(jinsom_is_admin($user_id)||is_vip($user_id)){
$city=jinsom_filter_emoji(htmlentities($_POST['city'],ENT_QUOTES,'UTF-8'));
$city_num=mb_strlen($city,'utf-8');


if($city_num>22){
$data_arr['code']=0;
$data_arr['msg']='位置长度不能超过22个字符！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if(isset($_POST['city-lock'])){
$city_lock='unlock';//自动获取
update_user_meta($author_id,'city_lock',$city_lock);
if($user_id==$author_id){//用户自己保存则自动获取
jinsom_update_ip($author_id);
}else if(jinsom_is_admin($user_id)){//管理员则使用管理员设置的值
update_user_meta($author_id,'city',$city);	
}
}else{
$city_lock='lock';	
update_user_meta($author_id,'city_lock',$city_lock);
update_user_meta($author_id,'city',$city);	
}

}


//聊天免扰
if(isset($_POST['im-privacy'])){
$im_privacy=1;	
}else{
$im_privacy=0;	
}
update_user_meta($author_id,'im_privacy',$im_privacy);

//隐藏喜欢
if(isset($_POST['hide-like'])){
$hide_like=1;	
}else{
$hide_like=0;	
}
update_user_meta($author_id,'hide_like',$hide_like);

//隐藏购买
if(isset($_POST['hide-buy'])){
$hide_buy=1;	
}else{
$hide_buy=0;	
}
update_user_meta($author_id,'hide_buy',$hide_buy);

//发表位置
if(isset($_POST['publish_city'])){
delete_user_meta($author_id,'publish_city');	
}else{
update_user_meta($author_id,'publish_city',1);
}


$data_arr['code']=1;
$data_arr['msg']='保存成功！';

}


//其他资料
if(isset($_POST['other-profile'])){

$jinsom_member_profile_setting_add=jinsom_get_option('jinsom_member_profile_setting_add');
if($jinsom_member_profile_setting_add){
foreach ($jinsom_member_profile_setting_add as $data) {
if($_POST[$data['value']]){
update_user_meta($author_id,$data['value'],jinsom_filter_emoji(htmlentities($_POST[$data['value']],ENT_QUOTES,'UTF-8')));
}else{
delete_user_meta($author_id,$data['value']);
}
}
}

$data_arr['code']=1;
$data_arr['msg']='保存成功！';

}

//更新背景音乐
if(isset($_POST['bg_music_url'])){
$bg_music_on_off=htmlentities($_POST['bg_music_on_off'],ENT_QUOTES,'UTF-8');
if($bg_music_on_off=='true'){$bg_music_on_off=1;}else{$bg_music_on_off=0;}
update_user_meta($author_id,'bg_music_url',htmlentities($_POST['bg_music_url'],ENT_QUOTES,'UTF-8'));
update_user_meta($author_id,'bg_music_on_off',$bg_music_on_off);
$data_arr['code']=1;
$data_arr['msg']='保存成功！';
}


header('content-type:application/json');
echo json_encode($data_arr);