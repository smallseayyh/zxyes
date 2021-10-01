<?php
//易支付回调
header('Content-type:text/html; Charset=utf-8');
require( '../../../wp-load.php');
require_once("epay.php");
$jinsom_pay_tab=jinsom_get_option('jinsom_pay_tab');
$epay_key=$jinsom_pay_tab['jinsom_epay_key'];
$sdk = new SDK();
$sdk->key($epay_key);

//参数验证
$data=$_GET;
if (!(isset($data['out_trade_no']) && isset($data['sign']))) {
exit();
}
//签名验证
if (!$sdk->signVerify($data)) {
exit();
}

// 交易状态## 交易成功
if($data['trade_status'] == 'TRADE_SUCCESS') {

$trade_no=$data['out_trade_no'];
$out_trade_no=$data['trade_no'];
$total_fee = $data['money'];
$total_fee=floatval($total_fee);

global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$datas=$wpdb->get_results("SELECT * FROM $table_name WHERE trade_no = $trade_no and pay_price='$total_fee' LIMIT 1;");	

if($datas){//如果存在订单，并且状态还没有完成

foreach ($datas as $data){
$user_id=$data->user_id;
$post_id=$data->post_id;
$goods_type=$data->goods_type;//商品类型
$virtual_type=$data->virtual_type;//虚拟物品类型
$number=(int)$data->number;//订单数量
}

$goods_data=get_post_meta($post_id,'goods_data',true);//商品数据
$virtual_number=(int)$goods_data['virtual_number'];
$virtual_info='';
if($number>1){$virtual_number=$virtual_number*$number;}//如果购买的数量大于1，则自动相乘

if($goods_type=='a'){//本站虚拟
$status=2;//订单完成
$virtual_info=$virtual_number;//虚拟物品的信息：数量、头衔名称、卡密、下载信息
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
}else if($virtual_type=='faka'){
$virtual_faka=$goods_data['virtual_faka'];
$virtual_faka_arr=explode(PHP_EOL,$virtual_faka);
if($virtual_faka_arr){
$virtual_info='发卡信息：'.$virtual_faka_arr[0];
unset($virtual_faka_arr[0]);//删除卡密
$goods_data['virtual_faka']=implode(PHP_EOL,$virtual_faka_arr);//取出卡密后的发卡信息
}else{
$virtual_info='卡密已经没有了！请联系管理员！';	
}

//遍历取出来========
jinsom_im_tips($user_id,$virtual_info);//IM发送

}




}else{//其他虚拟、实物
$status=1;//待发货

//更新库存
$goods_count=(int)$goods_data['jinsom_shop_goods_count'];
$goods_count=$goods_count-1;
$goods_data['jinsom_shop_goods_count']=$goods_count;

}


//商品交易数量
$buy_number=(int)$goods_data['buy_number'];
$goods_data['buy_number']=$buy_number+$number;


update_post_meta($post_id,'goods_data',$goods_data);//更新商品信息

$time=current_time('mysql');
//更新订单信息
$wpdb->query("UPDATE $table_name SET out_trade_no='$out_trade_no',status=$status,virtual_info='$virtual_info',pay_type='epay',time='$time' WHERE trade_no = '$trade_no';");


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
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$post_id','buy_goods','$time')");


}

echo "success";
}

