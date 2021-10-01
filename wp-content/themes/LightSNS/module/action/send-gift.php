<?php
//送礼物
require( '../../../../../wp-load.php' );
require('../admin/ajax.php');//验证

$user_id = $current_user->ID;
$name=strip_tags($_POST['name']);
$author_id=(int)$_POST['author_id'];
$post_id=(int)$_POST['post_id'];
$jinsom_gift_add=jinsom_get_option('jinsom_gift_add');


if(!jinsom_get_option('jinsom_gift_add')||!jinsom_get_option('jinsom_gift_on_off')){
$data_arr['code']=0;
$data_arr['msg']='已经关闭礼物功能！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
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
if($author_id==$user_id){
$data_arr['code']=0;
$data_arr['msg']='你太自恋了！';
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



//数据异常
if($name==''){
$data_arr['code']=0;
$data_arr['msg']='礼物名称不能为空！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}


if($post_id){
if(jinsom_get_post_author_id($post_id)!=$author_id){
$data_arr['code']=0;
$data_arr['msg']='数据异常！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
}


if($jinsom_gift_add){
foreach ($jinsom_gift_add as $data){

if($data['title']==$name){


if($data['vip']){
if(!is_vip($user_id)){
$data_arr['code']=0;
$data_arr['msg']='这是VIP专属礼物，请你开通VIP会员！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}
}
	
$charm=(int)$data['m'];
$gift_number=(int)$data['gift_number'];
$gift_credit=$data['credit'];
$img=$data['images'];
$name=$data['title'];
}
}

if($gift_credit==''){$gift_credit=0;}



$user_credit=(int)get_user_meta($user_id,'credit',true);
if($user_credit<$gift_credit){
$data_arr['code']=0;
$data_arr['msg']='你的'.jinsom_get_option('jinsom_credit_name').'不足！';
}else{

$user_gift_number=(int)get_user_meta($author_id,'gift_number',true);
$count_gift_number=$user_gift_number+$gift_number;

jinsom_update_user_charm($author_id,$charm);//更新对方魅力值


update_user_meta($author_id,'gift_number',$count_gift_number);//更新对方礼物积分
jinsom_update_credit($user_id,$gift_credit,'cut','gift','赠送礼物',1,''); 
jinsom_add_tips($author_id,$user_id,$post_id,'gift','给你赠送了礼物['.$name.']','赠送你礼物'); 

$user_reward=(int)get_user_meta($user_id,'reward',true);
update_user_meta($user_id,'reward',$user_reward+$gift_credit);//更新用户累计打赏值

//记录当天送礼次数
$gift_times=(int)get_user_meta($user_id,'gift_times',true);
update_user_meta($user_id,'gift_times',($gift_times+1));

global $wpdb;
$table_name = $wpdb->prefix . 'jin_gift';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (send_user_id,receive_user_id,name,img,credit,charm,number,time) VALUES ('$user_id','$author_id','$name','$img',$gift_credit,'$charm','1','$time')" );


if($post_id){//写入评论
$content='<m class="gift"><span class="jinsom-gift-icon"></span>赠送了礼物['.$name.']<img src="'.$img.'" class="gift-img"></m>';	
$data = array(
'comment_post_ID' =>$post_id,
'comment_content' => $content,
'user_id' => $user_id,
'comment_date' =>current_time('mysql'),
'comment_author_IP'=>$_SERVER['REMOTE_ADDR'],
'comment_approved' => 1,
);
$comment_id=wp_insert_comment($data); 

if($comment_id){
update_comment_meta($comment_id,'comment_type','gift');

//更新评论来自
if(wp_is_mobile()){
update_comment_meta($comment_id,'from','mobile');
}else{
update_comment_meta($comment_id,'from','pc');  
}

update_post_meta($post_id, 'last_comment_time', time());//插入最后回复字段	

update_comment_meta($comment_id,'gift',1);//更新评论为打赏类型
update_comment_meta($comment_id,'delete',1);//禁止删除

//更新楼层数
$bbs_floor=get_post_meta($post_id,'bbs_floor',true);//获取目前的楼层数
update_comment_meta($comment_id,'comment_floor',$bbs_floor+1);//写入当前评论的楼层
update_post_meta($post_id,'bbs_floor',$bbs_floor+1);//总楼层累加

}
}


$data_arr['code']=1;
$data_arr['msg']='赠送成功！';
$data_arr['post_url']=get_the_permalink($post_id);	

//记录实时动态
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','$author_id','gift','$time')");


}



}else{
$data_arr['code']=0;
$data_arr['msg']='请在后台-主题设置-礼物模块 添加礼物数据！';
}



header('content-type:application/json');
echo json_encode($data_arr);