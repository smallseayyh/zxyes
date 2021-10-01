<?php
//领红包
require( '../../../../../wp-load.php');


global $wpdb;
$table_name = $wpdb->prefix.'jin_redbag';
$user_id = $current_user->ID;
if(!$user_id){exit();}
$post_id=(int)$_POST['post_id'];
$post_data=get_post($post_id);
$credit_name=jinsom_get_option('jinsom_credit_name');
$author_id=jinsom_get_post_author_id($post_id);
$redbag_credit=(int)get_post_meta($post_id,'redbag_credit',true);//红包总金额
$redbag_number=(int)get_post_meta($post_id,'redbag_number',true);//个数
$redbag_type=get_post_meta($post_id,'redbag_type',true);//类型
$redbag_surplus_credit=(int)get_post_meta($post_id,'redbag_surplus_credit',true);//剩余金额
$had_get_redbag_number=$wpdb->get_var("SELECT COUNT(*) FROM $table_name where post_id=$post_id");//已经领取的数量
$redbag_surplus_number=($redbag_number-$had_get_redbag_number);//剩余未领取的数量
$time=current_time('mysql');
if($redbag_type=='rand'){
$type_text=__('随机红包','jinsom');
}else if($redbag_type=='comment'){
$type_text=__('队形红包','jinsom');
}else if($redbag_type=='average'){
$type_text=__('平均红包','jinsom');
}else if($redbag_type=='follow'){
$type_text=__('关注红包','jinsom');
}


if($redbag_type=='follow'){
if(!jinsom_is_follow_author($author_id,$user_id)){
echo 0;
exit;
}
}

//操作领取红包逻辑

//判断是否被对方拉黑
if(!jinsom_is_blacklist($author_id,$user_id)||jinsom_is_admin($user_id)){
if(!jinsom_is_black($user_id)){//判断是否黑名单
if($had_get_redbag_number<$redbag_number){//红包是否已经领取完成

$status=$wpdb->get_results("SELECT credit FROM $table_name where post_id=$post_id and user_id=$user_id limit 1");
if(!$status){//用户是否已经领取红包

//红包类型
if($redbag_type=='average'){//平均
$user_get_redbag=$redbag_credit/$redbag_number;
}else{//随机公布/队形红包
if($redbag_credit==$redbag_number){//金额和数量一样
$user_get_redbag=1;
}else{
$user_get_redbag=jinsom_get_redbag($redbag_surplus_credit,$redbag_surplus_number);//给当前用户分配领取的随机金额
}	
}

if($user_id){
jinsom_update_credit($user_id,$user_get_redbag,'add','get-redbag','抢红包，获得',1,''); //给用户增加红包金额
update_post_meta($post_id,'redbag_surplus_credit',($redbag_surplus_credit-$user_get_redbag));//更新剩余红包金额
$redbag_surplus_credit=(int)get_post_meta($post_id,'redbag_surplus_credit',true);//重新获取剩余金额
$had_get_redbag_number=($had_get_redbag_number+1);//已领取数量+1

//记录已领取的用户
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,credit,time) VALUES ('$user_id','$post_id','$user_get_redbag','$time')" );
jinsom_add_tips($author_id,$user_id,$post_id,'redbag','领取了你的红包','领取了');//通知发红包的用户

if($redbag_type=='comment'){//队形 则插入评论
$ip = $_SERVER['REMOTE_ADDR'];
$comment_data = array(
'comment_post_ID' =>$post_id,
'comment_content' => $post_data->post_content,
'user_id' => $user_id,
'comment_date' => $time,
'comment_author_IP'=>$ip,
'comment_approved' => 1,
);
$comment_id=wp_insert_comment($comment_data); 
if($comment_id){
$bbs_floor=(int)get_post_meta($post_id,'bbs_floor',true);//获取目前的楼层数
update_comment_meta($comment_id,'comment_floor',($bbs_floor+1));//写入当前评论的楼层
update_post_meta($post_id,'bbs_floor',($bbs_floor+1));//总楼层累加
update_comment_meta($comment_id,'comment_type','redbag_comment');
}
}

}


}else{//已领取
foreach ($status as $data) {
$user_get_redbag=$data->credit;//用户已经领取的金额
}
}

$redbag_text='<i>'.$user_get_redbag.'</i><n>'.$credit_name.'</n>';

}else{
$redbag_text='<m>'.__('红包被领完了','jinsom').'</m>';
}
}else{
$redbag_text='<m>'.__('黑名单用户，禁止领红包','jinsom').'</m>';
}
}else{
$redbag_text='<m>'.__('你已经被对方加入黑名单！','jinsom').'</m>';
}
?>
<div class="jinsom-get-redbag-content">
<div class="header">
<div class="redbag-desc">“ <?php echo $post_data->post_content;?> ”</div>
<div class="jinsom-get-redbag-my-credit"><?php echo $redbag_text;?></div>
<div class="userinfo">
<a href="<?php echo jinsom_userlink($author_id);?>" target="_blank">
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
<?php echo jinsom_verify($author_id);?>
</a>
<div class="name"><?php echo jinsom_nickname_link($author_id);?></div>
</div>
</div>
<div class="info"><?php _e('领取','jinsom');?><?php echo $had_get_redbag_number;?>/<?php echo $redbag_number;?><?php _e('个','jinsom');?>，<?php _e('剩余','jinsom');?><?php echo $redbag_surplus_credit.$credit_name;?><n><?php echo $type_text;?></n></div>
<div class="list">
<?php 
$result= $wpdb->get_results("SELECT * FROM $table_name WHERE post_id=$post_id GROUP BY user_id;");

if($redbag_type!='average'&&$redbag_credit!=$redbag_number){
$max=0;
foreach ($result as $data_max) {
if($data_max->credit>$max){
$max=$data_max->credit;
}
}
}

foreach ($result as $data) {
if($redbag_type!='average'&&$redbag_credit!=$redbag_number){if($max==$data->credit){$nice='<span>'.__('运气王','jinsom').'</span>';}else{$nice='';}}else{$nice='';}
echo '<li>'.$nice.'<div class="avatarimg"><a href="'.jinsom_userlink($data->user_id).'" target="_blank">'.jinsom_avatar($data->user_id,'40',avatar_type($data->user_id)).jinsom_verify($data->user_id).'</a></div><div class="info"><div class="name">'.jinsom_nickname_link($data->user_id).'</div><div class="time">'.$data->time.'</div></div><div class="credit">'.$data->credit.$credit_name.'</div></li>';
}
?>
</div>
</div>