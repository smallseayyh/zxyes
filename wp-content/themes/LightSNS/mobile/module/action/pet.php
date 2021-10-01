<?php 
//宠物
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$type=strip_tags($_POST['type']);
$credit_name=jinsom_get_option('jinsom_credit_name');

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


//出售
if($type=='sell'){

if((jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("pet",jinsom_get_option('jinsom_machine_verify_use_for')))&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])&&!jinsom_is_admin($user_id)){
if(!jinsom_machine_verify($_POST['ticket'],$_POST['randstr'])){
$data_arr['code']=0;
$data_arr['msg']='安全验证失败！请重新进行操作！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

$id=(int)$_POST['id'];
global $wpdb;
$table_name=$wpdb->prefix.'jin_pet';
$pet_data=$wpdb->get_results("SELECT * FROM $table_name WHERE ID='$id' and user_id=$user_id limit 1;");

if($pet_data){
$price=$pet_data[0]->price;
$pet_name=$pet_data[0]->pet_name;
jinsom_update_credit($user_id,$price,'add','pet-sell','出售宠物',1,'');  
$wpdb->query("DELETE FROM $table_name where ID='$id';");

//写入记录
$table_name_note=$wpdb->prefix.'jin_pet_note';
$time=current_time('mysql');
$wpdb->query("INSERT INTO $table_name_note (user_id,type,author_id,pet_name,time) VALUES ('$user_id','sell',0,'$pet_name','$time')");


$data_arr['code']=1;
$data_arr['msg']=__('出售成功！','jinsom');
$data_arr['text']=__('可孵化','jinsom');

}else{
$data_arr['code']=0;
$data_arr['msg']=__('数据异常！该宠物不属于你！','jinsom');	
}


}

//花金币解锁
if($type=='deblocking'){
$number=(int)$_POST['number'];
$jinsom_pet_nest_add=jinsom_get_option('jinsom_pet_nest_add');
$type=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type'];//窝的类型
if($type!='credit'){
$data_arr['code']=0;
$data_arr['msg']='数据类型有误！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


$nest_credit=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_credit'];//窝的价格
$credit=(int)get_user_meta($user_id,'credit',true);
if($credit<$nest_credit){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

jinsom_update_credit($user_id,$nest_credit,'cut','pet-deblocking','解锁宠物窝',1,'');
$nest_name=$jinsom_pet_nest_add[$number]['name'];
$user_pet_nest=get_user_meta($user_id,'pet_nest',true);
if($user_pet_nest){
$user_pet_nest_arr=explode(",",$user_pet_nest);
array_push($user_pet_nest_arr,$nest_name);
$user_pet_nest=implode(",",$user_pet_nest_arr);
update_user_meta($user_id,'pet_nest',$user_pet_nest);
}else{
update_user_meta($user_id,'pet_nest',$nest_name);
}


//写入记录
$table_name_note=$wpdb->prefix.'jin_pet_note';
$time=current_time('mysql');
$wpdb->query("INSERT INTO $table_name_note (user_id,type,author_id,remark,time) VALUES ('$user_id','deblocking',0,'$nest_name','$time')");


$data_arr['code']=1;
$data_arr['msg']=__('解锁成功！','jinsom');
$data_arr['text']=__('可孵化','jinsom');
}


//购买宠物蛋 并且孵化
if($type=='buy'){
$number=(int)$_POST['number'];//第几个坑位
$iiii=(int)$_POST['iiii'];//第几个宠物
$jinsom_pet_nest_add=jinsom_get_option('jinsom_pet_nest_add');
$type=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type'];//窝的类型
$name=$jinsom_pet_nest_add[$number]['name'];//窝的名称

if($type=='credit'){
$user_pet_nest=get_user_meta($user_id,'pet_nest',true);
if($user_pet_nest){
$user_pet_nest_arr=explode(",",$user_pet_nest);
if(in_array($name,$user_pet_nest_arr)){
$status=1;
}else{
$status=0;
}
}else{
$status=0;
}
}else if($type=='vip'){
if(is_vip($user_id)){
$status=1;	
}else{
$status=0;
}
}else if($type=='exp'){
$user_exp=(int)get_user_meta($user_id,'exp',true);
if($user_exp>=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_exp']){
$status=1;	
}else{
$status=0;
}
}else if($type=='vip_number'){
$user_vip_number=(int)get_user_meta($user_id,'vip_number',true);
if($user_vip_number>=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_vip_number']){
$status=1;	
}else{
$status=0;
}
}else if($type=='charm'){
$user_charm=(int)get_user_meta($user_id,'charm',true);
if($user_charm>=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_charm']){
$status=1;	
}else{
$status=0;
}
}else if($type=='visitor'){
$user_visitor=(int)get_user_meta($user_id,'visitor',true);
if($user_visitor>=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_visitor']){
$status=1;	
}else{
$status=0;
}
}else{//free
$status=1;	
}

if($status==0){
$data_arr['code']=0;
$data_arr['msg']=__('你还没有解锁这个宠物窝！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

global $wpdb;
$table_name=$wpdb->prefix.'jin_pet';
$pet_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' and nest_name='$name' limit 1;");
if($pet_data){
$data_arr['code']=0;
$data_arr['msg']=__('这个宠物窝还在使用中！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}




$jinsom_pet_add=jinsom_get_option('jinsom_pet_add');
$pet_name=$jinsom_pet_add[$iiii]['name'];//宠物名称
$price_egg=$jinsom_pet_add[$iiii]['price_egg'];
$price_pet=$jinsom_pet_add[$iiii]['price_pet'];
$img_egg=$jinsom_pet_add[$iiii]['img_egg'];
$img_pet=$jinsom_pet_add[$iiii]['img_pet'];
$hatch_time=$jinsom_pet_add[$iiii]['time'];//孵化时间
$protect_time=(int)jinsom_get_option('jinsom_pet_protect_time');//保护时间

$jinsom_pet_same_number=(int)jinsom_get_option('jinsom_pet_same_number');
$jinsom_pet_same_number=$jinsom_pet_same_number-1;
if($wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id and pet_name='$pet_name';")>$jinsom_pet_same_number){
$data_arr['code']=0;
$data_arr['msg']=sprintf(__( '为了繁荣宠物市场<br>每种类蛋仅可以同时最多孵化%s个','jinsom'),jinsom_get_option('jinsom_pet_same_number'));
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


$hatch_time=$hatch_time*60;
$vip=$jinsom_pet_add[$iiii]['vip'];
$credit=(int)get_user_meta($user_id,'credit',true);
if($credit<$price_egg){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if($vip&&!is_vip($user_id)){
$data_arr['code']=0;
$data_arr['msg']=__('该宠物只允许VIP用户购买！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


//购买成功
$time=time();
jinsom_update_credit($user_id,$price_egg,'cut','pet-buy','购买宠物蛋',1,'');
$wpdb->query("INSERT INTO $table_name (user_id,pet_name,nest_name,egg_img,pet_img,hatch_time,price,time,protect_time) VALUES ('$user_id','$pet_name','$name','$img_egg','$img_pet','$hatch_time','$price_pet','$time','$protect_time')");


//写入记录
$table_name_note=$wpdb->prefix.'jin_pet_note';
$time=current_time('mysql');
$wpdb->query("INSERT INTO $table_name_note (user_id,type,author_id,pet_name,time) VALUES ('$user_id','buy',0,'$pet_name','$time')");

$pet_times=(int)get_user_meta($user_id,'pet_times',true);//孵化次数
$today_pet_times=(int)get_user_meta($user_id,'today_pet_times',true);//今日孵化次数
update_user_meta($user_id,'pet_times',$pet_times+1);
update_user_meta($user_id,'today_pet_times',$today_pet_times+1);




$data_arr['code']=1;
$data_arr['msg']=__('选择成功！','jinsom');
$data_arr['img_egg']=$img_egg;
$data_arr['pet_name']=$pet_name;
$data_arr['hatch_time']=jinsom_get_second_h_m($hatch_time);
$data_arr['text']=__('孵化中','jinsom');

}



//偷宠物
if($type=='steal'){

if((jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("pet",jinsom_get_option('jinsom_machine_verify_use_for')))&&!isset($_POST[jinsom_get_option('jinsom_machine_verify_param')])&&!jinsom_is_admin($user_id)){
if(!jinsom_machine_verify($_POST['ticket'],$_POST['randstr'])){
$data_arr['code']=0;
$data_arr['msg']='安全验证失败！请重新进行操作！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}
	
$id=(int)$_POST['id'];
global $wpdb;
$time=time();
$table_name=$wpdb->prefix.'jin_pet';

if(!$wpdb->get_results("SELECT user_id FROM $table_name WHERE user_id=$user_id limit 1;")){
$data_arr['code']=0;
$data_arr['msg']=__('嗨，你窝里还是空的呢！<br>请先孵化一颗蛋再来狩猎其他人的吧！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


$pet_data=$wpdb->get_results("SELECT * FROM $table_name WHERE ID='$id' and (hatch_time+time+protect_time)<$time limit 1;");
$author_id=$pet_data[0]->user_id;

if($pet_data){
$status=$wpdb->query("DELETE FROM $table_name where ID='$id';");

if($status){//删除成功
$pet_name=$pet_data[0]->pet_name;
$price=$pet_data[0]->price;
$jinsom_pet_steal_commission=(int)jinsom_get_option('jinsom_pet_steal_commission');
if($jinsom_pet_steal_commission){
$jinsom_pet_steal_commission=$jinsom_pet_steal_commission/100;
$price=(int)($price-($jinsom_pet_steal_commission*$price));
$commission_text='(已扣'.jinsom_get_option('jinsom_pet_steal_commission').'%手续费)';
}else{	
$commission_text='';
}

$jinsom_pet_steal_type=jinsom_get_option('jinsom_pet_steal_type');
if($jinsom_pet_steal_type=='half'){//一半
$price=$price/2;
jinsom_update_credit($author_id,$price,'add','pet-steal','宠物被捕获'.$commission_text,1,'');
jinsom_update_credit($user_id,$price,'add','pet-steal','捕获宠物'.$commission_text,1,'');
}else if($jinsom_pet_steal_type=='rand'){//随机
$rand_price=rand(1,$price);
jinsom_update_credit($author_id,($price-$rand_price),'add','pet-steal','宠物被捕获'.$commission_text,1,'');
jinsom_update_credit($user_id,$rand_price,'add','pet-steal','捕获宠物'.$commission_text,1,'');
}else{//全部
jinsom_update_credit($user_id,$price,'add','pet-steal','捕获宠物'.$commission_text,1,''); 	
}


//写入记录
$table_name_note=$wpdb->prefix.'jin_pet_note';
$time=current_time('mysql');
$wpdb->query("INSERT INTO $table_name_note (user_id,type,author_id,pet_name,time) VALUES ('$author_id','steal',$user_id,'$pet_name','$time')");

//IM提醒
$nickname=get_user_meta($user_id,'nickname',true);
jinsom_im_tips($author_id,'你的['.$pet_name.']被['.$nickname.']捕获了');


$pet_steal_times=(int)get_user_meta($user_id,'pet_steal_times',true);//偷取次数
$today_pet_steal_times=(int)get_user_meta($user_id,'today_pet_steal_times',true);//今日偷取次数
update_user_meta($user_id,'pet_steal_times',$pet_steal_times+1);
update_user_meta($user_id,'today_pet_steal_times',$today_pet_steal_times+1);


$data_arr['code']=1;
$data_arr['msg']=__('捕获成功！','jinsom');
$data_arr['text']=__('未孵化','jinsom');

}else{
$data_arr['code']=0;
$data_arr['msg']=__('太多人在抢这个宠物了，请重新捕获！','jinsom');	
}

}else{
$data_arr['code']=0;
$data_arr['msg']=__('数据异常！该宠物还不能捕获！','jinsom');	
}


}


header('content-type:application/json');
echo json_encode($data_arr);