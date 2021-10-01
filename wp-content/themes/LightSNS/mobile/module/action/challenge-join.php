<?php 
//参与挑战
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$credit_name=jinsom_get_option('jinsom_credit_name');
$challenge_poundage=jinsom_get_option('jinsom_challenge_poundage');


//判断是否登录
if (!is_user_logged_in()){ 
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


//使用权限
$power=jinsom_get_option('jinsom_challenge_power');
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
$im_exp=jinsom_get_option('jinsom_challenge_power_exps');
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
$honor_arr=jinsom_get_option('jinsom_challenge_power_honor_arr');
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
$verify_arr=jinsom_get_option('jinsom_challenge_power_verify_arr');
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




$id=(int)$_POST['id'];
$b_value=$_POST['value'];

global $wpdb;
$table_name=$wpdb->prefix.'jin_challenge';
$challenge_data=$wpdb->get_results("SELECT * FROM $table_name WHERE ID=$id LIMIT 1;");

if(!$challenge_data){
$data_arr['code']=0;
$data_arr['msg']='数据异常！该数据不存在！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

$challenge_user_id=$challenge_data[0]->challenge_user_id;
if($challenge_user_id){
$data_arr['code']=0;
$data_arr['msg']='该挑战已被参与！请重新换一个挑战！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


$type=$challenge_data[0]->type;
$a_value=$challenge_data[0]->user_value;
$price=$challenge_data[0]->price;
$c_user_id=$challenge_data[0]->user_id;
if($type=='a'){
if($b_value!='石头'&&$b_value!='剪刀'&&$b_value!='布'){
$b_value='石头';
}
}else{
$b_value=rand(0,100);
}

if($price<=0){
$data_arr['code']=0;
$data_arr['msg']='数据异常，挑战金额无效！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}

$credit=(int)get_user_meta($user_id,'credit',true);
if($credit<$price){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}



$a_value_text=$a_value;
$b_value_text=$b_value;

$aa=1;//胜利
$bb=2;//失败
$cc=3;
if($type=='a'){

if($a_value=='石头'){
if($b_value=='石头'){
$a_result=$cc;
$b_result=$cc;
}else if($b_value=='剪刀'){
$a_result=$aa;
$b_result=$bb;
}else if($b_value=='布'){
$a_result=$bb;
$b_result=$aa;
}
}else if($a_value=='剪刀'){
if($b_value=='石头'){
$a_result=$bb;
$b_result=$aa;
}else if($b_value=='剪刀'){
$a_result=$cc;
$b_result=$cc;
}else if($b_value=='布'){
$a_result=$aa;
$b_result=$bb;
}
}else{
if($b_value=='石头'){
$a_result=$aa;
$b_result=$bb;
}else if($b_value=='剪刀'){
$a_result=$bb;
$b_result=$aa;
}else if($b_value=='布'){
$a_result=$cc;
$b_result=$cc;
}
}


}else{
if($a_value>$b_value){
$a_result=$aa;
$b_result=$bb;
}else if($a_value<$b_value){
$a_result=$bb;
$b_result=$aa;	
}else{
$a_result=$cc;
$b_result=$cc;
}
}


if($challenge_poundage){
$poundage_price=(int)($price-($price*($challenge_poundage/100)));
}


$status=$wpdb->query("UPDATE $table_name SET challenge_user_id=$user_id,challenge_user_value='$b_value' WHERE ID=$id");
if($status){
if($b_result==1){//胜利
$data_arr['code']=1;
$data_arr['msg']='挑战成功！获得'.$poundage_price.$credit_name;
jinsom_update_credit($user_id,$poundage_price,'add','challenge','参与挑战获胜(已扣除手续费)',1,'');

$nickname=get_user_meta($user_id,'nickname',true);
jinsom_im_tips($c_user_id,'['.$nickname.']参与了你的挑战，并且打败了你！你损失了'.$price.$credit_name);

}else if($b_result==2){//失败
$data_arr['code']=2;
$data_arr['msg']='挑战失败！扣除'.$price.$credit_name;
jinsom_update_credit($user_id,$price,'cut','challenge','参与挑战失败',1,'');
jinsom_update_credit($c_user_id,($poundage_price+$price),'add','challenge','挑战守擂台成功(已扣除手续费)',1,'');

$nickname=get_user_meta($user_id,'nickname',true);
jinsom_im_tips($c_user_id,'['.$nickname.']参与了你的挑战，并且被你打败！你获得对方'.$poundage_price.$credit_name.'的奖励！');

}else{
$data_arr['code']=3;
$data_arr['msg']='双方打成平局！';
jinsom_update_credit($c_user_id,$price,'add','challenge','挑战打成平局(费用返还)',1,'');

$nickname=get_user_meta($user_id,'nickname',true);
jinsom_im_tips($c_user_id,'['.$nickname.']参与了你的挑战，双方打成平局！你的挑战费用['.$price.$credit_name.']已经返还！');
}


if($a_result==1){
$a_result='<font style="color:#4caf50;">胜利</font>';
}else if($a_result==2){
$a_result='<font style="color:#f00;">失败</font>';
}else{
$a_result='<font style="color:#2196f3;">平局</font>';
}

if($b_result==1){
$b_result='<font style="color:#4caf50;">胜利</font>';
}else if($b_result==2){
$b_result='<font style="color:#f00;">失败</font>';
}else{
$b_result='<font style="color:#2196f3;">平局</font>';
}

$vs_html='
<div class="result">
<div class="a">
<a href="'.jinsom_mobile_author_url($c_user_id).'" class="link">
'.jinsom_avatar($c_user_id,'40',avatar_type($c_user_id)).jinsom_verify($c_user_id).'
</a>
<p>'.$a_result.'<br>'.$a_value_text.'</p>
</div>
<div class="vs"><i class="jinsom-icon jinsom-VS"></i></div>
<div class="b">
<a href="'.jinsom_mobile_author_url($user_id).'" class="link">
'.jinsom_avatar($user_id,'40',avatar_type($user_id)).jinsom_verify($user_id).'
</a>
<p>'.$b_result.'<br>'.$b_value_text.'</p>
</div>
</div>
';
$data_arr['html']=$vs_html;


}else{
$data_arr['code']=0;
$data_arr['msg']='挑战失败！数据有误！';
}

header('content-type:application/json');
echo json_encode($data_arr);
