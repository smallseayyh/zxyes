<?php 
// 钩子&过滤器

//注册
function jinsom_register_hook($user_id){

//推广注册
$jinsom_referral_link_reg_max = (int)jinsom_get_option('jinsom_referral_link_reg_max');//注册推广上限	
if(isset($_COOKIE["invite"])&&$jinsom_referral_link_reg_max){
$invite_user_id=$_COOKIE["invite"];
$invite_number=(int)get_user_meta($invite_user_id,'invite_number',true);//用户总邀请人数
$today_invite_number=(int)get_user_meta($invite_user_id,'today_invite_number',true);//今日邀请人数

$invite_reg_credit = (int)jinsom_get_option('jinsom_referral_link_reg_credit');
$invite_reg_exp = (int)jinsom_get_option('jinsom_referral_link_reg_exp');
update_user_meta($invite_user_id,'invite_number',($invite_number+1));
update_user_meta($invite_user_id,'today_invite_number',($today_invite_number+1));
update_user_meta($user_id,'who',$invite_user_id);

if($today_invite_number<=$jinsom_referral_link_reg_max){
jinsom_update_credit($invite_user_id,$invite_reg_credit,'add','invite-reg','邀请用户注册',1,'');
jinsom_update_exp($invite_user_id,$invite_reg_exp,'add','邀请用户注册');
//记录推广获利
$referral_credit=(int)get_user_meta($invite_user_id,'referral_credit',true);
update_user_meta($invite_user_id,'referral_credit',$referral_credit+$invite_reg_credit);
}

}

$jinsom_reg_im_tip_on_off = jinsom_get_option('jinsom_reg_im_tip_on_off');
$jinsom_reg_im_notice = jinsom_get_option('jinsom_reg_im_notice');
$rand_avatar_number = jinsom_get_option('jinsom_user_rand_avatar_number');
if(empty($rand_avatar_number)){$rand_avatar_number=40;}
if($jinsom_reg_im_tip_on_off){
jinsom_im_tips($user_id,$jinsom_reg_im_notice);
}

$rand_avatar=rand(1,$rand_avatar_number);
update_user_meta($user_id,'default_avatar',$rand_avatar.'.png');
$jinsom_reg_notice = jinsom_get_option('jinsom_reg_notice');
if(empty($jinsom_reg_notice)){$jinsom_reg_notice_new='欢迎你加入';}else{$jinsom_reg_notice_new=$jinsom_reg_notice;}
jinsom_add_tips($user_id,99999,0,'reg',$jinsom_reg_notice_new,'注册第一封邮件');
date_default_timezone_set(get_option('timezone_string'));
update_user_meta($user_id,'latest_login', current_time( 'mysql' ) );
$ip=$_SERVER['REMOTE_ADDR'];
update_user_meta($user_id,'latest_ip',$ip);
jinsom_update_ip($user_id);//更新用户位置

//更新注册时间
wp_update_user(array('ID' => $user_id,'user_registered' => current_time('mysql')));


//注册奖励金币、经验
$reg_credit=(int)jinsom_get_option('jinsom_reg_credit');
if($reg_credit){
jinsom_update_credit($user_id,$reg_credit,'add','reg','注册奖励',1,'');	
}
$reg_exp=(int)jinsom_get_option('jinsom_reg_exp');
if($reg_exp){
jinsom_update_exp($user_id,$reg_exp,'add','注册奖励');
}



//记录实时动态
global $wpdb;
$table_name=$wpdb->prefix.'jin_now';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,post_id,type,do_time) VALUES ('$user_id','0','reg','$time')");

}
add_action('user_register','jinsom_register_hook');


//删除文章/页面
function jinsom_delete_post_hook($post_id){
global $wpdb;
$table_name_a = $wpdb->prefix . 'jin_like';
$table_name_b = $wpdb->prefix . 'jin_notice';
$table_name_c = $wpdb->prefix . 'jin_now';
$table_name_d = $wpdb->prefix . 'jin_shop_order';
$wpdb->query( " DELETE FROM $table_name_a WHERE post_id='$post_id';");
$wpdb->query( " DELETE FROM $table_name_b WHERE post_id='$post_id';");
$wpdb->query( " DELETE FROM $table_name_c WHERE post_id='$post_id';");
$wpdb->query( " DELETE FROM $table_name_d WHERE post_id='$post_id';");//删除订单数据
}
add_action('wp_trash_post','jinsom_delete_post_hook');


//删除论坛
function jinsom_delete_bbs_hook($term){
global $wpdb;
$table_name_a = $wpdb->prefix . 'jin_bbs_like';
$table_name_b = $wpdb->prefix . 'jin_topic_like';
$wpdb->query( " DELETE FROM $table_name_a WHERE bbs_id='$term';");
$wpdb->query( " DELETE FROM $table_name_b WHERE topic_id='$term';");
}
add_action('delete_term','jinsom_delete_bbs_hook');

//删除用户
function jinsom_delete_user_hook($user_id){
global $wpdb;
$table_name_a = $wpdb->prefix . 'jin_visitor';//访客
$table_name_b = $wpdb->prefix . 'jin_follow';//关注
$table_name_c = $wpdb->prefix . 'jin_like';//喜欢
$table_name_d = $wpdb->prefix . 'jin_message';//消息
$table_name_e = $wpdb->prefix . 'jin_message_group';//群组
$table_name_f = $wpdb->prefix . 'jin_credit_note';//金币记录
$table_name_g = $wpdb->prefix . 'jin_exp_note';//经验记录
$table_name_h = $wpdb->prefix . 'jin_notice';//消息提醒
$wpdb->query( " DELETE FROM $table_name_a WHERE user_id='$user_id' || author_id='$user_id';");
$wpdb->query( " DELETE FROM $table_name_b WHERE user_id='$user_id' || follow_user_id='$user_id';");
$wpdb->query( " DELETE FROM $table_name_c WHERE user_id='$user_id';");
$wpdb->query( " DELETE FROM $table_name_d WHERE user_id='$user_id' || from_id='$user_id';");
$wpdb->query( " DELETE FROM $table_name_e WHERE user_id='$user_id';");
$wpdb->query( " DELETE FROM $table_name_f WHERE user_id='$user_id';");
$wpdb->query( " DELETE FROM $table_name_g WHERE user_id='$user_id';");
$wpdb->query( " DELETE FROM $table_name_h WHERE my_id='$user_id' || user_id='$user_id';");
$wpdb->query( " DELETE FROM $wpdb->comments WHERE user_id='$user_id';");//评论
}
add_action('delete_user','jinsom_delete_user_hook');


function jinsom_publish_post_hook($post_id){
//百度推送
$baidu_paw_type=jinsom_get_option('jinsom_baidu_paw_auto_on_off');
if($baidu_paw_type!='close'){
$baidu_paw_site=jinsom_get_option('jinsom_baidu_paw_site');
$baidu_paw_token=jinsom_get_option('jinsom_baidu_paw_token');
if(!get_post_meta($post_id,'baidu_paw',true)&&$baidu_paw_site&&$baidu_paw_token){
if($baidu_paw_type=='quick'){
$baidu_paw_type='&type=daily';
}else{
$baidu_paw_type='';
}
$baidu_paw_api='http://data.zz.baidu.com/urls?site='.$baidu_paw_site.'&token='.$baidu_paw_token.$baidu_paw_type;
$request=new WP_Http;
$result=$request->request($baidu_paw_api,array('method'=>'POST','body'=>get_permalink($post_id),'headers'=>'Content-Type:text/plain'));
$result=json_decode($result['body'],true);
if(array_key_exists('success',$result)){
update_post_meta($post_id,'baidu_paw',1);
}
}
}
}
add_action( 'publish_post', 'jinsom_publish_post_hook');


function jinsom_comment_post_hook($comment_ID){
$status=get_term_meta(1,'v',true);
$today_comment=(int)get_option('today_comment');
update_option('today_comment',$today_comment+1);
if(!$status){jinsom_update_comment_conetnt('',$comment_ID);}
}
add_action('wp_insert_comment','jinsom_comment_post_hook');



//记录使用情况
function jinsom_domain_marks(){
if(wp_get_theme()->get('ThemeURI')!=home_url()){
$data=array('domain'=>home_url(),'version'=>wp_get_theme()->get('Version'));
$url=wp_get_theme()->get('ThemeURI').'/domain.php';
$query=http_build_query($data); 
$result = file_get_contents($url.'?'.$query); 
}
}
add_action('after_switch_themes','jinsom_domain_marks');




if(is_admin()){
	
//头像过滤
function jinsom_avatar_filter($avatar,$id_or_email, $size, $default,$alt){
if(is_numeric($id_or_email)){
return jinsom_avatar($id_or_email,$size,avatar_type($id_or_email));
}else{
return jinsom_avatar($id_or_email->user_id,$size,avatar_type($id_or_email->user_id));
}
}
add_filter('get_avatar','jinsom_avatar_filter', 10,5);



//评论列表
function jinsom_manage_edit_comments_columns($newcolumns){
$newcolumns = array(
'cb' => '<input id="cb-select-all-1" type="checkbox">',
'nickname' =>'昵称',
'comment' =>'评论内容',
'response' =>'所属文章',
'date' => '日期',
);
return $newcolumns;
}
add_filter('manage_edit-comments_columns','jinsom_manage_edit_comments_columns');

//评论列表过滤
function jinsom_manage_comments_custom_column($column_name,$comment_id){
if($column_name=='nickname'){
echo '<a href="'.get_author_posts_url(jinsom_get_comments_author_id($post_id)).'" target="_blank">'.jinsom_nickname(jinsom_get_comments_author_id($post_id)).'</a>';	
}
}
add_action('manage_comments_custom_column','jinsom_manage_comments_custom_column',10,2);

//商城显示分类ID
function jinsom_shop_custom_column_header($columns){
$columns['cat_id'] = '分类ID'; 
return $columns;
}
add_filter("manage_edit-shop_columns",'jinsom_shop_custom_column_header',10);
function jinsom_shop_custom_column_content($value,$column_name,$tax_id){
if($column_name=='cat_id'){
return $tax_id;
}
}
add_action("manage_shop_custom_column",'jinsom_shop_custom_column_content',10,3);

//论坛/话题显示ID
function jinsom_admin_bbs_column($columns){
unset($columns['description']);
$columns['bbs_id'] = '论坛ID';
return $columns;
}
function jinsom_admin_topic_column($columns){
unset($columns['description']);
$columns['bbs_id'] = '话题ID';
$columns['topic_views'] = '浏览量';
return $columns;
}
add_filter('manage_edit-category_columns','jinsom_admin_bbs_column');
add_filter('manage_edit-post_tag_columns','jinsom_admin_topic_column');

function jinsom_admin_add_bbs_columns($content,$column_name,$topic_id){
if('bbs_id'==$column_name){
return $topic_id;
}else if('topic_views'==$column_name){
return (int)get_term_meta($topic_id,'topic_views',true);
}
}
add_filter('manage_category_custom_column','jinsom_admin_add_bbs_columns',10,3);
add_filter('manage_post_tag_custom_column','jinsom_admin_add_bbs_columns',10,3);


//小工具页面头部
function jinsom_widgets_admin_page_header_text() {
echo '<p style="color:#f00;padding:10px 0;font-size:16px;">论坛大厅、文章/帖子专题、论坛 的小工具是从自定义小工具的方案里面去选，每个论坛可以单独选择使用自定义的小工具方案，论坛大厅和文章/帖子专题也一样的道理</p>';
}
add_action('widgets_admin_page','jinsom_widgets_admin_page_header_text');


//内容菜单label
function jinsom_post_menu_label(){
global $menu;
global $submenu;
$menu[5][0] = '数据管理';
$submenu['edit.php'][5][0] = '内容管理';
$submenu['edit.php'][15][0] = '论坛管理';
$submenu['edit.php'][16][0] = '话题管理';
echo '';
}
add_action('admin_menu','jinsom_post_menu_label');

//论坛label
function jinsom_admin_bbs_label(){
$topic_name='论坛';
$tax=get_taxonomy('category');
$labels=$tax->labels;
$labels->name=$topic_name;
$labels->singular_name=$topic_name;
$labels->add_new_item='新增'.$topic_name;
$labels->edit_item='编辑'.$topic_name;
$labels->all_items='所有'.$topic_name;
$labels->search_items='搜索'.$topic_name;
$labels->not_found='未找到'.$topic_name;
$labels->no_terms='没有'.$topic_name;
$labels->items_list= $topic_name.'列表';
$labels->menu_name=$topic_name;
$labels->view_item='查看'.$topic_name;
}
add_action('init','jinsom_admin_bbs_label');

//话题label
function jinsom_admin_topic_label(){
$topic_name='话题';
$tax=get_taxonomy('post_tag');
$labels=$tax->labels;
$labels->name=$topic_name;
$labels->singular_name=$topic_name;
$labels->add_new_item='新增'.$topic_name;
$labels->edit_item='编辑'.$topic_name;
$labels->all_items='所有'.$topic_name;
$labels->search_items='搜索'.$topic_name;
$labels->not_found='未找到'.$topic_name;
$labels->no_terms='没有'.$topic_name;
$labels->items_list= $topic_name.'列表';
$labels->menu_name=$topic_name;
$labels->view_item='查看'.$topic_name;
}
add_action('init','jinsom_admin_topic_label');




}//判断是否是后台


