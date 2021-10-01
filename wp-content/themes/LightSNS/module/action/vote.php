<?php
//投票
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$user_info = get_userdata($user_id);
$user_registered=strtotime($user_info->user_registered);
$post_id=$_POST['post_id'];
$author_id=jinsom_get_user_id_post($post_id);
$post_type=get_post_meta($post_id,'post_type',true);

//判断是否登录
if (!is_user_logged_in()) { 
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


//判断新注册用户是否在允许投票的时间范围
$jinsom_vote_allow_time = jinsom_get_option('jinsom_vote_allow_time');
if(time()-$user_registered<60*60*$jinsom_vote_allow_time){
$data_arr['code']=0;
$data_arr['msg']='新注册用户需要'.$jinsom_vote_allow_time.'小时后才可以进行投票操作！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//数据异常
if($post_type!='vote'){
$data_arr['code']=0;
$data_arr['msg']='数据异常！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//需要绑定手机号才能使用
$bind_phone_use_for=jinsom_get_option('jinsom_bind_phone_use_for');
if($bind_phone_use_for&&in_array("vote",$bind_phone_use_for)&&!current_user_can('level_10')){
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
if($bind_email_use_for&&in_array("vote",$bind_email_use_for)&&!current_user_can('level_10')){
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


if(isset($_POST['vote'])){
$vote=$_POST['vote'];
$vote_times=get_post_meta($post_id,'vote_times',true);//投票次数
$vote_time=strtotime(get_post_meta($post_id,'vote_time',true));//投票时间戳
$vote_arr=explode(',',$vote);//将投票数据分割为数组
$count=count($vote_arr);

if(time()>$vote_time){
$data_arr['code']=0;
$data_arr['msg']='投票已经结束，你不能再进行投票！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

//超过设定的投票项数
if($count>$vote_times){
$data_arr['code']=0;
$data_arr['msg']='最多只能选择'.$vote_times.'项进行投票！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if(jinsom_is_vote($user_id,$post_id)){
$data_arr['code']=0;
$data_arr['msg']='你已经投过票了！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


$vote_data=get_post_meta($post_id,'vote_data',true);
$vote_data_arr = explode(',',$vote_data);
$vote_data_num=count($vote_data_arr);

foreach ($vote_arr as $vote_arrs){
$arr_num=($vote_arrs*2)-1;//该项对应的投票数的下标
$vote_data_arr[$arr_num]=$vote_data_arr[$arr_num]+1;//更新每项的投票数
}
$vote_data_arr[$vote_data_num-1]=$vote_data_arr[$vote_data_num-1]+$count;//更新总投票数
$vote_data_last=implode(',',$vote_data_arr);//得到最终的投票数据
update_post_meta($post_id,'vote_data',$vote_data_last);//更新数据
jinsom_add_vote($user_id,$post_id);//写入数据库

$data_arr['code']=1;
$data_arr['msg']='投票成功！';


jinsom_add_tips($author_id,$user_id,$post_id,'activity','参与了你发布的投票','参与了');

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$post_id','vote','$time')");


}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';
}

header('content-type:application/json');
echo json_encode($data_arr);