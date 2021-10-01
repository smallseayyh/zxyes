<?php
// $theme_url=get_template_directory();
// $user_id=$current_user->ID;
// $author_id=get_the_author_meta('ID');
// $post_id=get_the_ID();

$category_a = get_the_category();
if(count($category_a)>1){
$category=get_category($category_a[0]->term_id);
$cat_parents=$category->parent;
if($cat_parents==0){//判断该分类是否有父级
$bbs_id=$category_a[0]->term_id;
// $list_cat_name=$category_a[0]->cat_name;
// $child_cat_id=$category_a[1]->term_id; 
$child_name='<span class="bbs-name">'.$category_a[1]->cat_name.'</span>';
}else{
$bbs_id=$category_a[1]->term_id;
// $list_cat_name=$category_a[1]->cat_name;
// $child_cat_id=$category_a[0]->term_id;    
$child_name='<span class="bbs-name">'.$category_a[0]->cat_name.'</span>';
}
}else{
$bbs_id=$category_a[0]->term_id; 
// $list_cat_name=$category_a[0]->cat_name;
// $child_cat_id=$category_a[0]->term_id;  
$child_name='<span class="bbs-name">'.$category_a[0]->cat_name.'</span>';
}

$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);

if (is_user_logged_in()) {
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}

$bbs_visit_power=get_term_meta($bbs_id,'bbs_visit_power',true);
if($bbs_visit_power==1){
if(!is_vip($user_id)&&!$is_bbs_admin){
$post_link="javascript:layer.open({content:该内容只允许VIP用户查看,skin:'msg',time:2});";
}
}else if($bbs_visit_power==2){
$user_verify=get_user_meta($user_id, 'verify', true );
if(!$user_verify&&!$is_bbs_admin){
$post_link="javascript:layer.open({content:'该内容只允许认证用户查看',skin:'msg',time:2});";
}
}else if($bbs_visit_power==3){
if(!$is_bbs_admin){
$post_link="javascript:layer.open({content:'该内容只允许管理团队查看',skin:'msg',time:2});";
}
}else if($bbs_visit_power==4){
$user_honor=get_user_meta($user_id,'user_honor',true);
if(!$user_honor&&!$is_bbs_admin){
$post_link="javascript:layer.open({content:'该内容只允许拥有头衔的用户查看',skin:'msg',time:2});";
}
}else if($bbs_visit_power==5){
//先判断用户是否已经输入密码
if(!jinsom_is_bbs_visit_pass($bbs_id,$user_id)&&!$is_bbs_admin){
$post_link=jinsom_mobile_bbs_url($bbs_id);
}
}else if($bbs_visit_power==6){//满足经验的用户
$bbs_visit_exp=(int)get_term_meta($bbs_id,'bbs_visit_exp',true);
$current_user_lv=jinsom_get_user_exp($user_id);//当前用户经验
if($current_user_lv<$bbs_visit_exp&&!$is_bbs_admin){
$post_link="javascript:layer.open({content:'该内容只允许经验值大于'.$bbs_visit_exp.'的用户查看',skin:'msg',time:2});";
}
}else if($bbs_visit_power==7){//指定用户
$bbs_visit_user=get_term_meta($bbs_id,'bbs_visit_user',true);
$bbs_visit_user_arr=explode(",",$bbs_visit_user);
if(!in_array($user_id,$bbs_visit_user_arr)&&!$is_bbs_admin){
$post_link="javascript:layer.open({content:'该内容只允许指定的用户查看',skin:'msg',time:2});";
}
}else if($bbs_visit_power==8){//登录用户
if(!is_user_logged_in()){
$post_link="javascript:layer.open({content:'该内容只允许登录的用户查看',skin:'msg',time:2});";
}
}else if($bbs_visit_power==9){//指定头衔
if(!$is_bbs_admin){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$bbs_visit_honor=get_term_meta($bbs_id,'bbs_visit_honor',true);
$bbs_visit_honor_arr=explode(",",$bbs_visit_honor);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($bbs_visit_honor_arr,$user_honor_arr)){
$post_link="javascript:layer.open({content:'该内容只允许指定头衔的用户查看',skin:'msg',time:2});";
}
}else{
$post_link="javascript:layer.open({content:'该内容只允许指定头衔的用户查看',skin:'msg',time:2});";
}
}
}else if($bbs_visit_power==10){//指定认证类型
if(!$is_bbs_admin){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$bbs_visit_verify=get_term_meta($bbs_id,'bbs_visit_verify',true);
$bbs_visit_verify_arr=explode(",",$bbs_visit_verify);
if(!in_array($user_verify_type,$bbs_visit_verify_arr)){
$post_link="javascript:layer.open({content:'该内容只允许指定认证类型的用户查看',skin:'msg',time:2});";
}
}else{
$post_link="javascript:layer.open({content:'该内容只允许指定认证类型的用户查看',skin:'msg',time:2});";
}
}
}else if($bbs_visit_power==11){//付费访问
if(!$is_bbs_admin){
$bbs_pay_user_list=get_term_meta($bbs_id,'visit_power_pay_user_list',true);
$bbs_pay_user_list_arr=explode(",",$bbs_pay_user_list);
if($bbs_pay_user_list){
if(!in_array($user_id,$bbs_pay_user_list_arr)){
$post_link=jinsom_mobile_bbs_url($bbs_id);
}
}else{
$post_link=jinsom_mobile_bbs_url($bbs_id);
}
}
}