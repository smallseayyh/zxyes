<?php
//修改昵称
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$name_min = jinsom_get_option('jinsom_reg_name_min');
$name_max = jinsom_get_option('jinsom_reg_name_max');
if(isset($_POST['nickname'])){
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$user_id=$_POST['author_id'];//如果是管理员 则使用该主页的对应的用户id
}
$old_nickname=get_user_meta($user_id,'nickname',true);
$nickname=jinsom_filter_emoji(jinsom_trimall(htmlentities(strip_tags($_POST['nickname']),ENT_QUOTES,'UTF-8')));
$name_num=mb_strlen($nickname,'utf-8');

if(empty($nickname)||!$user_id){//空昵称、没有登录
$data_arr['code']=0;
$data_arr['msg']='你输入的数据不合法！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 	
}

//判断是否黑名单
if(jinsom_is_black($user_id)&&!jinsom_is_admin($current_user->ID)){
$data_arr['code']=0;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}

if($old_nickname==$nickname){//修改的昵称和当前昵称相同
$data_arr['code']=1;
$data_arr['nickname']=$nickname;
$data_arr['msg']='修改成功！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 	
}

if($name_num > $name_max||$name_num < $name_min){
$data_arr['code']=0;
$data_arr['msg']='昵称长度为'.$name_min.'-'.$name_max.'字符';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}


//敏感词过滤
if(jinsom_get_option('jinsom_baidu_filter_on_off')&&in_array("nickname",jinsom_get_option('jinsom_baidu_filter_use_for'))&&!jinsom_is_admin($user_id)){
$filter=jinsom_baidu_filter($nickname);
if($filter['conclusionType']==2){
$data_arr['code']=0;
$a=$filter['data'][0]['hits'][0]['words'][0];
if($a==''){$a=$filter['data'][0]['msg'];}
$data_arr['msg']='昵称含有敏感词：'.$a;
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}

$nickname=str_replace(' ','',$nickname);//去掉空格

if(jinsom_nickname_exists($nickname)){
$data_arr['code']=0;
$data_arr['msg']='该昵称已经存在，请重新输入！';
}else{


if(jinsom_get_option('jinsom_nickname_card_on_off')&&!jinsom_is_admin($current_user->ID)){//开启了改名卡功能
$nickname_card=(int)get_user_meta($user_id,'nickname_card',true);
if(!$nickname_card){
$data_arr['code']=0;
$data_arr['msg']='修改昵称需要消耗一张改名卡，你的改名卡不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}else{
update_user_meta($user_id,'nickname_card',$nickname_card-1);//消耗一张改名卡
}
}




$status=update_user_meta($user_id,'nickname',$nickname);	
if($status){
$data_arr['code']=1;
$data_arr['nickname']=$nickname;
$data_arr['msg']='修改成功！';	
$data_arr['self']=$current_user->ID==$_POST['author_id']?1:0;//修改自己的资料的时候


//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time,remark) VALUES ('$user_id','0','update_nickname','$time','$old_nickname')");


}else{
$data_arr['code']=0;
$data_arr['msg']='该昵称不合法，请重新输入！';		
}

}

header('content-type:application/json');
echo json_encode($data_arr);
}



?>