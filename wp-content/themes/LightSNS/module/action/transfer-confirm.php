<?php
//转账最后一步  确定转账
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$credit=get_user_meta($user_id,'credit',true);
$transfer_poundage = jinsom_get_option('jinsom_transfer_poundage');//每笔转账手续
$transfer_poundage_mini = jinsom_get_option('jinsom_transfer_poundage_mini');//最低转账手续费
$transfer_mini = jinsom_get_option('jinsom_transfer_mini');//转账最低金额
$transfer_max = jinsom_get_option('jinsom_transfer_max');//转账最高金额
$transfer_power = jinsom_get_option('jinsom_transfer_power');
$transfer_exp = jinsom_get_option('jinsom_transfer_exp');
$credit_name=jinsom_get_option('jinsom_credit_name');

$author_id=$_POST['author_id'];
$number=$_POST['number'];
$mark=htmlspecialchars($_POST['mark']);//入库的时候已经过滤
$mark_number=mb_strlen($mark,'utf-8');//转账字数备注

$poundage_number=(int)(($transfer_poundage/100)*$number);//手续费
if($poundage_number<$transfer_poundage_mini){//如果手续费低于最低手续费，则按最低手续费扣取
$poundage_number=$transfer_poundage_mini;
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


if($transfer_power=='vip'){
if(!is_vip($user_id)){
$data_arr['code']=0;
$data_arr['msg']='VIP用户才可以使用转账功能！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($transfer_power=='verify'){
$verify=get_user_meta($user_id,'verify',true);
if(!$verify){
$data_arr['code']=0;
$data_arr['msg']='认证用户才可以使用转账功能！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}
}else if($transfer_power=='exp'){
$exp=(int)get_user_meta($user_id,'exp',true);
if($exp<$transfer_exp){
$data_arr['code']=0;
$data_arr['msg']='经验值达到'.$transfer_exp.'的用户才可以使用转账功能！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

}



if($user_id==$author_id){
$data_arr['code']=0;
$data_arr['msg']='你不能给你自己转账！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if(!is_numeric($number)){
$data_arr['code']=0;
$data_arr['msg']='转账的金额不合法！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();			
}


if($number>$transfer_max||$number<$transfer_mini){
$data_arr['code']=0;
$data_arr['msg']='转账的范围：'.$transfer_mini.$credit_name.'-'.$transfer_max.$credit_name.'！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if($mark_number>10){
$data_arr['code']=0;
$data_arr['msg']='转账备注不能超过10个字！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if($credit<$number+$poundage_number){//金币小于转账金额+手续费
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！本次转账手续费需要'.$poundage_number.$credit_name;
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}



$transfer_number=$number-$poundage_number;//扣除手续费之后的本金
$nickname=get_user_meta($user_id,'nickname',true);
jinsom_update_credit($user_id,$number+$poundage_number,'cut','transfer','转账给'.get_user_meta($author_id,'nickname',true).'(含手续费)',1,'');  
jinsom_update_credit($author_id,$number,'add','transfer',$nickname.'给你转了'.$number.$credit_name.'。备注：'.$mark,1,'');
jinsom_add_tips($author_id,$user_id,0,'transfer','给你转了'.$number.$credit_name,'给你转了');

$data_arr['code']=1;
$data_arr['transfer_number']=$number+$poundage_number;
$data_arr['msg']='转账成功！';

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$author_id','transfer','$time')");


header('content-type:application/json');
echo json_encode($data_arr);