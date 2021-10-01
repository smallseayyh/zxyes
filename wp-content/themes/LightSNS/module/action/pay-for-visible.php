<?php 
//付费可见
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id=$current_user->ID;



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

//需要绑定手机号才能使用
$bind_phone_use_for=jinsom_get_option('jinsom_bind_phone_use_for');
if($bind_phone_use_for&&in_array("buy",$bind_phone_use_for)&&!current_user_can('level_10')){
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
if($bind_email_use_for&&in_array("buy",$bind_email_use_for)&&!current_user_can('level_10')){
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


//使用权限
$power=jinsom_get_option('jinsom_buy_power');
if($power=='vip'){//VIP用户
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限会员用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='verify'){//认证用户
$user_verify=get_user_meta($user_id,'verify',true);
if(!$user_verify&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限认证用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='honor'){//头衔用户
$user_honor=get_user_meta($user_id,'user_honor',true);
if(!$user_honor&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限拥有头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='admin'){//管理团队
if(!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限管理团队使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='exp'){//指定经验
$user_exp=jinsom_get_user_exp($user_id);//当前用户经验
$im_exp = jinsom_get_option('jinsom_buy_power_exps');
if($user_exp<$im_exp&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限经验值大于'.$im_exp.'的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='honor_arr'){//指定头衔
if(!jinsom_is_admin($user_id)){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$honor_arr=jinsom_get_option('jinsom_buy_power_honor_arr');
$publish_power_honor_arr=explode(",",$honor_arr);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($publish_power_honor_arr,$user_honor_arr)){	
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定头衔的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}else if($power=='verify_arr'){//指定认证类型
if(!jinsom_is_admin($user_id)){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$verify_arr=jinsom_get_option('jinsom_buy_power_verify_arr');
$publish_power_verify_arr=explode(",",$verify_arr);
if(!in_array($user_verify_type,$publish_power_verify_arr)){
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定认证类型的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该功能仅限指定认证类型的用户使用！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
}


$user_buy_times=(int)get_user_meta($user_id,'buy_times',true);
if(is_vip($user_id)){
$max_buy_time=jinsom_get_option('jinsom_vip_user_buy_times');
}else{
$max_buy_time=jinsom_get_option('jinsom_normal_user_buy_times');
}
if($user_buy_times>=$max_buy_time){
$data_arr['code']=0;
$data_arr['msg']='你今天购买次数已达上限，请明天再来！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 	
}


if(isset($_POST['post_id'])){

$ip = $_SERVER['REMOTE_ADDR'];
$post_id=$_POST['post_id'];

$price=(int)get_post_meta($post_id,'post_price',true);	
if(is_vip($user_id)){
$vip_discount=jinsom_vip_discount($user_id);
$price=ceil($price*$vip_discount);
}


//判断是否为付费内容
if(!$price&&!is_vip($user_id)){//只有会员才有可能是免费购买。
$data_arr['code']=0;
$data_arr['price']=$price;
$data_arr['msg']='非法操作！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}

$author_id=jinsom_get_user_id_post($post_id);
$pay_result=jinsom_get_pay_result($user_id,$post_id);
$credit=get_user_meta($user_id,'credit',true);

//判断是否将对方拉黑
if(jinsom_is_blacklist($author_id,$user_id)){
$data_arr['code']=0;
$data_arr['msg']='购买失败！对方已经将你拉进黑名单！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if($pay_result){//已经购买
$data_arr['code']=0;
$data_arr['msg']='该内容你已经购买过了，请不要重复购买！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}



if($credit<$price){
$data_arr['code']=3;
$data_arr['msg']='你的'.jinsom_get_option('jinsom_credit_name').'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit(); 
}


$status=jinsom_add_pay_content($user_id,$post_id);//记录已经购买了内容
if($status){//购买成功

jinsom_update_credit($user_id,$price,'cut','buy-post','购买付费内容',1,'');

$buy_poundage=jinsom_get_option('jinsom_buy_post_poundage');
if($buy_poundage){
$price=$price-(int)($price*($buy_poundage)/100);
}

if(!is_vip($user_id)){
jinsom_update_credit($author_id,$price,'add','buy-post','别人购买了你的付费内容',1,'');
}else{
jinsom_update_credit($author_id,$price,'add','buy-post','会员用户购买了你的付费内容',1,'');

//记录用户今天折扣次数
if($vip_discount>=0&&$vip_discount<1){
$discount_times=(int)get_user_meta($user_id,'discount_times',true);
update_user_meta($user_id,'discount_times',$discount_times+1);
}


}

jinsom_add_tips($author_id,$user_id,$post_id,'pay','购买了你内容',$price);//添加提示


if(jinsom_get_option('jinsom_buy_post_show_comment_on_off')){//是否在评论列表显示付费评论
$time = current_time('mysql');
$data = array(
'comment_post_ID' => $post_id,
'comment_content' => '<i class="fa fa-shopping-cart"></i> 购买了付费内容',
'user_id' => $user_id,
'comment_date' => $time,
'comment_approved' => 1,
'comment_author_IP'=>$ip,
);
$comment_id=wp_insert_comment($data); 


update_post_meta($post_id, 'last_comment_time', time());//论坛购买付费内容插入最后回复字段	
update_comment_meta($comment_id,'comment_type','pay');
update_comment_meta($comment_id,'delete',1);//禁止删除

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



//更新评论来自
if(wp_is_mobile()){
update_comment_meta($comment_id,'from','mobile');
}else{
update_comment_meta($comment_id,'from','pc');  
}

}

//文章购买次数
$buy_times=(int)get_post_meta($post_id,'buy_times',true);
update_post_meta($post_id,'buy_times',$buy_times+1);


//用户购买次数
update_user_meta($user_id,'buy_times',$user_buy_times+1);

//


// $data_arr['post_url']=jinsom_mobile_post_url($post_id);
$data_arr['code']=1;
$data_arr['msg']='购买成功！';

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$post_id','buy','$time')");


}else{
$data_arr['code']=0;
$data_arr['msg']='购买失败！100056';
}

}else{
$data_arr['code']=0;
$data_arr['msg']='非法数据！';  
}

header('content-type:application/json');
echo json_encode($data_arr);