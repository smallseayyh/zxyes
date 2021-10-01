<?php 
//获取我的粉丝数据 || 我关注的用户
require( '../../../../../../wp-load.php' );
$user_id=(int)$_POST['user_id'];
$page=(int)$_POST['page'];
$type=strip_tags($_POST['type']);
if(isset($_POST['number'])){
$number=(int)$_POST['number'];
}else{
$number=20;
}
$offset = ($page-1)*$number;

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';

if($type=='following'){//我关注的
$follow_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  follow_user_id='$user_id' AND follow_status IN(1,2) ORDER BY follow_time DESC limit $offset,$number;");
}else{
$follow_data = $wpdb->get_results("SELECT * FROM $table_name WHERE  user_id='$user_id' AND follow_status IN(1,2) ORDER BY follow_time DESC limit $offset,$number;");	
}




if($follow_data){
$data_arr['code']=1;
$data_arr['data']=array();
foreach ($follow_data as $data){
$follow_arr=array();

if($type=='following'){//我关注的
$follow_user_id=$data->user_id;
}else{
$follow_user_id=$data->follow_user_id;
}

$desc=get_user_meta($follow_user_id,'description',true);
if(!$desc){$desc=jinsom_get_option('jinsom_user_default_desc_b');}

$follow_arr['author_id']=$follow_user_id;
$follow_arr['nickname']=jinsom_nickname($follow_user_id);
$follow_arr['nickname_link']=jinsom_nickname_link($follow_user_id);
$follow_arr['avatar']=jinsom_avatar($follow_user_id,'40',avatar_type($follow_user_id));
$follow_arr['verify']=jinsom_verify($follow_user_id);
$follow_arr['vip']=jinsom_vip($follow_user_id);
$follow_arr['desc']=$desc;
if(wp_is_mobile()){
$follow_arr['link']=jinsom_mobile_author_url($follow_user_id);
$follow_arr['follow']=jinsom_mobile_follower_list_button($user_id,$follow_user_id);
}else{
$follow_arr['link']=jinsom_userlink($follow_user_id);
$follow_arr['follow']=jinsom_follow_button_home($follow_user_id);	
$follow_arr['mark']=jinsom_lv($follow_user_id).jinsom_vip($follow_user_id).jinsom_honor($follow_user_id);
}
array_push($data_arr['data'],$follow_arr);
}
}else{
$data_arr['code']=0;
}

header('content-type:application/json');
echo json_encode($data_arr);