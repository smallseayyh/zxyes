<?php
//转账第一步
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$transfer_poundage = jinsom_get_option('jinsom_transfer_poundage');//每笔转账手续
$transfer_poundage_mini = jinsom_get_option('jinsom_transfer_poundage_mini');//最低转账手续费
$transfer_power = jinsom_get_option('jinsom_transfer_power');
$transfer_exp = jinsom_get_option('jinsom_transfer_exp');

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

if($transfer_power=='vip'){
if(!is_vip($user_id)){
$data_arr['code']=0;
$data_arr['msg']='会员用户才可以使用转账功能！';
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

$nickname=$_POST['nickname'];

$user_query = new WP_User_Query( array ( 
'meta_key' => 'nickname',
'count_total'=>false,
'meta_value' => $nickname,//搜昵称
'number'=> 1
));
if (!empty($user_query->results)){
foreach ($user_query->results as $user){
$author_id=$user->ID;	
}

if($user_id==$author_id){
$data_arr['code']=0;
$data_arr['msg']='你不能给你自己转账！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

$data_arr['code']=1;
$data_arr['author_id']=$author_id;
$data_arr['content']='
<div class="jinsom-transfer-confirm-form" data="'.$author_id.'">
<div class="jinsom-transfer-confirm-avatar">'.jinsom_avatar($author_id,'100',avatar_type($author_id)).jinsom_verify($author_id).'</div>
<div class="name">'.jinsom_nickname_link($author_id).'</div>
<div class="info">
<span>I D：<i>'.$author_id.'</i></span>
<span>粉丝：<i>'.jinsom_follower_count($author_id).'</i></span>
<span>关注：<i>'.jinsom_following_count($author_id).'</i></span>
</div>
<div class="tips">转账手续费'.$transfer_poundage.'%/笔，最低'.$transfer_poundage_mini.jinsom_get_option('jinsom_credit_name').'/笔</div>
<div class="number"><input id="jinsom-pop-transfer-number" placeholder="请输入转账金额"></div>
<div class="mark"><textarea id="jinsom-pop-transfer-mark" placeholder="转账备注(10个字内)"></textarea></div>
<div class="btn opacity" onclick="jinsom_transfer_confirm()">确定转账</div>
</div>

';
$data_arr['msg']='成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='你输入的昵称不存在！';	
}



header('content-type:application/json');
echo json_encode($data_arr);