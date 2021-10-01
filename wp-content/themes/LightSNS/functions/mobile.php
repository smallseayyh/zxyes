<?php
//移动端函数集合

function jinsom_is_systerm(){
if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
return 'ios';
}else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
return 'android';
}else{
return 'other';
}
}

//显示用户的个人主页
function jinsom_mobile_author_url($author_id){
if(get_option('permalink_structure')){
$url=jinsom_userlink($author_id);	
}else{
$url='no';	
}
global $current_user;
$user_id=$current_user->ID;
if($author_id==$user_id){
return get_template_directory_uri().'/mobile/templates/page/member-mine.php?author_id='.$author_id.'&url='.$url;	
}else{
return get_template_directory_uri().'/mobile/templates/page/member-other.php?author_id='.$author_id.'&url='.$url;			
}
}



//显示论坛链接
function jinsom_mobile_bbs_url($bbs_id){
if(get_option('permalink_structure')){
$url=get_category_link($bbs_id);	
}else{
$url='no';	
}
return get_template_directory_uri().'/mobile/templates/page/bbs.php?bbs_id='.$bbs_id.'&url='.$url;
}

//显示话题链接
function jinsom_mobile_topic_id_url($topic_id){
if(get_option('permalink_structure')){
$url=get_tag_link($topic_id);	
}else{
$url='no';	
}
return get_template_directory_uri().'/mobile/templates/page/topic.php?topic_id='.$topic_id.'&url='.$url;
}


//获取内容的图片作为封面
function jinsom_mobile_single_img($content,$post_id){
$jinsom_upload_obj_style=jinsom_get_option('jinsom_upload_obj_style');
$upload_style='';
if($jinsom_upload_obj_style){
$upload_style=jinsom_get_option('jinsom_upload_style_oss_bbs_single_list');
}

preg_match_all("/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is",$content,$result);
$images_arr = $result[1];
$count=count($result[1]);
$html='';
if($count>=2){
for ($i=0; $i < $count; $i++) { 
if($i>2){break;}

if(strpos($images_arr[$i],'x-oss-process')!==false||strpos($images_arr[$i],'wp-content')!==false){//包含
$upload_style='';
}

if($i==2&&$count>3){$images_more='<span>+'.$count.'</span>';}else{$images_more='';}
$html.='<div style="background-image:url('.$images_arr[$i].$upload_style.');" class="opacity count-'.$count.'">'.$images_more.'</div>';
}
}else if($count==1){
for ($i=0; $i < 1; $i++) { 

if(strpos($images_arr[$i],'x-oss-process')!==false||strpos($images_arr[$i],'wp-content')!==false){//包含
$upload_style='';
}

$html.='<div style="background-image:url('.$images_arr[$i].$upload_style.');" class="opacity count-'.$count.'"></div>';
}
}
return $html;
}



//显示已经某篇文章/帖子已经喜欢的用户
function jinsom_mobile_post_like_list($post_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_like';
$list=$wpdb->get_results("SELECT * FROM $table_name WHERE post_id = $post_id and status=1 GROUP BY user_id ORDER BY like_time DESC limit 100;");
echo '<div class="jinsom-post-like clear">';
if($list){
foreach ($list as $lists) {
$user_id=$lists->user_id;
echo '<a href="'.jinsom_mobile_author_url($user_id).'" id="had_like_'.$user_id.'" class="link">';
echo jinsom_avatar($user_id, '40' , avatar_type($user_id) ).jinsom_verify($user_id);
echo '</a>';
}
}
echo '</div>';
}


//移动端关注按钮
function jinsom_mobile_follow_button($user_id,$follow_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$status = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id='$follow_id' AND follow_user_id='$user_id' AND follow_status IN(1,2)");
if(is_user_logged_in()){
if($user_id!=$follow_id){
if($status){
if($status->follow_status==2){
return '<div onclick="jinsom_follow('.$follow_id.',this);" class="jinsom-follow-'.$follow_id.' follow had"><i class="jinsom-icon jinsom-xianghuguanzhu"></i>'.__('互关','jinsom').'</div>';
}else{
return '<div onclick="jinsom_follow('.$follow_id.',this);" class="jinsom-follow-'.$follow_id.' follow had"><i class="jinsom-icon jinsom-yiguanzhu"></i>'.__('已关','jinsom').'</div>';
}
}else{
return '<div onclick="jinsom_follow('.$follow_id.',this);" class="jinsom-follow-'.$follow_id.' follow no"><i class="jinsom-icon jinsom-guanzhu"></i>'.__('关注','jinsom').'</div>';
}
}//自己不显示自己的关注按钮
}else{
return '<div class="follow" onclick="myApp.loginScreen()"><i class="jinsom-icon jinsom-guanzhu"></i>'.__('关注','jinsom').'</div>';
}//是否登录
}

//粉丝列表显示关注按钮
function jinsom_mobile_follower_list_button($user_id,$follow_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$status = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id='$follow_id' AND follow_user_id='$user_id' AND follow_status IN(1,2)");
if(is_user_logged_in()){
if($user_id!=$follow_id){
if($status){
if($status->follow_status==2){
return '<div onclick="jinsom_follow('.$follow_id.',this);" class="jinsom-follow-'.$follow_id.' item-after follow had"><i class="jinsom-icon jinsom-xianghuguanzhu"></i>'.__('互关','jinsom').'</div>';
}else{
return '<div onclick="jinsom_follow('.$follow_id.',this);" class="jinsom-follow-'.$follow_id.' item-after follow had"><i class="jinsom-icon jinsom-yiguanzhu"></i>'.__('已关','jinsom').'</div>';
}
}else{
return '<div onclick="jinsom_follow('.$follow_id.',this);" class="jinsom-follow-'.$follow_id.' item-after follow no"><i class="jinsom-icon jinsom-guanzhu"></i>'.__('关注','jinsom').'</div>';
}
}//自己不显示自己的关注按钮
}else{
return '<div class="follow item-after no open-login-screen"><i class="jinsom-icon jinsom-guanzhu"></i>'.__('关注','jinsom').'</div>';
}//是否登录
}


//输出帖子类型
function jinsom_mobile_bbs_post_type($post_id){
$html='';
$post_type=get_post_meta($post_id,'post_type',true);

//付费
if($post_type=='pay_see'){
$html.= '<span class="jinsom-bbs-post-type-pay type"></span>';
}
//vip
if($post_type=='vip_see'){
$html.= '<span class="jinsom-bbs-post-type-vip type"></span>';
}
//投票
if($post_type=='vote'){
$html.= '<span class="jinsom-bbs-post-type-vote type"></span>';
}
//投票
if($post_type=='activity'){
$html.= '<span class="jinsom-bbs-post-type-activity type"></span>';
}
//登录可见
if($post_type=='login_see'){
$html.= '<span class="jinsom-bbs-post-type-login type"></span>';
}
//回复可见
if($post_type=='comment_see'){
$html.= '<span class="jinsom-bbs-post-type-comment type"></span>';
}
//问答
if($post_type=='answer'){
$answer_floor=get_post_meta($post_id,'answer_adopt',true);
$answer_number=get_post_meta($post_id,'answer_number',true);
if($answer_floor){
$html.= '<span class="jinsom-bbs-post-type-answer ok type"></span>';
}else{
if($answer_number==0){
$html.= '<span class="jinsom-bbs-post-type-answer type">'.__('未解决','jinsom').'</span>';  
}else{
$html.= '<span class="jinsom-bbs-post-type-answer type">'.$answer_number.jinsom_get_option('jinsom_credit_name').'</span>';
}
}
}	
return $html;
}

