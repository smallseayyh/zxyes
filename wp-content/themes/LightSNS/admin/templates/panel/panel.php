<?php 
//版权为林金胜所有，未经授权，不得实施复制传播倒卖等任何侵权行为
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
if( ! class_exists( 'LightSNS_Field_panel' ) ) {
class LightSNS_Field_panel extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

global $current_user,$wpdb;
$date=date('Y-m-d');

$credit_name=jinsom_get_option('jinsom_credit_name');
$theme_url=get_template_directory_uri();
$visit_number=(int)get_option('visit_number');
update_option('visit_number',$visit_number+1);
$today_credit=(int)get_option('today_credit');
$today_comment=(int)get_option('today_comment');
$today_like=(int)get_option('today_like');
$today_follow=(int)get_option('today_follow');
$today_msg=(int)get_option('today_msg');

$today_publish = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status='publish' AND post_type='post' AND post_date like '$date%'");

$table_name_sign=$wpdb->prefix.'jin_sign';
$sign_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name_sign WHERE date='$date';");


$online_query = new WP_User_Query( array ( 
'meta_key' => 'latest_login',
'meta_value' =>date('Y-m-d H:i:s',(time()-300)),
'meta_compare'=>'>',
'number' =>-1
));

$today_login_query = new WP_User_Query( array ( 
'meta_key' => 'latest_login',
'meta_value' =>$date,
'meta_compare'=>'LIKE',
'number' =>-1
));
$today_sign_query = new WP_User_Query( array ( 
'meta_key' => 'daily_sign',
'meta_value' =>$date,
'meta_compare'=>'LIKE',
'number' =>-1
));
$admin_query = new WP_User_Query( array ( 
'meta_key' => 'user_power',
'meta_value' =>2,
'number' =>-1
));
$vip_query = new WP_User_Query( array ( 
'meta_key' => 'vip_time',
'meta_value' =>current_time('mysql'),
'meta_compare'=>'>',
'number' =>-1
));
$verify_query = new WP_User_Query( array ( 
'meta_key' => 'verify',
'meta_value' =>' ',
'meta_compare'=>'!=',
'number' =>-1
));
$honor_query = new WP_User_Query( array ( //头衔
'meta_key' => 'user_honor',
'meta_value' =>' ',
'meta_compare'=>'!=',
'number' =>-1
));
$black_query = new WP_User_Query( array ( //黑名单
'meta_key' => 'blacklist_time',
'meta_value' =>$date,
'meta_compare'=>'>',
'number' =>-1
));
$danger_query = new WP_User_Query( array ( //风险
'meta_key' => 'user_power',
'meta_value' =>4,
'number' =>-1
));

$all_users = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users;");//总人数
$all_credit = $wpdb->get_var("SELECT sum(meta_value) FROM $wpdb->usermeta WHERE meta_key='credit' ;");//总金币
$online_users=$online_query->get_total();//当前在线用户 
$today_login_users=$today_login_query->get_total();//今日登录 
$today_sign_users=$today_sign_query->get_total();//今日签到 
$today_reg_users = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users WHERE user_registered like '$date%';");//今日注册
$admin_users = $admin_query->get_total();//网站管理
$vip_users = $vip_query->get_total();//VIP用户
$verify_users = $verify_query->get_total();//认证用户
$honor_users = $honor_query->get_total();//头衔用户
$black_users = $black_query->get_total();//黑名单用户
$danger_users = $danger_query->get_total();//风险用户


//热搜
$table_name_search=$wpdb->prefix.'jin_search_note';
$top_search_data = $wpdb->get_results("SELECT *,count(`content`) as count FROM $table_name_search WHERE DATE(search_time) =CURDATE() group by `content` order by count(*) desc limit 30;");
$new_search_data = $wpdb->get_results("SELECT * FROM $table_name_search order by ID desc limit 30;");

$table_name_cash = $wpdb->prefix.'jin_cash';
$cash_count=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name_cash WHERE status='0';");
$today_cash=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name_cash WHERE cash_time like '$date%';");

$pedding_post = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status='pending' and post_type='post'");

$apply_bbs_table=$wpdb->prefix.'jin_bbs_note';
$apply_bbs_admin_number = $wpdb->get_var("SELECT COUNT(*) FROM $apply_bbs_table WHERE status='0' and type='bbs_admin'");
$apply_bbs_number = $wpdb->get_var("SELECT COUNT(*) FROM $apply_bbs_table WHERE status='0' and type='bbs'");

//商城订单
$shop_goods_table=$wpdb->prefix.'jin_shop_order';
$shop_goods_number = $wpdb->get_var("SELECT count(ID) FROM $shop_goods_table  WHERE status=1;");
?>

<div class="jinsom-admin-panel">
<div class="jinsom-admin-panel-top">
<span><?php _e('当前在线用户','jinsom');?>：<a href="/wp-admin/users.php?jinsom_user_type=online" target="_blank"><i><?php echo $online_users;?></i></a></span>
<span>网站总<?php echo $credit_name;?>：<i><?php echo (int)$all_credit;?></i></span>
<span><?php _e('今日浏览量','jinsom');?>：<i><?php echo $visit_number;?></i></span>
</div>

<div class="jinsom-admin-panel-tool">

<div class="left">

<div class="title"><span class="layui-badge-dot"></span> <?php _e('待处理事项','jinsom');?></div>
<div class="content">

<li class="opacity" onclick="jinsom_admin_shop_order_form()">
<span><?php _e('商城订单','jinsom');?></span>
<p style="color: #FFEB3B;"><?php echo $shop_goods_number;?></p>
</li>

<li class="opacity" onclick="jinsom_admin_cash_form()">
<span><?php _e('提现申请','jinsom');?></span>
<p style="color: #FFEB3B;"><?php echo $cash_count;?></p>
</li>

<li class="opacity" onclick="jinsom_admin_apply_bbs_admin_form()">
<span><?php _e('版主申请','jinsom');?></span>
<p style="color: #FFEB3B;"><?php echo $apply_bbs_admin_number;?></p>
</li>

<li class="opacity" onclick="jinsom_admin_apply_bbs_form()">
<span><?php _e('论坛申请','jinsom');?></span>
<p style="color: #FFEB3B;"><?php echo $apply_bbs_number;?></p>
</li>

<li class="opacity" onclick="layer.msg('<?php _e('请到前台右上角头像下拉菜单-内容管理进行审核','jinsom');?>');">
<span><?php _e('内容审核','jinsom');?></span>
<p style="color: #FFEB3B;"><?php echo $pedding_post;?></p>
</li>

<li class="opacity" onclick="jinsom_no()">
<span><?php _e('违规举报','jinsom');?></span>
<p>0</p>
</li>

<li class="opacity" onclick="jinsom_no()">
<span><?php _e('认证申请','jinsom');?></span>
<p>0</p>
</li>


</div>
</div>

<div class="right">
<div class="title"><span class="layui-badge-dot" style="background-color: #6ce521;"></span> <?php _e('常用工具','jinsom');?></div>
<div class="content">
<li onclick="jinsom_admin_notice_form()">
<i class="jinsom-icon jinsom-tongzhi2"></i>
<p><?php _e('全站通知','jinsom');?></p>
</li>
<li onclick="jinsom_admin_key_form()">
<i class="jinsom-icon jinsom-qiamizhifu"></i>
<p><?php _e('卡密','jinsom');?></p>
</li>
<li onclick="jinsom_admin_invite_code_form()">
<i class="jinsom-icon jinsom-yaoqing"></i>
<p><?php _e('邀请码','jinsom');?></p>
</li>
<li onclick="jinsom_multiple_reg_form()">
<i class="jinsom-icon jinsom-zhucefujia"></i>
<p><?php _e('批量注册','jinsom');?></p>
</li>
<li onclick="jinsom_multiple_import_form()">
<i class="jinsom-icon jinsom-daoru"></i>
<p><?php _e('视频导入','jinsom');?></p>
</li>
</div>
</div>

</div>

<div class="jinsom-admin-panel-box clear">

<li class="opacity" style="background-color: #578ebe;">
<a href="/wp-admin/users.php?jinsom_user_type=today_login" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-users"></i></div>
<div class="info">
<p><span><?php echo $today_login_users;?></span><?php _e('人','jinsom');?></p>
<p><?php _e('今日登录','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #e35b5a;">
<a>
<div class="icon"><i class="jinsom-icon jinsom-qiandao"></i></div>
<div class="info">
<p><span><?php echo $sign_count;?></span><?php _e('人','jinsom');?></p>
<p><?php _e('今日签到','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #4ec1b9;">
<a href="/wp-admin/users.php?orderby=user_registered&order=desc" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-register-copy"></i></div>
<div class="info">
<p><span><?php echo $today_reg_users;?></span><?php _e('人','jinsom');?></p>
<p><?php _e('今日注册','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #b5a2d8;" onclick="jinsom_admin_recharge_form()">
<a>
<div class="icon"><i class="jinsom-icon jinsom-chongzhi"></i></div>
<div class="info">
<p><span><?php echo jinsom_views_show($today_credit);?></span><?php echo $credit_name;?></p>
<p><?php _e('今日充值','jinsom');?></p>
</div>
</a>
</li>


<li class="opacity" style="background-color: #65CEA7;" onclick="jinsom_admin_cash_form()">
<a>
<div class="icon"><i class="jinsom-icon jinsom-tixian"></i></div>
<div class="info">
<p><span><?php echo $today_cash;?></span><?php _e('条','jinsom');?></p>
<p><?php _e('今日提现','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #e69215;">
<a href="/wp-admin/edit.php" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-fabiao-"></i></div>
<div class="info">
<p><span><?php echo $today_publish;?></span><?php _e('条','jinsom');?></p>
<p><?php _e('今日发表','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #4CAF50;">
<a href="/wp-admin/edit-comments.php" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-huifu"></i></div>
<div class="info">
<p><span><?php echo $today_comment;?></span><?php _e('条','jinsom');?></p>
<p><?php _e('今日评论','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #e28572;">
<a>
<div class="icon"><i class="jinsom-icon jinsom-guanzhu2"></i></div>
<div class="info">
<p><span><?php echo $today_like;?></span><?php _e('次','jinsom');?></p>
<p><?php _e('今日喜欢','jinsom');?></p>
</div>
</a>
</li>


<li class="opacity" style="background-color: #48CFAD;">
<a>
<div class="icon"><i class="jinsom-icon jinsom-guanzhu5"></i></div>
<div class="info">
<p><span><?php echo $today_follow;?></span><?php _e('次','jinsom');?></p>
<p><?php _e('今日关注','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #bebfbf;" onclick="jinsom_admin_chat_note_form()">
<a>
<div class="icon"><i class="jinsom-icon jinsom-pinglun"></i></div>
<div class="info">
<p><span><?php echo $today_msg;?></span><?php _e('条','jinsom');?></p>
<p><?php _e('今日消息','jinsom');?></p>
</div>
</a>
</li>


<li class="opacity" style="background-color: #da542e;">
<a href="/wp-admin/users.php" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-qixin-qunzu"></i></div>
<div class="info">
<p><span><?php echo $all_users;?></span><?php _e('人','jinsom');?></p>
<p><?php _e('全站用户','jinsom');?></p>
</div>
</a>
</li>



<li class="opacity" style="background-color: #4a9cde;">
<a href="/wp-admin/users.php?jinsom_user_type=2" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-guanliyuan"></i></div>
<div class="info">
<p><span><?php echo $admin_users;?></span><?php _e('人','jinsom');?></p>
<p><?php _e('网站管理','jinsom');?></p>
</div>
</a>
</li>


<li class="opacity" style="background-color: #eaaf52;">
<a href="/wp-admin/users.php?jinsom_user_type=vip" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-vip1"></i></div>
<div class="info">
<p><span><?php echo $vip_users;?></span><?php _e('人','jinsom');?></p>
<p><?php _e('VIP用户','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #949FB1;">
<a href="/wp-admin/users.php?jinsom_user_type=verify" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-renzheng"></i></div>
<div class="info">
<p><span><?php echo $verify_users;?></span><?php _e('人','jinsom');?></p>
<p><?php _e('认证用户','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #EC87C0;">
<a href="/wp-admin/users.php?jinsom_user_type=honor" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-wodexunzhang"></i></div>
<div class="info">
<p><span><?php echo $honor_users;?></span><?php _e('人','jinsom');?></p>
<p><?php _e('头衔用户','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #4f5c65;">
<a href="/wp-admin/users.php?jinsom_user_type=blacklist" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-heimingdan1"></i></div>
<div class="info">
<p><span><?php echo $black_users;?></span><?php _e('人','jinsom');?></p>
<p><?php _e('黑名单用户','jinsom');?></p>
</div>
</a>
</li>

<li class="opacity" style="background-color: #b0b0b0;">
<a href="/wp-admin/users.php?jinsom_user_type=4" target="_blank">
<div class="icon"><i class="jinsom-icon jinsom-heimingdan"></i></div>
<div class="info">
<p><span><?php echo $danger_users;?></span><?php _e('人','jinsom');?></p>
<p><?php _e('风险用户','jinsom');?></p>
</div>
</a>
</li>

</div>


<div class="jinsom-admin-panel-search-note clear">
<div class="left">
<h3><?php _e('今日热搜','jinsom');?></h3>
<div class="content">
<div class="table-title">
<span><?php _e('搜索词','jinsom');?></span>
<span><?php _e('次数','jinsom');?></span>
</div>
<div class="table-list">
<?php 
if($top_search_data){
$i=1;
foreach ($top_search_data as $data) {
echo '
<li>
<span><i></i><a href="'.home_url().'/?s='.$data->content.'" target="_blank" title="'.$data->content.'">'.$data->content.'</a></span>
<span>'.$data->count.'</span>
</li>
';
$i++;
}
}else{
echo jinsom_empty();
}
?>
</div>
</div>
</div>

<div class="right">
<h3><?php _e('最新搜索','jinsom');?></h3>
<div class="content">
<div class="table-title">
<span><?php _e('搜索词','jinsom');?></span>
<span><?php _e('用户','jinsom');?></span>
<span><?php _e('类型','jinsom');?></span>
<span><?php _e('时间','jinsom');?></span>
</div>
<div class="table-list">
<?php 
if($new_search_data){
foreach ($new_search_data as $data) {
$search_user=$data->user_id;
$nickname=get_user_meta($search_user,'nickname',true);
if(!$search_user){
$search_user=__('游客','jinsom');
}else{
$search_user='<a href="'.get_author_posts_url($search_user).'" target="_blank">'.$nickname.'</a>';
}
echo '
<li>
<span><i></i><a href="'.home_url().'/?s='.$data->content.'" target="_blank" title="'.$data->content.'">'.$data->content.'</a></span>
<span title="'.__('用户IP','jinsom').'：'.$data->ip.'">'.$search_user.'</span>
<span>'.$data->type.'</span>
<span title="'.$data->search_time.'">'.jinsom_timeago($data->search_time).'</span>
</li>
';
$i++;
}
}else{
echo jinsom_empty();
}
?>
</div>
</div>

</div>


</div>


</div>


<?php }

}
}