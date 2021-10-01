<?php 
//大转盘
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$credit_name=jinsom_get_option('jinsom_credit_name');
$credit=(int)get_user_meta($user_id,'credit',true);
$jinsom_lottery_min=jinsom_get_option('jinsom_lottery_min');
$jinsom_lottery_max=jinsom_get_option('jinsom_lottery_max');
$lottery_play_max=jinsom_get_option('jinsom_lottery_play_max');
$lottery_times=(int)get_user_meta($user_id,'lottery_times',true);

$number=(int)$_POST['number'];


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

if($number<$jinsom_lottery_min||$number>$jinsom_lottery_max){
$data_arr['code']=0;
$data_arr['msg']='下注金额范围为'.$jinsom_lottery_min.'-'.$jinsom_lottery_max.$credit_name;
header('content-type:application/json');
echo json_encode($data_arr);
exit();			
}

if($credit<$number){
$data_arr['code']=0;
$data_arr['msg']='你的'.$credit_name.'不足！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

if($lottery_times>=$lottery_play_max){
$data_arr['code']=0;
$data_arr['msg']='你今天抽奖的次数已经用完！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

$jinsom_lottery_add = jinsom_get_option('jinsom_lottery_add');
$i=0;
$prize_arr = array();
foreach ($jinsom_lottery_add as $lottery) {
$prize_arr[$i]=array('id'=>$i,'msg'=>$lottery['msg'],'v'=>$lottery['chance'],'number'=>$lottery['number']);
$i++;
}


update_user_meta($user_id,'lottery_times',$lottery_times+1);//累加今天已经抽奖次数
jinsom_update_credit($user_id,$number,'cut','lottery','大转盘下注',1,'');


foreach ($prize_arr as $key => $val) {
$arr[$val['id']] = $val['v'];
}
$rid = jinsom_lottery_rand($arr); //根据概率获取奖项id 

switch ($rid)
{
case 0:
$a=rand(0,12);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[0]['msg'];
$multiple=$prize_arr[0]['number'];//倍数
break;  

case 1:
$a=rand(17,42);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[1]['msg'];
$multiple=$prize_arr[1]['number'];//倍数
break;  

case 2:
$a=rand(47,72);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[2]['msg'];
$multiple=$prize_arr[2]['number'];//倍数
break;  

case 3:
$a=rand(77,102);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[3]['msg'];
$multiple=$prize_arr[3]['number'];//倍数
break;  

case 4:
$a=rand(107,132);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[4]['msg'];
$multiple=$prize_arr[4]['number'];//倍数
break;  

case 5:
$a=rand(138,162);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[5]['msg'];
$multiple=$prize_arr[5]['number'];//倍数
break;  

case 6:
$a=rand(169,194);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[6]['msg'];
$multiple=$prize_arr[6]['number'];//倍数
break; 

case 7:
$a=rand(200,224);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[7]['msg'];
$multiple=$prize_arr[7]['number'];//倍数
break; 

case 8:
$a=rand(230,254);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[8]['msg'];
$multiple=$prize_arr[8]['number'];//倍数
break; 

case 9:
$a=rand(260,284);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[9]['msg'];
$multiple=$prize_arr[9]['number'];//倍数
break; 

case 10:
$a=rand(289,314);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[10]['msg'];
$multiple=$prize_arr[10]['number'];//倍数
break; 

case 11:
$a=rand(320,342);
$data_arr['rand']=$a;
$data_arr['msg']=$prize_arr[11]['msg'];
$multiple=$prize_arr[11]['number'];//倍数
break; 


}
$data_arr['multiple']=$multiple;
$count=ceil($multiple*$number);
$data_arr['count']=$count;//用户最终获得的金币
jinsom_update_credit($user_id,$count,'add','lottery','大转盘抽奖',1,'');//更新用户获得的金币


header('content-type:application/json');
echo json_encode($data_arr);


//获取概率
function jinsom_lottery_rand($proArr) {
$result = '';
//概率数组的总概率精度 
$proSum = array_sum($proArr);
//概率数组循环 
foreach ($proArr as $key => $proCur) {
$randNum = mt_rand(1, $proSum);
if ($randNum <= $proCur) {
$result = $key;
break;
} else {
$proSum -= $proCur;
}
}
unset ($proArr);
return $result;
}