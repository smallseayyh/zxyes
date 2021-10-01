<?php
//购买商品
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证
$user_id = $current_user->ID;

$select_arr=stripslashes(strip_tags($_POST['select_arr']));
$info_arr=stripslashes(strip_tags($_POST['info_arr']));
$info_arr=json_decode($info_arr,true);

$post_id=(int)$_POST['post_id'];
$number=(int)$_POST['number'];
$address=(int)$_POST['address'];
$marks=strip_tags($_POST['marks']);
$pay_type='';

$select_price=(int)$_POST['select_price'];
$select_price=$select_price-1;//数组下标需要-1
$select_arr=json_decode($select_arr,true);//套餐--数组
$goods_data=get_post_meta($post_id,'goods_data',true);//商品数据
$price_type=$goods_data['jinsom_shop_goods_price_type'];//价格类型：金币、人民币
$select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐
// if(!$number){$number=1;}//如果没有数量则为1；

if($price_type=='credit'){//金币购买
$trade_no=time().rand(100,999);//生成站内订单
}else{//人民币购买
$trade_no=strip_tags($_POST['trade_no']);
}

//判断是否登录
if (!is_user_logged_in()) { 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if(!jinsom_get_option('jinsom_shop_on_off')){
$data_arr['code']=0;
$data_arr['msg']=__('购买功能已经关闭！暂时无法购买！','jinsom');
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

if($number<=0){
$data_arr['code']=0;
$data_arr['msg']=__('购买的数量不能为0！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if(current_user_can('level_10')){
$data_arr['code']=0;
$data_arr['msg']=__('整个网站都是你自己的，还要不要脸！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//是否属于商品类型
$goods_type=$goods_data['jinsom_shop_goods_type'];//商品类型
if(get_post_type($post_id)!='goods'||$goods_type=='d'){
$data_arr['code']=0;
$data_arr['msg']=__('商品异常！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断商品库存
if($goods_type!='a'){
$goods_count=(int)$goods_data['jinsom_shop_goods_count'];
if($goods_count<1){
$data_arr['code']=0;
$data_arr['msg']=__('该商品库存不足！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}



if($goods_type=='a'){//本站虚拟
$virtual_type=$goods_data['virtual_type'];//虚拟物品类型
if($virtual_type=='honor'||$virtual_type=='download'||$virtual_type=='faka'){
if($number>1){
$data_arr['code']=0;
$data_arr['msg']=__('该类型商品每次只能购买一个','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

if($virtual_type=='faka'){//如果发卡完了提示库存不足
$virtual_faka=$goods_data['virtual_faka'];
if(!$virtual_faka){
$data_arr['code']=0;
$data_arr['msg']=__('该商品库存不足！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}

}


if($goods_type=='c'){//实物
if($goods_data['jinsom_shop_goods_buy_info_tips']&&!$marks){
$data_arr['code']=0;
$data_arr['msg']=__('请输入备注信息！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}


if($goods_type=='c'){//实物
if(!isset($_POST['address'])){
$data_arr['code']=0;
$data_arr['msg']=__('请选择地址！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


$user_address_arr=get_user_meta($user_id,'address',true);
$address_arr=$user_address_arr[$address];
}else{
$address_arr='';
}




$power=$goods_data['jinsom_shop_goods_power'];//商品权限

if($power=='no'||$power=='stop'){
$data_arr['code']=0;
$data_arr['msg']=__('该商品状态无法被购买！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}else if($power=='vip'){
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']=__('该商品仅限VIP用户购买！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='verify'){
$user_verify=get_user_meta($user_id,'verify',true);
if(!$user_verify&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']=__('该商品仅限认证用户购买！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='new'){
$user_info=get_userdata($user_id);
if((time()-strtotime($user_info->user_registered))>2592000&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']=__('该商品仅限30天内注册的新用户购买！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='exp'){//指定经验值
$user_exp=jinsom_get_user_exp($user_id);
if($user_exp<$goods_data['jinsom_shop_goods_power_exp']&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该商品只允许经验值大于'.$goods_data['jinsom_shop_goods_power_exp'].'的用户购买！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='charm'){//指定魅力值
$user_charm=(int)get_user_meta($user_id,'charm',true);
if($user_charm<$goods_data['jinsom_shop_goods_power_charm']&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该商品只允许魅力值大于'.$goods_data['jinsom_shop_goods_power_charm'].'的用户购买！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}else if($power=='vip_number'){//指定成长值
$vip_number=(int)get_user_meta($user_id,'vip_number',true);
if($vip_number<$goods_data['jinsom_shop_goods_power_vip_number']&&!jinsom_is_admin($user_id)){
$data_arr['code']=0;
$data_arr['msg']='该商品只允许成长值大于'.$goods_data['jinsom_shop_goods_power_vip_number'].'的用户购买！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}


//价格

if($goods_type=='a'||$goods_type=='d'||!$select_change_price_add){//本站虚拟、淘宝客、没有添加价格套餐
$price=$goods_data['jinsom_shop_goods_price'];
$price_discount=$goods_data['jinsom_shop_goods_price_discount'];
}else{
$price=$select_change_price_add[0]['value_add'][$select_price]['price'];
$price_discount=$select_change_price_add[0]['value_add'][$select_price]['price_discount'];
}


if($price_discount){
$price=$price_discount;//如果有折扣，则价格为折扣价
}


if($number>1){$price=$price*$number;}//最终支付的价格

if($price<=0||!is_numeric($price)){
$data_arr['code']=0;
$data_arr['msg']=__('参数不合法！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


if($price_type=='credit'){//金币支付
$user_credit=(int)get_user_meta($user_id,'credit',true);
if($price>$user_credit){
$data_arr['code']=2;
$data_arr['msg']='你的'.jinsom_get_option('jinsom_credit_name').'不足，请充值之后再购买！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}


global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';

//同一个商品最多只能有一个未支付订单
$data_b=$wpdb->get_results("SELECT ID FROM $table_name WHERE user_id = $user_id and post_id=$post_id and status=0 and now() <SUBDATE(time,interval -1 day) LIMIT 1;");
if($data_b){
$data_arr['code']=5;
$data_arr['msg']=__('该商品你还有未支付完成的订单！请前往我的订单里面进行支付！','jinsom');
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}



//购买上限
$goods_max=$goods_data['jinsom_shop_goods_max'];//每个用户购买上限
$count=$wpdb->get_var("SELECT sum(number) FROM $table_name WHERE user_id = $user_id and post_id=$post_id and ((status=0 and now() <SUBDATE(time,interval -1 day))||status!=0);");//排除未过期的
//if(isset($_POST['confirmation'])&&$_POST['confirmation']=='first'){
$count=$count+$number;
// }
if($count>$goods_max){
$data_arr['code']=0;
$data_arr['msg']=sprintf(__( '该商品每个用户最多限购%s件！','jinsom'),$goods_max);
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


if($goods_type=='b'){//其他虚拟
$marks=$info_arr;
$marks=serialize($marks);
}





if($price_type=='credit'){//金币支付
jinsom_update_credit($user_id,$price,'cut','goods-buy','购买商品',1,$post_id);//扣掉金币
$pay_type='credit';

if($goods_type=='a'){//本站虚拟物品
$virtual_number=(int)$goods_data['virtual_number'];
$virtual_info=$virtual_number;//虚拟物品的信息：数量、头衔名称、卡密、下载信息
if($number>1){$virtual_number=$virtual_number*$number;}//如果购买的数量大于1，则自动相乘
if($virtual_type=='honor'){
jinsom_add_honor($user_id,$goods_data['virtual_honor']);
$virtual_info=$goods_data['virtual_honor'];
}else if($virtual_type=='exp'){
jinsom_update_exp($user_id,$virtual_number,'add','购买商品');
}else if($virtual_type=='charm'){
jinsom_update_user_charm($user_id,$virtual_number);
}else if($virtual_type=='vip_number'){
jinsom_update_user_vip_number($user_id,$virtual_number);
}else if($virtual_type=='sign'){
jinsom_update_user_sign_number($user_id,$virtual_number);
}else if($virtual_type=='nickname'){//改名卡
jinsom_update_user_nickname_card($user_id,$virtual_number);
}else if($virtual_type=='download'){
$virtual_info=$goods_data['virtual_download_info'];
if(!$virtual_info){$virtual_info='购买的商品没有提取信息，请联系管理员！';}
jinsom_im_tips($user_id,$virtual_info);//IM发送
//数据库记录
}else if($virtual_type=='faka'){
$virtual_faka_arr=explode(PHP_EOL,$virtual_faka);
$virtual_info='发卡信息：'.$virtual_faka_arr[0];
unset($virtual_faka_arr[0]);//删除卡密
$goods_data['virtual_faka']=implode(PHP_EOL,$virtual_faka_arr);//取出卡密后的发卡信息

//遍历取出来========
jinsom_im_tips($user_id,$virtual_info);//IM发送
//数据库记录
}


$status=2;//订单状态==已完成
}else if($goods_type=='b'){//其他虚拟
$status=1;//订单状态==待发货
}else if($goods_type=='c'){//实物
$status=1;//订单状态==待发货
}



if($goods_type!='a'){
//库存-1
$goods_count=$goods_count-1;
$goods_data['jinsom_shop_goods_count']=$goods_count;
}

if($goods_type=='a'){
//商品交易数量
$buy_number=(int)$goods_data['buy_number'];
$goods_data['buy_number']=$buy_number+$number;
}
update_post_meta($post_id,'goods_data',$goods_data);//更新商品信息


//推广返利
$who=(int)get_user_meta($user_id,'who',true);
$jinsom_shop_referral_credit=(int)$goods_data['jinsom_shop_referral_credit'];//返利金币
if($jinsom_shop_referral_credit){//设置了返利金币
if(isset($_COOKIE["invite"])){//通过推广链接访问
jinsom_update_credit($_COOKIE["invite"],$jinsom_shop_referral_credit,'add','shop-goods-referral','推广用户商品返利',1,'');
}else if($who){//存在上级推广人
jinsom_update_credit($who,$jinsom_shop_referral_credit,'add','shop-goods-referral','推广用户商品返利',1,'');	
}
}



jinsom_im_tips(1,'提醒：你网站有新的商城订单，请登录后台查看！<br>购买用户：'.jinsom_nickname($user_id).'<br>商品名称：'.get_the_title($post_id));//提醒管理员有新的订单

//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$post_id','buy_goods','$time')");


}else{//人民币支付
$status=0;//订单状态==没付款



}

$data_arr['status']=$status;




$select_arr=serialize($select_arr);
$address_arr=serialize($address_arr);

//记录订单
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$time=current_time('mysql');
$status=$wpdb->query("INSERT INTO $table_name (post_id,user_id,goods_type,price_type,price,pay_price,select_info,number,virtual_type,virtual_info,address,marks,coupon_id,status,trade_no,pay_type,time) VALUES ('$post_id','$user_id','$goods_type','$price_type','$price','$price','$select_arr','$number','$virtual_type','$virtual_info','$address_arr','$marks','','$status','$trade_no','$pay_type','$time')" );



if($price_type=='credit'){//金币支付
$data_arr['code']=1;
$data_arr['msg']=__('购买成功！','jinsom');
}else{//人民币支付
$data_arr['code']=3;
$data_arr['order_id']=$wpdb->insert_id;;
$data_arr['msg']=__('订单提交成功！','jinsom');
}



header('content-type:application/json');
echo json_encode($data_arr);

