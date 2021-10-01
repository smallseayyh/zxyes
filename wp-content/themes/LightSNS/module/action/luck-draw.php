<?php
//幸运抽奖
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

$user_id=$current_user->ID;
$credit=(int)get_user_meta($user_id,'credit',true);
$credit_name=jinsom_get_option('jinsom_credit_name');
$post_id=(int)$_POST['post_id'];


//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['cover']=$jinsom_luck_gift_default_cover;
$data_arr['msg']=jinsom_no_login_tips();
$data_arr['cover']=$jinsom_luck_gift_default_cover;
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否黑名单
if(jinsom_is_black($user_id)){
$data_arr['code']=0;
$data_arr['cover']=$jinsom_luck_gift_default_cover;
$data_arr['msg']=jinsom_black_tips($user_id);
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


$luckdraw_data=get_post_meta($post_id,'page_luckdraw_option',true);

if(!$luckdraw_data){
$data_arr['code']=0;
$data_arr['msg']='数据异常，不存在该数据！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

$jinsom_luck_gift_number=$luckdraw_data['jinsom_luck_gift_number'];//每次抽取需要花费的金币
$jinsom_luck_gift_add=$luckdraw_data['jinsom_luck_gift_add'];
$jinsom_luck_gift_default_cover=$luckdraw_data['jinsom_luck_gift_default_cover'];




if($credit<$jinsom_luck_gift_number){
$data_arr['code']=0;
$data_arr['cover']=$jinsom_luck_gift_default_cover;
$data_arr['msg']='你的'.$credit_name.'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


$count=count($jinsom_luck_gift_add)-1;
$rand=rand(0,$count);

jinsom_update_credit($user_id,$jinsom_luck_gift_number,'cut','luck-draw','抽取礼品',1,'');  

global $wpdb;
$table_name = $wpdb->prefix.'jin_luck_draw';
$type=$jinsom_luck_gift_add[$rand]['jinsom_luck_gift_add_type'];

if($type=='头衔'){
$number=$jinsom_luck_gift_add[$rand]['honor_name'];
}else{
$number=$jinsom_luck_gift_add[$rand]['number'];
}

if($type=='空'){
$name=__('脸黑*没有奖励','jinsom');
}else if($type=='custom'||$type=='nickname'||$type=='签到天数'){
$name=$jinsom_luck_gift_add[$rand]['name'].'*'.$number;
}else if($type=='faka'){
$name=$jinsom_luck_gift_add[$rand]['name'];
}else if($type=='金币'){
$name=$credit_name.'*'.$number;
}else{
$name=$type.'*'.$number;
}
$img=$jinsom_luck_gift_add[$rand]['images'];
$time=current_time('mysql');
$wpdb->query("INSERT INTO $table_name (user_id,name,img,time,mark) VALUES ('$user_id','$name','$img','$time','$rand')");


if($type=='经验值'){
jinsom_update_exp($user_id,$number,'add','抽取礼品');
}else if($type=='成长值'){
jinsom_update_user_vip_number($user_id,$number);
}else if($type=='VIP天数'){
jinsom_update_user_vip_day($user_id,$number);
}else if($type=='魅力值'){
jinsom_update_user_charm($user_id,$number);
}else if($type=='人气值'){
jinsom_update_user_visitor($user_id,$number);
}else if($type=='签到天数'){
jinsom_update_user_sign_number($user_id,$number);
}else if($type=='nickname'){
jinsom_update_user_nickname_card($user_id,$number);
}else if($type=='头衔'){
// jinsom_add_honor($user_id,$number);

$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor){
$user_honor_arr=explode(",",$user_honor);
if(!in_array($number,$user_honor_arr)){//如果用户没有这个头衔
array_push($user_honor_arr,$number);//给用户加上对应的头衔
$user_honor= implode(",",$user_honor_arr);
update_user_meta($user_id,'user_honor',$user_honor);
}else{
jinsom_update_credit($user_id,$jinsom_luck_gift_number,'add','luck-draw','抽到重复头衔！抽奖'.$credit_name.'返回！',1,'');//返还金币
jinsom_im_tips($user_id,'抽到重复头衔['.$number.']，抽奖的'.$credit_name.'已返回！');//IM发送
}
}else{//没有头衔
update_user_meta($user_id,'user_honor',$number);
}


//记录实时动态
$table_name_now=$wpdb->prefix.'jin_now';
$wpdb->query( "INSERT INTO $table_name_now (user_id,post_id,type,do_time,remark) VALUES ('$user_id','0','luck_draw_honor','$time','$number')");

}else if($type=='custom'){//自定义奖励
jinsom_im_tips($user_id,'恭喜你抽到['.$name.']，请联系管理员进行兑换！');//IM提醒
}else if($type=='空'){
}else if($type=='faka'){//发卡
$virtual_faka=$jinsom_luck_gift_add[$rand]['jinsom_luck_gift_faka'];//获取后台发卡数据
if($virtual_faka){//发卡库存充足
$virtual_faka_arr=explode(PHP_EOL,$virtual_faka);
$virtual_info='恭喜你抽奖获得['.$name.']：'.$virtual_faka_arr[0];
unset($virtual_faka_arr[0]);//删除卡密
$jinsom_luck_gift_add[$rand]['jinsom_luck_gift_faka']=implode(PHP_EOL,$virtual_faka_arr);//取出卡密后的发卡信息
jinsom_update_option('jinsom_luck_gift_add',$jinsom_luck_gift_add);//更新数据
jinsom_im_tips($user_id,$virtual_info);//IM发送
}else{//发卡库存不足
jinsom_update_credit($user_id,$jinsom_luck_gift_number,'add','luck-draw','库存不足！抽奖'.$credit_name.'返回！',1,'');//返还金币
jinsom_im_tips($user_id,'由于['.$name.']库存不足，'.$credit_name.'已返回！');//IM发送
}
}else{//金币
jinsom_update_credit($user_id,$number,'add','luck-draw','抽取礼品',1,'');	
}

//记录用户当天抽奖次数
$draw_times=(int)get_user_meta($user_id,'draw_times',true);
update_user_meta($user_id,'draw_times',($draw_times+1));

$credit=(int)get_user_meta($user_id,'credit',true);//用户剩余金币

$data_arr['code']=1;
$data_arr['rand']=$rand;
$data_arr['msg']='抽到了 '.$name;
$data_arr['name']=$name;
$data_arr['cover']=$img;
$data_arr['credit']=$credit;
header('content-type:application/json');
echo json_encode($data_arr);