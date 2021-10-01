<?php 

//新建表
function jinsom_table_install(){
global $wpdb;
require_once(ABSPATH.'wp-admin/includes/upgrade.php'); 

//用户喜欢
$table_name_like = $wpdb->prefix . 'jin_like';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_like'") != $table_name_like ){
$sql_like = " CREATE TABLE `$table_name_like` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`post_id` int,
`user_id` int,
`type` text,
`like_time` datetime,
`status` int
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_like);
}

//单对单聊天
$table_name_message = $wpdb->prefix . 'jin_message';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_message'") != $table_name_message ){  
$sql_message = " CREATE TABLE `$table_name_message` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`from_id` int,
`status` int,
`msg_date` datetime,
`msg_content` longtext character set utf8mb4 collate utf8mb4_unicode_ci default null
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_message);
}

//群聊
$table_name_message_group = $wpdb->prefix . 'jin_message_group';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_message_group'") != $table_name_message_group ){ 
$sql_message_group = " CREATE TABLE `$table_name_message_group` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`bbs_id` int,
`user_id` int,
`type` int,
`status` int,
`msg_time` datetime,
`content` longtext character set utf8mb4 collate utf8mb4_unicode_ci default null
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_message_group);
}


//实时动态
$table_name_now = $wpdb->prefix . 'jin_now';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_now'") != $table_name_now ){
$sql_now = " CREATE TABLE `$table_name_now` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`post_id` int,
`type` text,
`do_time` datetime,
`remark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_now);
}

//订单
$table_name_order = $wpdb->prefix . 'jin_order';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_order'") != $table_name_order ){  
$sql_order = " CREATE TABLE `$table_name_order` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`out_trade_no` text,
`trade_no` text,
`trade_status` text,
`total_fee` text,
`user_id` int,
`buyer_email` text,
`type` text,
`content` text,
`trade_time` datetime
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_order);
}

//消息提醒
$table_name_notice = $wpdb->prefix . 'jin_notice';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_notice'") != $table_name_notice ){ 
$sql_notice = " CREATE TABLE `$table_name_notice` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`my_id` int,
`user_id` int,
`post_id` int,
`notice_type` longtext character set utf8mb4 collate utf8mb4_unicode_ci default null,
`status` int,
`notice_time` datetime,
`notice_content` longtext character set utf8mb4 collate utf8mb4_unicode_ci default null,
`remark` longtext character set utf8mb4 collate utf8mb4_unicode_ci default null
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_notice);
}

//金币记录
$table_name_credit_note = $wpdb->prefix . 'jin_credit_note';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_credit_note'") != $table_name_credit_note ){ 
$sql_credit_note = " CREATE TABLE `$table_name_credit_note` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`type` text,
`number` text,
`status` int,
`action` text,
`content` text,
`time` datetime,
`mark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_credit_note);
}

//经验记录
$table_name_exp_note = $wpdb->prefix . 'jin_exp_note';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_exp_note'") != $table_name_exp_note ){  
$sql_exp_note = " CREATE TABLE `$table_name_exp_note` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`type` text,
`number` int,
`content` text,
`time` datetime,
`mark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_exp_note);
}

//付费内容
$table_name_pay = $wpdb->prefix . 'jin_pay';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_pay'") != $table_name_pay ){   
$sql_pay = " CREATE TABLE `$table_name_pay` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`post_id` int,
`pay_date` datetime
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_pay);
}

//密码内容
$table_name_password = $wpdb->prefix . 'jin_password';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_password'") != $table_name_password ){  
$sql_password = " CREATE TABLE `$table_name_password` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`post_id` int,
`password_date` datetime
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_password);
}

//关注论坛
$table_name_bbs_like = $wpdb->prefix . 'jin_bbs_like';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_bbs_like'") != $table_name_bbs_like ){  
$sql_bbs_like = " CREATE TABLE `$table_name_bbs_like` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`bbs_id` int
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_bbs_like);
}

//关注话题
$table_name_topic_like = $wpdb->prefix . 'jin_topic_like';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_topic_like'") != $table_name_topic_like ){  
$sql_topic_like = " CREATE TABLE `$table_name_topic_like` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`topic_id` int
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_topic_like);
}

//关注用户
$table_name_follow = $wpdb->prefix . 'jin_follow';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_follow'") != $table_name_follow ){
$sql_follow = " CREATE TABLE `$table_name_follow` (
`id` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
`user_id` int,
`follow_user_id` int,
`follow_status` int,
`follow_time` datetime,
`remark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_follow);
}

//验证码
$table_name_code = $wpdb->prefix . 'jin_code';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_code'") != $table_name_code ){  
$sql_code = " CREATE TABLE `$table_name_code` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`code_main` text,
`code_number` text,
`code_ip` text,
`code_type` text,
`code_time` datetime,
`remark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_code);
}

//访客
$table_name_visitor = $wpdb->prefix . 'jin_visitor';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_visitor'") != $table_name_visitor ){  
$sql_visitor = " CREATE TABLE `$table_name_visitor` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`author_id` int,
`visit_time` datetime
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_visitor);
}

//投票记录
$table_name_vote = $wpdb->prefix . 'jin_vote';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_vote'") != $table_name_vote ){ 
$sql_vote = " CREATE TABLE `$table_name_vote` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`post_id` int,
`remark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_vote);
}

//卡密
$table_name_key = $wpdb->prefix . 'jin_key';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_key'") != $table_name_key ){  
$sql_key = " CREATE TABLE `$table_name_key` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`key_number` text,
`type` text,
`status` int,
`number` int,
`user_id` int,
`expiry` date,
`use_time` datetime,
`remark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_key);
}

//邀请码
$table_name_invite_code = $wpdb->prefix . 'jin_invite_code';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_invite_code'") != $table_name_invite_code ){  
$sql_invite_code = " CREATE TABLE `$table_name_invite_code` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`code` text,
`user_id` int,
`status` int,
`use_user_id` int,
`use_time` datetime,
`remark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_invite_code);
}

//推广
$table_name_referral_link = $wpdb->prefix . 'jin_referral_link';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_referral_link'") != $table_name_referral_link ){  
$sql_referral_link = " CREATE TABLE `$table_name_referral_link` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`ip` text,
`referral_time` datetime
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_referral_link);
}

//评论点赞
$table_name_comment_up = $wpdb->prefix . 'jin_comment_up';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_comment_up'") != $table_name_comment_up ){   
$sql_comment_up = " CREATE TABLE `$table_name_comment_up` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`comment_id` int,
`user_id` int,
`status` int
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_comment_up);
}


//密码论坛
$table_name_bbs_visit_pass = $wpdb->prefix . 'jin_bbs_visit_pass';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_bbs_visit_pass'") != $table_name_bbs_visit_pass ){   
$sql_bbs_visit_pass = " CREATE TABLE `$table_name_bbs_visit_pass` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`bbs_id` int,
`user_id` int
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_bbs_visit_pass);
}

//礼物
$table_name_gift = $wpdb->prefix . 'jin_gift';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_gift'") != $table_name_gift ){ 
$sql_gift = " CREATE TABLE `$table_name_gift` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`send_user_id` int,
`receive_user_id` int,
`name` text,
`img` text,
`credit` int,
`charm` int,
`number` int,
`time` datetime,
`mark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_gift);
}

//搜索记录
$table_name_search_note = $wpdb->prefix . 'jin_search_note';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_search_note'") != $table_name_search_note ){
$sql_search_note = " CREATE TABLE `$table_name_search_note` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`content` longtext character set utf8mb4 collate utf8mb4_unicode_ci default null,
`user_id` int,
`type` text,
`ip` text,
`search_time` datetime
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_search_note);
}


//抽取礼品
$table_name_luck_draw = $wpdb->prefix . 'jin_luck_draw';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_luck_draw'") != $table_name_luck_draw ){ 
$sql_luck_draw = " CREATE TABLE `$table_name_luck_draw` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`name` text,
`img` text,
`time` datetime,
`mark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_luck_draw);
}
$wpdb->query("ALTER TABLE `$table_name_luck_draw` ADD `time` DATETIME NOT NULL AFTER `img`");//不存在时间字段则自动加上去

//提现
$table_name_cash = $wpdb->prefix . 'jin_cash';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_cash'") != $table_name_cash ){ 
$sql_cash = " CREATE TABLE `$table_name_cash` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`credit` int,
`rmb` text,
`status` int,
`type` text,
`name` text,
`phone_email` text,
`cash_time` datetime,
`mark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_cash);
}

//红包
$table_name_redbag = $wpdb->prefix . 'jin_redbag';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_redbag'") != $table_name_redbag ){ 
$sql_redbag = " CREATE TABLE `$table_name_redbag` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`post_id` int,
`credit` int,
`time` datetime,
`mark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_redbag);
}

//任务
$table_name_task = $wpdb->prefix . 'jin_task';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_task'") != $table_name_task ){ 
$sql_task = " CREATE TABLE `$table_name_task` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`task_id` text,
`type` text,
`time` datetime,
`mark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_task);
}

//论坛事项-版主申请、论坛申请
$table_name_bbs_note = $wpdb->prefix . 'jin_bbs_note';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_bbs_note'") != $table_name_bbs_note ){ 
$sql_bbs_note = " CREATE TABLE `$table_name_bbs_note` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`bbs_id` int,
`type` text,
`note_type` text,
`status` int,
`title` text,
`content` text,
`time` datetime,
`mark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_bbs_note);
}

//商城订单
$table_name_shop_order = $wpdb->prefix . 'jin_shop_order';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_shop_order'") != $table_name_shop_order ){ 
$sql_shop_order = " CREATE TABLE `$table_name_shop_order` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`post_id` int,
`user_id` int,
`goods_type` text,
`price_type` text,
`price` text,
`pay_price` text,
`select_info` text,
`number` int,
`virtual_type` text,
`virtual_info` text,
`address` text,
`address_order` text,
`marks` text,
`comment_id` int,
`coupon_id` int,
`status` int,
`out_trade_no` text,
`trade_no` text,
`pay_type` text,
`shopping_cart_data` text,
`time` datetime
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_shop_order);
}


//签到表
$table_name_sign = $wpdb->prefix . 'jin_sign';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_sign'") != $table_name_sign ){ 
$sql_sign = " CREATE TABLE `$table_name_sign` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`sign_day` int,
`strtotime` text,
`date` date,
`mark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_sign);
}


//孵化表
$table_name_pet = $wpdb->prefix . 'jin_pet';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_pet'") != $table_name_pet ){ 
$sql_pet = " CREATE TABLE `$table_name_pet` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`pet_name` text,
`nest_name` text,
`egg_img` text,
`pet_img` text,
`hatch_time` int,
`protect_time` int,
`price` int,
`time` int,
`mark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_pet);
}

//宠物记录表
$table_name_pet_note = $wpdb->prefix . 'jin_pet_note';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_pet_note'") != $table_name_pet_note ){ 
$sql_pet_note = " CREATE TABLE `$table_name_pet_note` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`type` text,
`author_id` int,
`pet_name` text,
`time` datetime,
`remark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_pet_note);
}


//收藏
$table_name_collect = $wpdb->prefix . 'jin_collect';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_collect'") != $table_name_collect ){ 
$sql_collect = " CREATE TABLE `$table_name_collect` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`post_id` int,
`type` text,
`url` text,
`time` datetime,
`remark` text
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_collect);
}

//挑战
$table_name_challenge = $wpdb->prefix . 'jin_challenge';   
if( $wpdb->get_var("SHOW TABLES LIKE '$table_name_challenge'") != $table_name_challenge ){ 
$sql_challenge = " CREATE TABLE `$table_name_challenge` (
`ID` int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ID),
`user_id` int,
`challenge_user_id` int,
`type` text,
`price` int,
`user_value` text,
`challenge_user_value` text,
`description` text character set utf8mb4 collate utf8mb4_unicode_ci default null,
`time` datetime
) ENGINE = MyISAM CHARSET=utf8;";
dbDelta($sql_challenge);
}


}
add_action('load-themes.php','jinsom_table_install'); 
// add_action('after_switch_theme','jinsom_table_install'); 