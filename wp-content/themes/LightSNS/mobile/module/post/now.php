<?php 
//实时动态
require( '../../../../../../wp-load.php' );
if(isset($_POST['page'])){
$page=(int)$_POST['page'];
}else{
$page=1;
}
$limit=20;
$offset = ($page-1)*$limit;
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$now_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY do_time DESC LIMIT $offset,$limit");

if($now_data){
foreach ($now_data as $datas) {
$now_user_id=$datas->user_id;
$now_type=$datas->type;
$author_url=jinsom_mobile_author_url($now_user_id);
if($now_type=='comment'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=sprintf(__( '评论了<n>%s</n>的内容','jinsom'),jinsom_nickname($datas->remark));
}else if($now_type=='comment-bbs'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=sprintf(__( '回复了<n>%s</n>的帖子','jinsom'),jinsom_nickname($datas->remark));
}else if($now_type=='comment-bbs-floor'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=sprintf(__( '回复了<n>%s</n>的楼层帖子','jinsom'),jinsom_nickname($datas->remark));
}else if($now_type=='reward'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=sprintf(__( '打赏了<n>%s</n>','jinsom'),jinsom_nickname($datas->remark));
}else if($now_type=='buy'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=__('购买了付费内容','jinsom');
}else if($now_type=='reprint-floor'||$now_type=='reprint'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=__('转发了内容','jinsom');
}else if($now_type=='gift'){
$post_url=jinsom_mobile_author_url($datas->post_id);
$now_content=sprintf(__( '给<n>%s</n>送了礼物','jinsom'),jinsom_nickname($datas->post_id));
}else if($now_type=='vote'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=__('参与了投票','jinsom');
}else if($now_type=='activity'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=__('参与了活动','jinsom');
}else if($now_type=='transfer'){
$post_url=jinsom_mobile_author_url($datas->post_id);
$now_content=sprintf(__( '给<n>%s</n>转了账','jinsom'),jinsom_nickname($datas->post_id));
}else if($now_type=='reg'){
$post_url=$author_url;
$now_content=sprintf(__( '加入了%s','jinsom'),jinsom_get_option('jinsom_site_name'));
}else if($now_type=='use_key'){
$post_url=$author_url;
$now_content=__('使用了卡密','jinsom');
}else if($now_type=='password-post'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=__('查看了密码内容','jinsom');
}else if($now_type=='get_vip_number'){
$post_url=$author_url;
$now_content=__('领取了会员成长值','jinsom');
}else if($now_type=='recharge-vip'){
$post_url=$author_url;
$now_content=__('开通了VIP会员','jinsom');
}else if($now_type=='recharge-credit'){
$post_url=$author_url;
$now_content='充值了'.jinsom_get_option('jinsom_credit_name');
}else if($now_type=='blacklist_bail'){
$post_url=jinsom_mobile_author_url($datas->remark);
$now_content=sprintf(__( '保释了黑名单用户<n>%s</n>','jinsom'),jinsom_nickname($datas->remark));
}else if($now_type=='answer'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=sprintf(__( '采纳了<n>%s</n>的回复','jinsom'),jinsom_nickname($datas->remark));
}else if($now_type=='cash'){
$post_url=$author_url;
$now_content=__('申请了提现','jinsom');
}else if($now_type=='comment_up'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=sprintf(__( '赞了<n>%s</n>的回复','jinsom'),jinsom_nickname($datas->remark));
}else if($now_type=='follow'){
$post_url=jinsom_mobile_author_url($datas->remark);
$now_content=sprintf(__( '关注了<n>%s</n>','jinsom'),jinsom_nickname($datas->remark));
}else if($now_type=='redbag'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=__('发了一个红包','jinsom');
}else if($now_type=='task-treasure'){
$post_url=$author_url;
$now_content=sprintf(__( '领取了%s奖励','jinsom'),$datas->remark);
}else if($now_type=='buy_goods'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=__('购买了商品','jinsom');
}else if($now_type=='update_nickname'){
$post_url=$author_url;
$now_content=__('修改了昵称','jinsom');
}else if($now_type=='luck_draw_honor'){
$post_url=$author_url;
$now_content=sprintf(__( '抽奖获得头衔*%s','jinsom'),$datas->remark);
}else if($now_type=='sign-treasure'){
$post_url=$author_url;
$now_content=__('领取了签到宝箱奖励','jinsom');
}else if($now_type=='goods-comment'){
$post_url=jinsom_mobile_post_url($datas->post_id);
$now_content=__('评价了商品','jinsom');
}else{
$now_content=__('在无聊发呆','jinsom');
}




echo '
<li>
<div class="item-content">
<div class="item-media">
<a href="'.$author_url.'" class="link">
'.$status.'
'.jinsom_avatar($now_user_id,'40',avatar_type($now_user_id)).jinsom_verify($now_user_id).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="'.$post_url.'" class="link">
<div class="name">'.jinsom_nickname($now_user_id).jinsom_vip($now_user_id).'</div>
<div class="desc">'.$now_content.'</div>
</a>
</div>
<div class="time">'.jinsom_timeago($datas->do_time).'</div>
</div>
</div>
</li>
';
}

}else{
if(isset($_POST['page'])){
echo 0;
}else{
echo jinsom_empty(__('没有任何实时动态','jinsom'));
}
}