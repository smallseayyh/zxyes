<?php 
require( '../../../../../wp-load.php' );
$page=(int)$_POST['page'];
$limit=20;
$offset = ($page-1)*$limit;
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$now_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY do_time DESC LIMIT $offset,$limit");

if($now_data){
foreach ($now_data as $datas) {
$now_user_id=$datas->user_id;
$now_type=$datas->type;
if($now_type=='comment'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.sprintf(__( '评论了<n>%s</n>的内容','jinsom'),jinsom_nickname($datas->remark)).'</a>';
}else if($now_type=='comment-bbs'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.sprintf(__( '回复了<n>%s</n>的帖子','jinsom'),jinsom_nickname($datas->remark)).'</a>';
}else if($now_type=='comment-bbs-floor'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.sprintf(__( '回复了<n>%s</n>的楼层帖子','jinsom'),jinsom_nickname($datas->remark)).'</a>';
}else if($now_type=='reward'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.sprintf(__( '打赏了<n>%s</n>','jinsom'),jinsom_nickname($datas->remark)).'</a>';
}else if($now_type=='buy'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.__('购买了付费内容','jinsom').'</a>';
}else if($now_type=='reprint-floor'||$now_type=='reprint'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.__('转发了内容','jinsom').'</a>';
}else if($now_type=='gift'){
$now_content='<a href="'.jinsom_userlink($datas->post_id).'" target="_blank">'.sprintf(__( '给<n>%s</n>赠送了礼物','jinsom'),jinsom_nickname($datas->post_id)).'</a>';
}else if($now_type=='vote'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.__('参与了投票','jinsom').'</a>';
}else if($now_type=='activity'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.__('参与了活动','jinsom').'</a>';
}else if($now_type=='transfer'){
$now_content='<a href="'.jinsom_userlink($datas->post_id).'" target="_blank">'.sprintf(__( '给<n>%s</n>转了账','jinsom'),jinsom_nickname($datas->post_id)).'</a>';
}else if($now_type=='reg'){
$now_content='<a href="'.jinsom_userlink($now_user_id).'" target="_blank">'.__('加入了','jinsom').'<n>'.jinsom_get_option('jinsom_site_name').'</n></a>';
}else if($now_type=='use_key'){
$now_content='<a>'.__('使用了卡密','jinsom').'</a>';
}else if($now_type=='password-post'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.__('查看了密码内容','jinsom').'</a>';
}else if($now_type=='get_vip_number'){
$now_content='<a>'.__('领取了会员成长值','jinsom').'</a>';
}else if($now_type=='recharge-vip'){
$now_content='<a>'.__('开通了VIP会员','jinsom').'</a>';
}else if($now_type=='recharge-credit'){
$now_content='<a>充值了'.jinsom_get_option('jinsom_credit_name').'</a>';
}else if($now_type=='blacklist_bail'){
$now_content='<a href="'.jinsom_userlink($datas->remark).'" target="_blank">'.__('保释了黑名单用户','jinsom').'<n>'.jinsom_nickname($datas->remark).'</n></a>';
}else if($now_type=='answer'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.sprintf(__( '采纳了<n>%s</n>的回答','jinsom'),jinsom_nickname($datas->remark)).'</a>';
}else if($now_type=='cash'){
$now_content='<a href="'.jinsom_userlink($now_user_id).'" target="_blank">'.__('申请了提现','jinsom').'</a>';
}else if($now_type=='comment_up'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.sprintf(__( '赞了<n>%s</n>的评论','jinsom'),jinsom_nickname($datas->remark)).'</a>';
}else if($now_type=='follow'){
$now_content='<a href="'.jinsom_userlink($datas->remark).'" target="_blank">'.sprintf(__( '关注了<n>%s</n>','jinsom'),jinsom_nickname($datas->remark)).'</a>';
}else if($now_type=='redbag'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.__('发了一个红包','jinsom').'</a>';
}else if($now_type=='task-treasure'){
$now_content='<a href="'.jinsom_userlink($now_user_id).'" target="_blank">'.sprintf(__( '领取了%s奖励','jinsom'),$datas->remark).'</a>';
}else if($now_type=='buy_goods'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.__('购买了商品','jinsom').'</a>';
}else if($now_type=='update_nickname'){
$now_content='<a href="'.jinsom_userlink($now_user_id).'" target="_blank">'.__('修改了昵称','jinsom').'</a>';
}else if($now_type=='luck_draw_honor'){
$now_content='<a href="'.jinsom_userlink($now_user_id).'" target="_blank">'.__('抽奖获得头衔','jinsom').'*'.$datas->remark.'</a>';
}else if($now_type=='sign-treasure'){
$now_content='<a href="'.jinsom_userlink($now_user_id).'" target="_blank">'.__('领取了签到宝箱奖励','jinsom').'</a>';
}else if($now_type=='goods-comment'){
$now_content='<a href="'.get_the_permalink($datas->post_id).'" target="_blank">'.__('评价了商品','jinsom').'</a>';
}else{
$now_content='<a>'.__('在无聊发呆','jinsom').'</a>';
}


echo '
<li>
<div class="header">
<a href="'.jinsom_userlink($now_user_id).'" target="_blank">'.jinsom_avatar($now_user_id,'100',avatar_type($now_user_id)).jinsom_verify($now_user_id).'</a>
<span class="name">'.jinsom_nickname_link($now_user_id).'</span>
'.jinsom_follow_button_home($now_user_id).'
<span class="time">'.jinsom_timeago($datas->do_time).'</span>
</div>
<div class="content">
'.$now_content.'
</div>
</li>';
}

if($_POST['type']==1){
echo '<div class="jinsom-now-more" onclick="jinsom_more_now()">'.__('加载更多','jinsom').'</div>';
}

}else{
if($_POST['type']==1){
echo jinsom_empty(__('暂没有记录','jinsom'));
}else{
echo 0;
}
}

?>




