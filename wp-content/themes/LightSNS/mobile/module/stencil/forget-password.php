<?php 
//忘记密码
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$type=strip_tags($_POST['type']);
$username=strip_tags($_POST['username']);

if($type=='get-type'){

if(!$username){
$data_arr['code']=0;
$data_arr['msg']='请输入手机号/邮箱！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

if(!is_email($username)){//手机号

if(!jinsom_phone_exists($username)){
$data_arr['code']=0;
$data_arr['msg']='你输入的手机号不存在！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

$user_id=jinsom_get_user_id_for_phone($username);


}else{//输入的是邮箱


if(!email_exists($username)){
$data_arr['code']=0;
$data_arr['msg']='你输入的手机号/邮箱不存在！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
$user_id=jinsom_get_user_id_for_mail($username);

}


$user_info=get_userdata($user_id);
$question=$user_info->question;
$answer=$user_info->answer;
$phone=$user_info->phone;
$email=$user_info->user_email;
$html='<div class="jinsom-forget-password-type list-block"><ul>';

if(!$email||jinsom_get_option('jinsom_email_style'=='close')){
$html.='<li class="no"><label class="label-radio item-content"><input type="radio" disabled=""><div class="item-media"><i class="icon icon-form-radio"></i></div><div class="item-inner"><div class="item-title">邮箱</div></div></label></li>';
}else{
$html.='<li><label class="label-radio item-content"><input type="radio" name="style" value="email" checked=""><div class="item-media"><i class="icon icon-form-radio"></i></div><div class="item-inner"><div class="item-title">邮箱</div></div></label></li>';
}

if(!$phone){
$html.='<li class="no"><label class="label-radio item-content"><input type="radio" disabled=""><div class="item-media"><i class="icon icon-form-radio"></i></div><div class="item-inner"><div class="item-title">'.__('手机号','jinsom').'</div></div></label></li>';
}else{
$html.='<li><label class="label-radio item-content"><input type="radio" name="style" value="phone"><div class="item-media"><i class="icon icon-form-radio"></i></div><div class="item-inner"><div class="item-title">'.__('手机号','jinsom').'</div></div></label></li>';
}

if($question==''||$answer==''){
$html.='<li class="no"><label class="label-radio item-content"><input type="radio" disabled=""><div class="item-media"><i class="icon icon-form-radio"></i></div><div class="item-inner"><div class="item-title">'.__('密保','jinsom').'</div></div></label></li>';
}else{
$html.='<li><label class="label-radio item-content"><input type="radio" name="style" value="question"><div class="item-media"><i class="icon icon-form-radio"></i></div><div class="item-inner"><div class="item-title">'.__('密保','jinsom').'</div></div></label></li>';
}




$html.='<div class="jinsom-get-password-tips">若以上找回方式不可用，请尝试联系管理员</div></ul></div><div class="jinsom-mobile-login-form-btn reg">
<div class="jinsom-login-btn" onclick=\'jinsom_forget_password_last_form('.$user_id.',"'.$username.'")\'>下一步</div></div>';


$data_arr['code']=1;
$data_arr['html']=$html;

header('content-type:application/json');
echo json_encode($data_arr);
}