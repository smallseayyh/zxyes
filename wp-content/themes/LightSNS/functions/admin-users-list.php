<?php 
//用户列表选项
function jinsom_manage_users_columns($columns){
unset($columns['role']);
unset($columns['posts']);
unset($columns['username']);
$newcolumns=array(
'cb' => '<input id="cb-select-all-1" type="checkbox">',
'id' => 'ID',
'nickname'=>'昵称',
'phone' =>'手机',
'email' =>'邮箱',
'latest_login' => '在线',
'ip' => 'IP',
'reg' => '注册',
);
if(wp_is_mobile()){
unset($newcolumns['latest_login']);
unset($newcolumns['ip']);
unset($newcolumns['reg']);
}
return $newcolumns;
}
add_filter('manage_users_columns','jinsom_manage_users_columns');

//用户列表选项过滤
function jinsom_manage_users_custom_column($value,$column_name,$user_id){
$user=get_userdata($user_id);
if('nickname'==$column_name){
return '<a href="'.jinsom_userlink($user_id).'" target="_blank">'.get_user_meta($user_id,'nickname',true).'</a>';
}else if('phone'==$column_name){
return get_user_meta($user_id,'phone',true);	
}else if('latest_login'==$column_name){
return jinsom_timeago(get_user_meta($user_id,'latest_login',true));	
}else if('ip'==$column_name){
return get_user_meta($user_id,'latest_ip',true);	
}else if('reg'==$column_name){
$user_info=get_userdata($user_id);	
return $user_info->user_registered;
}else if('id'==$column_name){
return $user_id;	
}else{
return $value;	
}
}
add_action('manage_users_custom_column','jinsom_manage_users_custom_column',20,3);

//用户列表排序
function jinsom_manage_users_sortable_columns($sortable_columns){
unset($sortable_columns['email']);
$sortable_columns['reg']='user_registered';
$sortable_columns['id']='ID';
return $sortable_columns;
}
add_filter("manage_users_sortable_columns",'jinsom_manage_users_sortable_columns');

//用户列表排序过滤
function jinsom_pre_user_query_sortable($obj){
if(!isset($_REQUEST['orderby'])){
$orderby='ID';
}else{
$orderby=$_REQUEST['orderby'];
}
if(!isset($_REQUEST['order'])){
$order='desc';
}else{
$order=$_REQUEST['order'];
}
$obj->query_orderby="ORDER BY ".$orderby." ".$order."";
}
add_action('pre_user_query','jinsom_pre_user_query_sortable');


//用户列表筛选
function jinsom_restrict_manage_users(){
$selected_admin=(!empty($_GET['jinsom_user_type']) AND $_GET['jinsom_user_type']=='2')?'selected="select"' : '';
$selected_vip=(!empty($_GET['jinsom_user_type']) AND $_GET['jinsom_user_type']=='vip')?'selected="select"' : '';
$selected_verify=(!empty($_GET['jinsom_user_type']) AND $_GET['jinsom_user_type']=='verify')?'selected="select"' : '';
$selected_honor=(!empty($_GET['jinsom_user_type']) AND $_GET['jinsom_user_type']=='honor')?'selected="select"' : '';
$selected_black=(!empty($_GET['jinsom_user_type']) AND $_GET['jinsom_user_type']=='blacklist')?'selected="select"' : '';
$selected_danger=(!empty($_GET['jinsom_user_type']) AND $_GET['jinsom_user_type']=='4')?'selected="select"' : '';
$selected_login=(!empty($_GET['jinsom_user_type']) AND $_GET['jinsom_user_type']=='today_login')?'selected="select"' : '';
$selected_online=(!empty($_GET['jinsom_user_type']) AND $_GET['jinsom_user_type']=='online')?'selected="select"' : '';
$selected_reg=(!empty($_GET['jinsom_user_type']) AND $_GET['jinsom_user_type']=='reg')?'selected="select"' : '';
echo '<select name="jinsom_user_type" style="float:none;">';
echo '<option value="">选择用户类型</option>';
echo '<option value="2" '.$selected_admin.'>管理用户</option>';
echo '<option value="vip" '.$selected_vip.'>会员用户</option>';
echo '<option value="verify" '.$selected_verify.'>认证用户</option>';
echo '<option value="honor" '.$selected_honor.'>头衔用户</option>';
echo '<option value="blacklist" '.$selected_black.'>黑名单用户</option>';
echo '<option value="4" '.$selected_danger.'>风险用户</option>';
echo '<option value="today_login" '.$selected_login.'>今日登录用户</option>';
echo '<option value="online" '.$selected_online.'>当前在线用户</option>';
echo '</select>';
echo '<input type="submit" class="button" value="筛选">';
}
add_action('restrict_manage_users','jinsom_restrict_manage_users');


//后台过滤用户数据
function jinsom_admin_users_filter_data($query){
global $pagenow;
if('users.php'==$pagenow&&isset($_GET['jinsom_user_type'])&&!empty($_GET['jinsom_user_type'])){
$jinsom_user_type=$_GET['jinsom_user_type'];
if($jinsom_user_type=='verify'){//认证
$query->set('meta_key','verify');
$query->set('meta_value',' ');
$query->set('meta_compare','!=');
}else if($jinsom_user_type=='vip'){//vip
$query->set('meta_key','vip_time');
$query->set('meta_value',current_time('mysql'));
$query->set('meta_compare','>');
}else if($jinsom_user_type=='honor'){//头衔
$query->set('meta_key','user_honor');
$query->set('meta_value',' ');
$query->set('meta_compare','!=');
}else if($jinsom_user_type=='today_login'){//今日登录
$query->set('meta_key','latest_login');
$query->set('meta_value',date('Y-m-d'));
$query->set('meta_compare','LIKE');
}else if($jinsom_user_type=='online'){//当前在线
$query->set('meta_key','latest_login');
$query->set('meta_value',date('Y-m-d H:i:s',(time()-300)));
$query->set('meta_compare','>');
}else if($jinsom_user_type=='blacklist'){//黑名单
$query->set('meta_key','blacklist_time');
$query->set('meta_value',date('Y-m-d',(time())));
$query->set('meta_compare','>');
}else{//管理、黑名单、风险
$query->set('meta_key','user_power');
$query->set('meta_value',$jinsom_user_type);	
}

}
}
add_filter('pre_get_users','jinsom_admin_users_filter_data');



//通过昵称搜索用户
function jinsom_pre_user_query_nickname($u_query){
if($u_query->query_vars['search']){
$search_query=trim($u_query->query_vars['search'],'*');
if($_REQUEST['s']==$search_query){
global $wpdb;
$u_query->query_from.= " JOIN {$wpdb->usermeta} nickname ON nickname.user_id = {$wpdb->users}.ID AND nickname.meta_key='nickname'";
$search_by=array('nickname.meta_value');
$u_query->query_where='WHERE 1=1'.$u_query->get_search_sql($search_query,$search_by,'both');
}
}
}
add_action('pre_user_query','jinsom_pre_user_query_nickname');