<?php 
require( '../../../../../wp-load.php' );
//发表 权限
$user_id=$current_user->ID;
$type=$_POST['type'];
$bbs_name=jinsom_get_option('jinsom_bbs_name');//论坛名称

if($type=='bbs'){//帖子
$bbs_id=(int)$_POST['bbs_id'];
$category=get_category($bbs_id);
$cat_parents=$category->parent;
if($cat_parents>0){//如果当前论坛ID属于子论坛
$bbs_id=$cat_parents;
// $data_arr['code']=0;
// $data_arr['msg']='无法在'.$bbs_name.'的子版块发表内容！';
}


$user_id=$current_user->ID;
$bbs_power=get_term_meta($bbs_id,'bbs_power',true);

//权限
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
if(is_user_logged_in()){
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}

if($bbs_power==0){//全部登录用户且不是黑名单都可以发帖
$data_arr['code']=1;
}else if($bbs_power==1){//vip才能发帖
if(is_vip($user_id)||$is_bbs_admin){
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许VIP用户发表内容！';
} 
}else if($bbs_power==2){//认证用户才能发帖
$user_verify=get_user_meta($user_id, 'verify', true );
if($user_verify||$is_bbs_admin){
$data_arr['code']=1; 
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许认证用户发表内容！';
}
}else if($bbs_power==3){//管理团队才可以发帖
if($is_bbs_admin){
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许管理团队发表内容！';
}
}else if($bbs_power==4){//关注本论坛才可以发帖
if(jinsom_is_bbs_like($user_id,$bbs_id)||$is_bbs_admin){
$data_arr['code']=1;
}else{
$data_arr['code']=0; 
$data_arr['msg']='该'.$bbs_name.'需要关注之后才允许发表内容！';
}
}else if($bbs_power==5){//有头衔的用户
if(get_user_meta($user_id,'user_honor',true)||$is_bbs_admin){
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许拥有头衔的用户发表内容！';
}
}else if($bbs_power==6){//指定经验用户才能发帖
$publish_power_exp=(int)get_term_meta($bbs_id,'publish_power_exp',true);
$current_user_lv=jinsom_get_user_exp($user_id);//当前用户经验
if($current_user_lv>= $publish_power_exp||$is_bbs_admin){//当前用户等级是否大于或等于指定的等级
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许经验值大于 <font style="color:#f00;">'.$publish_power_exp.'</font> 的用户发表内容！';
}
}else if($bbs_power==7){//指定头衔
if(!$is_bbs_admin){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$publish_power_honor=get_term_meta($bbs_id,'publish_power_honor',true);
$publish_power_honor_arr=explode(",",$publish_power_honor);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($publish_power_honor_arr,$user_honor_arr)){	
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许指定头衔的用户发表内容！';
}else{
$data_arr['code']=1;	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许指定头衔的用户发表内容！';
}
}else{
$data_arr['code']=1;	
}
}else if($bbs_power==8){//指定认证类型
if(!$is_bbs_admin){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$publish_power_verify=get_term_meta($bbs_id,'publish_power_verify',true);
$publish_power_verify_arr=explode(",",$publish_power_verify);
if(!in_array($user_verify_type,$publish_power_verify_arr)){
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许指定认证类型的用户发表内容！';
}else{
$data_arr['code']=1;
}
}else{
$data_arr['code']=0;
$data_arr['msg']='该'.$bbs_name.'只允许指定认证类型的用户发表内容！';
}
}else{
$data_arr['code']=1;	
}
}


}else{//动态、音乐、文章、视频

if($type=='words'){
$power=jinsom_get_option('jinsom_publish_words_power');
$publish_exp = jinsom_get_option('jinsom_publish_words_exp');
$honor_arr = jinsom_get_option('jinsom_publish_words_honor_arr');
$verify_arr = jinsom_get_option('jinsom_publish_words_verify_arr');
}else if($type=='music'){
$power = jinsom_get_option('jinsom_publish_music_power');	
$publish_exp = jinsom_get_option('jinsom_publish_music_exp');
$honor_arr = jinsom_get_option('jinsom_publish_music_honor_arr');
$verify_arr = jinsom_get_option('jinsom_publish_music_verify_arr');
}else if($type=='video'){
$power = jinsom_get_option('jinsom_publish_video_power');
$publish_exp = jinsom_get_option('jinsom_publish_video_exp');
$honor_arr = jinsom_get_option('jinsom_publish_video_honor_arr');
$verify_arr = jinsom_get_option('jinsom_publish_video_verify_arr');
}else if($type=='single'){
$power = jinsom_get_option('jinsom_publish_single_power');
$publish_exp = jinsom_get_option('jinsom_publish_single_exp');
$honor_arr = jinsom_get_option('jinsom_publish_single_honor_arr');
$verify_arr = jinsom_get_option('jinsom_publish_single_verify_arr');
}else if($type=='secret'){
$power = jinsom_get_option('jinsom_publish_secret_power');
$publish_exp = jinsom_get_option('jinsom_publish_secret_exp');
$honor_arr = jinsom_get_option('jinsom_publish_secret_honor_arr');
$verify_arr = jinsom_get_option('jinsom_publish_secret_verify_arr');
}else if($type=='redbag'){
$data_arr['code']=1;
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}


if($power=='vip'){//VIP用户
if(is_vip($user_id)||jinsom_is_admin($user_id)){
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='会员用户才有权限发表！';
}
}else if($power=='verify'){//认证用户
$user_verify=get_user_meta($user_id,'verify',true);
if($user_verify||jinsom_is_admin($user_id)){
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='认证用户才有权限发表！';
}
}else if($power=='honor'){//头衔用户
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''||jinsom_is_admin($user_id)){
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='拥有头衔的用户才有权限发表！';
}
}else if($power=='admin'){//管理员
if(jinsom_is_admin($user_id)){
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='管理员才有权限发表！';
}
}else if($power=='exp'){//指定经验
$user_exp=jinsom_get_user_exp($user_id);//当前用户经验
if($user_exp>=$publish_exp||jinsom_is_admin($user_id)){
$data_arr['code']=1;
}else{
$data_arr['code']=0;
$data_arr['msg']='需要经验值达到'.$publish_exp.'，才有权限发表！';
}
}else if($power=='honor_arr'){//指定头衔
if(!jinsom_is_admin($user_id)){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$publish_power_honor_arr=explode(",",$honor_arr);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($publish_power_honor_arr,$user_honor_arr)){	
$data_arr['code']=0;
$data_arr['msg']='只允许指定头衔的用户发表！';
}else{
$data_arr['code']=1;	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='只允许指定头衔的用户发表！';
}
}else{
$data_arr['code']=1;	
}
}else if($power=='verify_arr'){//指定认证类型
if(!jinsom_is_admin($user_id)){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$publish_power_verify_arr=explode(",",$verify_arr);
if(!in_array($user_verify_type,$publish_power_verify_arr)){
$data_arr['code']=0;
$data_arr['msg']='只允许指定认证类型的用户发表！';
}else{
$data_arr['code']=1;
}
}else{
$data_arr['code']=0;
$data_arr['msg']='只允许指定认证类型的用户发表！';
}
}else{
$data_arr['code']=1;	
}
}else if($power=='login'){
$data_arr['code']=1;
}

}


header('content-type:application/json');
echo json_encode($data_arr);
